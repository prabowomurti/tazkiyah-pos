<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "client".
 *
 * @property integer $id
 * @property string $client_name
 * @property string $api_key
 * @property string $label
 * @property string $db_name
 * @property string $db_username
 * @property string $db_password
 * @property string $api_key
 * @property string $client_token
 */
class Client extends \common\components\coremodels\ZeedActiveRecord
{
    public static function getDb()
    {
        return \Yii::$app->db;
    }

    public function behaviors()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'client';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['client_name', 'api_key', 'label', 'db_name', 'db_username', 'db_password'], 'required'],
            [['client_name'], 'string', 'max' => 100],
            [['label'], 'string', 'max' => 50],
            [['db_name', 'db_username'], 'string', 'max' => 30],
            [['db_password'], 'string', 'max' => 255],
            [['client_name'], 'unique'],
            [['db_name'], 'unique'],
            [['api_key'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'          => Yii::t('app', 'ID'),
            'client_name' => Yii::t('app', 'Clientname'),
            'api_key'     => Yii::t('app', 'API Key'),
            'label'       => Yii::t('app', 'Label'),
            'db_name'     => Yii::t('app', 'DB Name'),
            'db_username' => Yii::t('app', 'DB Username'),
            'db_password' => Yii::t('app', 'DB Key'),
            'api_key'  => Yii::t('app', 'Secret Key'),
            'client_token'  => Yii::t('app', 'Client Token'),
        ];
    }

    /**
     * @inheritdoc
     * @return \common\models\activequery\ClientQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\activequery\ClientQuery(get_called_class());
    }

    /**
     * Generate new token or get existing one
     * @param  integer $client_id [description]
     * @return string client_token
     */
    public static function generate_new_token($client_id = 0)
    {
        if (empty($client_id))
            return null;

        // return existing value
        $client = static::findOne($client_id);
        if ( ! empty($client->client_token))
            return $client->client_token;

        // or generate new one
        $client_token = \Yii::$app->security->generateRandomString();

        while (static::findOne(['client_token' => $client_token]))
        {
            $client_token = \Yii::$app->security->generateRandomString();
        }
        // save new token
        $client->client_token = $client_token;
        $client->save();

        return $client_token;
    }
}