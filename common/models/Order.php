<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order".
 *
 * @property integer $id
 * @property integer $customer_id
 * @property integer $outlet_id
 * @property string $code
 * @property integer $tax
 * @property integer $total_price
 * @property string $status
 * @property integer $delivery_time
 * @property string $note
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Customer $customer
 * @property Outlet $outlet
 * @property OrderItem[] $orderItems
 * @property OrderLog[] $orderLogs
 */
class Order extends \common\components\coremodels\ZeedActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'outlet_id', 'tax', 'total_price', 'delivery_time', 'created_at', 'updated_at'], 'integer'],
            [['created_at', 'updated_at'], 'required'],
            [['code'], 'string', 'max' => 20],
            [['status'], 'string', 'max' => 50],
            [['note'], 'string', 'max' => 255],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'id']],
            [['outlet_id'], 'exist', 'skipOnError' => true, 'targetClass' => Outlet::className(), 'targetAttribute' => ['outlet_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'customer_id' => Yii::t('app', 'Customer ID'),
            'outlet_id' => Yii::t('app', 'Outlet ID'),
            'code' => Yii::t('app', 'Code'),
            'tax' => Yii::t('app', 'Tax'),
            'total_price' => Yii::t('app', 'Total Price'),
            'status' => Yii::t('app', 'Status'),
            'delivery_time' => Yii::t('app', 'Delivery Time'),
            'note' => Yii::t('app', 'Note'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOutlet()
    {
        return $this->hasOne(Outlet::className(), ['id' => 'outlet_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(OrderItem::className(), ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderLogs()
    {
        return $this->hasMany(OrderLog::className(), ['order_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return \common\models\activequery\OrderQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\activequery\OrderQuery(get_called_class());
    }
}
