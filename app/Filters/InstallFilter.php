<?php

namespace App\Filters;

use App\Models\SettingModel;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class InstallFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $uri = trim(service('uri')->getPath(), '/');
        $isInstallRoute = ($uri === 'install' || str_starts_with($uri, 'install/'));

        $settingModel = new SettingModel();
        $isInstalled = $settingModel->isInstalled();

        if (! $isInstalled) {
            if (! $isInstallRoute) {
                return redirect()->to('/install');
            }
        } else {
            if ($isInstallRoute) {
                return redirect()->to('/');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed
    }
}
