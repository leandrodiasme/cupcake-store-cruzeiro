<?php

namespace Tests\Unit;

use App\Controllers\Api;
use App\Controllers\Home;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class StoreBusinessRulesTest extends CIUnitTestCase
{
    /**
     * Testa validação de horário comercial no mesmo dia (ex: 09:00 às 22:00)
     */
    public function testIsStoreOpenDaytime(): void
    {
        $home = new Home();

        // Aberto durante o dia
        $this->assertTrue($home->isStoreOpen('09:00', '22:00', '2026-09-12 10:30:00'));
        $this->assertTrue($home->isStoreOpen('09:00', '22:00', '2026-09-12 15:00:00'));
        $this->assertTrue($home->isStoreOpen('09:00', '22:00', '2026-09-12 21:59:00'));

        // Fechado antes ou depois do horário
        $this->assertFalse($home->isStoreOpen('09:00', '22:00', '2026-09-12 08:00:00'));
        $this->assertFalse($home->isStoreOpen('09:00', '22:00', '2026-09-12 22:30:00'));
        $this->assertFalse($home->isStoreOpen('09:00', '22:00', '2026-09-12 03:00:00'));
    }

    /**
     * Testa validação de horário comercial noturno (ex: 18:00 às 02:00)
     */
    public function testIsStoreOpenOvernight(): void
    {
        $home = new Home();

        // Aberto à noite e início da madrugada
        $this->assertTrue($home->isStoreOpen('18:00', '02:00', '2026-09-12 20:00:00'));
        $this->assertTrue($home->isStoreOpen('18:00', '02:00', '2026-09-12 01:30:00'));

        // Fechado durante o dia
        $this->assertFalse($home->isStoreOpen('18:00', '02:00', '2026-09-12 12:00:00'));
        $this->assertFalse($home->isStoreOpen('18:00', '02:00', '2026-09-12 17:50:00'));
        $this->assertFalse($home->isStoreOpen('18:00', '02:00', '2026-09-12 03:00:00'));
    }

    /**
     * Testa a formatação correta dos dados do pedido para a mensagem do WhatsApp
     */
    public function testFormatWhatsAppMessage(): void
    {
        $api = new Api();

        $items = [
            [
                'name'     => 'Cupcake de Chocolate Belga',
                'quantity' => 2,
                'price'    => 14.50,
                'options'  => [
                    ['name' => 'Granulado Belga Extra', 'price' => 2.50]
                ]
            ],
            [
                'name'     => 'Cappuccino Cremoso Artesanal',
                'quantity' => 1,
                'price'    => 9.50,
                'options'  => []
            ]
        ];

        $message = $api->formatWhatsAppMessage(
            'Doce Sonho Cupcakes',
            42,
            'Leandro Silva',
            '(18) 99765-4321',
            'Rua das Palmeiras',
            '500',
            'Centro',
            'Araçatuba',
            '16010-000',
            'Apto 101',
            'Dinheiro',
            50.00,
            $items,
            38.50
        );

        $this->assertStringContainsString('Doce Sonho Cupcakes', $message);
        $this->assertStringContainsString('#42', $message);
        $this->assertStringContainsString('Leandro Silva', $message);
        $this->assertStringContainsString('Rua das Palmeiras, 500 - Centro', $message);
        $this->assertStringContainsString('16010-000', $message);
        $this->assertStringContainsString('Apto 101', $message);
        $this->assertStringContainsString('Cupcake de Chocolate Belga', $message);
        $this->assertStringContainsString('Granulado Belga Extra', $message);
        $this->assertStringContainsString('Cappuccino Cremoso Artesanal', $message);
        $this->assertStringContainsString('Dinheiro', $message);
        $this->assertStringContainsString('50,00', $message);
        $this->assertStringContainsString('38,50', $message);
    }
}
