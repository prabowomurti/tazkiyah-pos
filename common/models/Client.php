<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "client".
 *
 * @property integer $id
 * @property string $clientname
 * @property string $label
 * @property string $db_name
 * @property string $db_username
 * @property string $db_password
 * @property string $secret_key
 */
class Client extends \common\components\coremodels\ZeedActiveRecord
{
    public static function getDb()
    {
        return \Yii::$app->db;
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
            [['clientname', 'label', 'db_name', 'db_username', 'db_password'], 'required'],
            [['clientname'], 'string', 'max' => 100],
            [['label'], 'string', 'max' => 50],
            [['db_name', 'db_username'], 'string', 'max' => 30],
            [['db_password'], 'string', 'max' => 255],
            [['clientname'], 'unique'],
            [['db_name'], 'unique'],
            [['secret_key'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'          => Yii::t('app', 'ID'),
            'clientname'  => Yii::t('app', 'Clientname'),
            'label'       => Yii::t('app', 'Label'),
            'db_name'     => Yii::t('app', 'DB Name'),
            'db_username' => Yii::t('app', 'DB Username'),
            'db_password' => Yii::t('app', 'DB Key'),
            'secret_key'  => Yii::t('app', 'Secret Key'),
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
}