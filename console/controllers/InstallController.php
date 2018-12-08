<?php 
namespace console\controllers;

use yii\console\Controller;
use common\models\User;

class InstallController extends Controller
{
    /**
     * Add new Administrator
     * @return mixed
     */
    public function actionAddAdmin()
    {
        // security check, if there is a 'super admin' added before
        $is_superadmin_exist = (bool) User::findOne(['role' => User::ROLE_SUPERADMIN]);
        if ($is_superadmin_exist)
        {
            $this->stdout('A super admin is already added' . PHP_EOL);
            return 0;
        }

        $this->stdout('ADD NEW SUPER ADMIN' . PHP_EOL);
        $this->stdout(str_repeat('-', 19) . PHP_EOL);

        $username = $this->prompt('Username :', ['required' => true]);
        $email = $this->prompt('Email :', ['required' => true]);

        if ( ! filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $this->stdout('Please give a valid email address' . PHP_EOL);
            return 0;
        }

        // TODO : use hidden prompt
        // $password = \Seld\CliPrompt\CliPrompt::hiddenPrompt();
        $password = $this->prompt('Password :', ['required' => true]);

        if (strlen($password) < 6)
        {
            $this->stdout('Password is too short, should not less than 6 characters' . PHP_EOL);
            return 0;
        }

        $admin           = new User();
        $admin->username = $username;
        $admin->email    = $email;
        $admin->setPassword($password);
        $admin->generateAuthKey();
        $admin->role     = User::ROLE_SUPERADMIN;
        $admin->status   = User::STATUS_ACTIVE;

        if ($admin->save())
            $this->stdout('Super Admin added');
        else 
            $this->stdout('Error : can not add new user : ' . $username . '. Please repeat the process.');

        $this->stdout(PHP_EOL);
        return 0;
        
    }
}