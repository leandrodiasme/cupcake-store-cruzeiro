<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\ProductClickModel;
use App\Models\SettingModel;
use CodeIgniter\Controller;

class Api extends Controller
{
    /**
     * Rastreia clique no produto para alimentar as métricas do painel
     */
    public function trackClick()
    {
        $body = $this->request->getBody();
        $json = ! empty($body) ? json_decode($body, true) : null;
        $productId = (int) ($json['product_id'] ?? $this->request->getPost('product_id'));

        if (! $productId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ID do produto inválido'])->setStatusCode(400);
        }

        $ip        = $this->request->getIPAddress();
        $userAgent = (string) $this->request->getUserAgent();

        $clickModel = new ProductClickModel();
        $clickModel->trackClick($productId, $ip, $userAgent);

        return $this->response->setJSON(['status' => 'success']);
    }

    /**
     * Persiste o pedido e gera a URL formatada do WhatsApp
     */
    public function createOrder()
    {
        $settingModel = new SettingModel();
        $settings     = $settingModel->getAllKeyValue();

        $openingTime = $settings['opening_time'] ?? '09:00';
        $closingTime = $settings['closing_time'] ?? '22:00';
        $homeCtrl    = new Home();

        if (! $homeCtrl->isStoreOpen($openingTime, $closingTime)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Desculpe, a loja está fechada no momento. Não é possível realizar pedidos fora do horário comercial.',
            ])->setStatusCode(403);
        }

        $body = preg_replace('/^[\xEF\xBB\xBF\xFE\xFF]+/', '', (string) $this->request->getBody());
        $json = ! empty($body) ? json_decode($body, true) : null;
        if (! is_array($json)) {
            $json = $this->request->getPost();
        }

        $customerName  = trim($json['customer_name'] ?? '');
        $customerPhone = trim($json['customer_phone'] ?? '');
        $cep           = trim($json['cep'] ?? '');
        $street        = trim($json['street'] ?? '');
        $number        = trim($json['number'] ?? '');
        $neighborhood  = trim($json['neighborhood'] ?? '');
        $city          = trim($json['city'] ?? '');
        $complement    = trim($json['complement'] ?? '');
        $paymentMethod = trim($json['payment_method'] ?? 'PIX');
        $changeFor     = ! empty($json['change_for']) ? (float) $json['change_for'] : null;
        $items         = $json['items'] ?? [];
        $totalAmount   = (float) ($json['total_amount'] ?? 0);

        if (empty($customerName) || empty($customerPhone) || empty($street) || empty($number) || empty($items)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Por favor, preencha todos os dados obrigatórios do cliente e de entrega.',
            ])->setStatusCode(422);
        }

        // Validação no backend impedindo troco inferior ao total (conforme teste de aceite - Erasmo Cossatto)
        if ($paymentMethod === 'Dinheiro' && $changeFor !== null && $changeFor < $totalAmount) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'O valor informado para o troco não pode ser menor que o total do pedido.',
            ])->setStatusCode(422);
        }

        // Salvar pedido no banco
        $orderModel = new OrderModel();
        $orderId    = $orderModel->insert([
            'customer_name'    => $customerName,
            'customer_phone'   => $customerPhone,
            'cep'              => $cep,
            'street'           => $street,
            'number'           => $number,
            'neighborhood'     => $neighborhood,
            'city'             => $city,
            'complement'       => $complement,
            'payment_method'   => $paymentMethod,
            'change_for'       => $changeFor,
            'total_amount'     => $totalAmount,
            'order_items_json' => json_encode($items, JSON_UNESCAPED_UNICODE),
            'status'           => 'sent_whatsapp',
            'created_at'       => date('Y-m-d H:i:s'),
        ]);

        // Formatar mensagem para o WhatsApp
        $storeName = $settings['store_name'] ?? 'Loja';
        $whatsapp  = $settings['whatsapp_number'] ?? '5518999999999';

        $textMessage = $this->formatWhatsAppMessage(
            $storeName,
            $orderId,
            $customerName,
            $customerPhone,
            $street,
            $number,
            $neighborhood,
            $city,
            $cep,
            $complement,
            $paymentMethod,
            $changeFor,
            $items,
            $totalAmount
        );

        $whatsappUrl = 'https://wa.me/' . $whatsapp . '?text=' . urlencode($textMessage);

        return $this->response->setJSON([
            'status'       => 'success',
            'order_id'     => $orderId,
            'whatsapp_url' => $whatsappUrl,
        ]);
    }

    /**
     * Formata o resumo do pedido de forma amigável e legível para o WhatsApp
     */
    public function formatWhatsAppMessage(
        string $storeName,
        int $orderId,
        string $customerName,
        string $customerPhone,
        string $street,
        string $number,
        string $neighborhood,
        string $city,
        string $cep,
        string $complement,
        string $paymentMethod,
        ?float $changeFor,
        array $items,
        float $totalAmount
    ): string {
        $msg = "✨ *NOVO PEDIDO #{$orderId} - {$storeName}* ✨\n";
        $msg .= "-------------------------------------------\n";
        $msg .= "👤 *Cliente:* {$customerName}\n";
        $msg .= "📱 *Telefone:* {$customerPhone}\n";
        $msg .= "📍 *Endereço de Entrega:*\n";
        $msg .= "   {$street}, {$number} - {$neighborhood}\n";
        $msg .= "   {$city}" . (! empty($cep) ? " (CEP: {$cep})" : "") . "\n";
        if (! empty($complement)) {
            $msg .= "   *Compl.:* {$complement}\n";
        }
        $msg .= "-------------------------------------------\n";
        $msg .= "🛍️ *ITENS DO PEDIDO:*\n";

        foreach ($items as $item) {
            $qty       = (int) ($item['quantity'] ?? 1);
            $name      = $item['name'] ?? 'Item';
            $unitPrice = (float) ($item['price'] ?? 0);
            $subtotal  = $qty * $unitPrice;
            $msg .= "• *{$qty}x {$name}* (R$ " . number_format($subtotal, 2, ',', '.') . ")\n";

            if (! empty($item['options']) && is_array($item['options'])) {
                foreach ($item['options'] as $opt) {
                    $optName  = $opt['name'] ?? '';
                    $optPrice = (float) ($opt['price'] ?? 0);
                    $msg .= "   + {$optName} (R$ " . number_format($optPrice, 2, ',', '.') . ")\n";
                }
            }
        }

        $msg .= "-------------------------------------------\n";
        $msg .= "💳 *Forma de Pagamento:* {$paymentMethod}\n";
        if ($paymentMethod === 'Dinheiro' && $changeFor) {
            $changeAmount = $changeFor - $totalAmount;
            $msg .= "   💵 *Troco para:* R$ " . number_format($changeFor, 2, ',', '.') . "\n";
            $msg .= "   💵 *Valor do troco:* R$ " . number_format(max(0, $changeAmount), 2, ',', '.') . "\n";
        }
        $msg .= "💰 *VALOR TOTAL:* R$ " . number_format($totalAmount, 2, ',', '.') . "\n";
        $msg .= "-------------------------------------------\n";
        $msg .= "Aguardando confirmação do pedido pela loja! 🧁";

        return $msg;
    }
}
