<?php

namespace App\Controllers\Admin;

use App\Models\SettingModel;
use CodeIgniter\Controller;

class Settings extends Controller
{
    protected $helpers = ['form', 'url'];

    public function index()
    {
        $settingModel = new SettingModel();
        $settings     = $settingModel->getAllKeyValue();

        return view('admin/settings/index', [
            'settings'   => $settings,
            'storeName'  => $settings['store_name'] ?? 'Minha Loja',
            'themeColor' => $settings['theme_color'] ?? '#ec4899',
        ]);
    }

    public function update()
    {
        $rules = [
            'store_name'      => 'required|min_length[3]|max_length[100]',
            'store_segment'   => 'required|min_length[2]|max_length[50]',
            'whatsapp_number' => 'required|min_length[10]|max_length[20]',
            'opening_time'    => 'required',
            'closing_time'    => 'required',
            'theme_color'     => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Por favor, preencha todos os campos obrigatórios.');
        }

        $rawWhatsapp = preg_replace('/\D/', '', (string) $this->request->getPost('whatsapp_number'));
        if (strlen($rawWhatsapp) <= 11 && ! str_starts_with($rawWhatsapp, '55')) {
            $cleanWhatsapp = '55' . $rawWhatsapp;
        } else {
            $cleanWhatsapp = $rawWhatsapp;
        }

        $settingModel = new SettingModel();
        $settingModel->setVal('store_name', $this->request->getPost('store_name'));
        $settingModel->setVal('store_segment', $this->request->getPost('store_segment'));
        $settingModel->setVal('whatsapp_number', $cleanWhatsapp);
        $settingModel->setVal('opening_time', $this->request->getPost('opening_time'));
        $settingModel->setVal('closing_time', $this->request->getPost('closing_time'));
        $settingModel->setVal('theme_color', $this->request->getPost('theme_color'));

        return redirect()->to('/admin/settings')->with('success', 'Configurações da loja atualizadas com sucesso!');
    }
}
