<?php 

namespace api\modules\v1\controllers;

use common\components\ZeedActiveController;
use common\models\Client;

class ClientController extends ZeedActiveController
{
    public $modelClass = 'common\models\Client';

    public function actions()
    {
        return ['login'];
    }

    /**
     * Using default behaviors, because we don't need basic authentication here
     * @return [type] [description]
     */
    public function behaviors()
    {
        return \yii\rest\ActiveController::behaviors();
    }

    /**
     * Login to the client to get the client_token
     * @return [type] [description]
     */
    public function actionLogin()
    {
        $params = \Yii::$app->request->post();

        if (empty($params['client_name']) || empty($params['api_key']))
            throw new \yii\web\BadRequestHttpException('Empty parameters');

        $client_name = $params['client_name'];
        $api_key = $params['api_key'];

        // generate client_token
        $client = Client::findOne(['client_name' => $client_name, 'api_key' => $api_key]);

        if (empty($client))
            throw new \yii\web\BadRequestHttpException('Wrong client name or bad credentials');

        $client_token = Client::generate_new_token($client->id);

        return ['client_label' => $client->label, 'client_token' => $client_token];
    }

}