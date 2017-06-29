<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "setting".
 *
 * @property integer $id
 * @property string $key
 * @property string $value
 */
class Setting extends \common\components\coremodels\ZeedActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'setting';
    }

    public function behaviors()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key'], 'required'],
            [['value'], 'string'],
            [['key'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'key' => Yii::t('app', 'Key'),
            'value' => Yii::t('app', 'Value'),
        ];
    }

    /**
     * Return value of 'value' field based on key
     * @param  string $key key
     * @return string      $value
     */
    public static function t($key = '')
    {
        $row = self::find()->where(['key' => $key]);
        if (empty($row))
            return '';

        return $row->select('value')->scalar();
    }

    /**
     * @inheritdoc
     * @return \common\models\activequery\SettingQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\activequery\SettingQuery(get_called_class());
    }
}
