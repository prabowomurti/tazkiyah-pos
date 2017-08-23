<?php 

namespace api\modules\v1\controllers;

use common\components\ZeedActiveController;

use common\models\User;

class UserController extends ZeedActiveController
{
    public $modelClass = 'api\modules\v1\models\User';

    public function actions()
    {
        return ['login', 'logout'];
    }

    public function actionLogin()
    {
        $params = \Yii::$app->request->post();

        if (empty($params['email']) || empty($params['password']))
            static::missingParameter('Email or Password should not be empty');

        $user = User::findOne(['email' => $params['email']]);
        if (empty($user))
            static::missingParameter('Wrong email and/or password');

        if ( ! $user->validatePassword($params['password']))
            static::exception('Wrong credentials');

        if ( ! $user->status == User::STATUS_ACTIVE)
            static::exception('User is not active');

        return ['user_name' => $user->username, 'access_token' => $user->generate_access_token()];
    }

    public function actionLogout()
    {
        static::checkAccessToken();

        $user = User::findOne(['access_token' => \Yii::$app->request->post('access_token')]);

        // delete access_token
        $user->access_token = null;
        if ( ! $user->save())
            return static::exception('Can not delete access token');

        return static::successResponse();

    }
}