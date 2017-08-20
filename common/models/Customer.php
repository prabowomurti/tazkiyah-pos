<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "customer".
 *
 * @property integer $id
 * @property string $username
 * @property string $phone
 * @property string $address
 * @property string $gender
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Order[] $orders
 */
class Customer extends \common\components\coremodels\ZeedActiveRecord
{
    const GENDER_MALE   = 'male';
    const GENDER_FEMALE = 'female';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username'], 'required'],
            [['address'], 'string'],
            [['created_at', 'updated_at'], 'integer'],
            [['username', 'phone'], 'string', 'max' => 255],
            [['gender'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'phone' => Yii::t('app', 'Phone'),
            'address' => Yii::t('app', 'Address'),
            'gender' => Yii::t('app', 'Gender'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['customer_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return \common\models\activequery\CustomerQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\activequery\CustomerQuery(get_called_class());
    }

    /**
     * Get gender as list
     * @param  string $index [description]
     * @return array
     */
    public static function genderAsList($index = '')
    {
        $return = [static::GENDER_MALE => 'Male', static::GENDER_FEMALE => 'Female'];

        return (( ! isset($index) || empty($index)) ? $return : $return[$index]);
    }

    /**
     * Get all customer as list, used in dropdown
     * @return array array of customers
     */
    public static function getAllAsList()
    {
        $customers = self::find()->
            select(['username', 'id', 'phone'])->
            orderBy('username')->
            all();

        $return = [];
        foreach ($customers as $customer)
        {
            $phone = (! empty($customer['phone']) ? ' (' . substr_replace($customer['phone'], 'XXXX', -4) . ')' : '');
            $return[$customer['id']] = $customer['username'] . $phone;
        }

        return $return;
    }
}
