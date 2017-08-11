<?php 

namespace common\components;

use yii\rest\ActiveController;
use yii\filters\auth\HttpBasicAuth;
use yii\web\BadRequestHttpException;

use api\modules\v1\models\Client;
use common\models\User;

/**
 * Simple and sweet active controller for our beloved API
 * 
 * @author Prabowo Murti <prabowo.murti@gmail.com>
 */
class ZeedActiveController extends ActiveController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::className(),
            'auth' => [$this, 'findIdentityByClientToken'],
            'auth' => function ($token) {
                return Client::findIdentityByClientToken($token);
            }
        ];

        return $behaviors;
    }

    /**
     * Check access_token, used almost in every request
     * @return mixed
     */
    public static function checkAccessToken()
    {
        $params = \Yii::$app->request->post();

        if (empty($params['access_token']))
            static::missingParameter('Access token should not be empty');
        
        $access_token = $params['access_token'];

        $user = User::findOne(['access_token' => $access_token, 'status' => User::STATUS_ACTIVE]);
        
        if (empty($user))
            return static::exception('Bad credentials');

    }

    /**
     * Return special exception
     * @param  string $message [description]
     * @return [type]          [description]
     */
    public static function missingParameter($message = '')
    {
        throw new BadRequestHttpException($message);
    }

    /**
     * Return special exception
     * @param  string $message [description]
     * @return [type]          [description]
     */
    public static function exception($message = '')
    {
        throw new BadRequestHttpException($message);
    }

    /**
     * Return success response format
     * @param  string $message [description]
     * @return [type]          [description]
     */
    public static function successResponse($message = 'Success')
    {
        return ['status' => 200, 'message' => $message];
    }

}