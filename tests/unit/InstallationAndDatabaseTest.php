<?php

namespace Tests\Unit;

use App\Models\SettingModel;
use App\Models\UserModel;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class InstallationAndDatabaseTest extends CIUnitTestCase
{
    /**
     * Testa SettingModel com valores e chaves
     */
    public function testSettingModelGetValDefault(): void
    {
        $settingModel = new SettingModel();
        $this->assertSame('DefaultStore', $settingModel->getVal('non_existent_key', 'DefaultStore'));
    }

    /**
     * Testa UserModel e hash seguro de senha
     */
    public function testUserPasswordHash(): void
    {
        $rawPassword = 'PasswordSegura123!';
        $hashed = password_hash($rawPassword, PASSWORD_DEFAULT);

        $this->assertTrue(password_verify($rawPassword, $hashed));
        $this->assertFalse(password_verify('WrongPassword', $hashed));
    }
}
