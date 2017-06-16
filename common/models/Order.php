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
    const STATUS_ORDERED    = 'ordered';
    const STATUS_ON_PROCESS = 'on_process';
    const STATUS_CANCELED   = 'canceled';
    const STATUS_DONE       = 'done';
    const STATUS_DELIVERED  = 'delivered';

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
            [['customer_id', 'outlet_id', 'tax', 'total_price', 'created_at', 'updated_at'], 'integer'],
            [['delivery_time'], 'safe'],
            [['code'], 'string', 'max' => 20],
            [['status'], 'string', 'max' => 50],
            [['note'], 'string', 'max' => 255],
            [['total_price'], 'default', 'value' => 0],
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
     * Get status as list
     * @return array array of status
     */
    public static function getStatusAsList()
    {
        return [
            self::STATUS_ORDERED    => Yii::t('app', 'Ordered'),
            self::STATUS_ON_PROCESS => Yii::t('app', 'On Process'),
            self::STATUS_DONE       => Yii::t('app', 'Done'),
            self::STATUS_DELIVERED  => Yii::t('app', 'Delivered'),
            self::STATUS_CANCELED   => Yii::t('app', 'Canceled'),
        ];
    }

    /**
     * @inheritdoc
     * @return \common\models\activequery\OrderQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\activequery\OrderQuery(get_called_class());
    }

    /**
     * Generate code to check order status by customer
     * @return [type] [description]
     */
    public static function generateCode()
    {
        //http://mikeboers.com/blog/2013/06/28/the-ux-of-coupon-codes
        $characters = '34679ACDEFGHJKMNPRTWXY'; // minus 0, O, Q, 8, B, 1, I, L, U, V, 2, Z, 5, S

        $code = '';
        for ($i = 0; $i < 5; $i++) {
            $code .= $characters[rand(0, 21)];
        }

        while (self::findOne(['code' => $code]))
        {
            $code = self::generateCode();
        }

        return $code;
    }

    /**
     * - Save status change in order_log table
     * @param  [type] $insert            [description]
     * @param  [type] $changedAttributes [description]
     * @return [type]                    [description]
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        // generate code
        if ($insert)
        {
            $this->code = self::generateCode();
            $this->save();
        }

        $old_status = isset($changedAttributes['status']) ? $changedAttributes['status'] : $this->status;

        // do nothing when the status is not changed
        if ( ! $insert && $old_status == $this->status)
            return ;

        $status_log           = new OrderLog;

        $status_log->user_id  = Yii::$app->user->id;
        $status_log->order_id = $this->id;
        $status_log->status   = $this->status;

        $user = User::findOne(Yii::$app->user->id);

        if ($insert)
            $status_log->note = 'User ' . $user->username . ' #' . $user->id . ' makes order #' . $this->id;
        else // order's status update
            $status_log->note = 'User ' . $user->username . ' #' . $user->id . ' updates order #' . $this->id;

        $status_log->save();
    }

    /**
     * - Save log when the order is deleted
     * @return [type] [description]
     */
    public function beforeDelete()
    {
        if (parent::beforeDelete())
        {
            $status_log           = new OrderLog;

            $status_log->user_id  = Yii::$app->user->id;
            $status_log->order_id = $this->id;
            $status_log->status   = $this->status;

            $user = User::findOne(Yii::$app->user->id);

            $status_log->note = 'User ' . $user->username . ' #' . $user->id . ' just deleted order #' . $this->id;
            $status_log->save();

            return TRUE;
        }
        else
            return FALSE;
    }

}
