<?php 

namespace api\modules\v1\models;

use yii\web\IdentityInterface;

class Client extends \common\models\Client implements IdentityInterface
{
    public static function findIdentityByClientToken($token, $type = NULL)
    {
        return static::findOne(['client_token' => $token]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = NULL)
    {
        // doesn't have access_token field
        return null;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return false;
    }
}