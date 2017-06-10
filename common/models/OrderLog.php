<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order_log".
 *
 * @property integer $id
 * @property integer $order_id
 * @property string $status
 * @property integer $user_id
 * @property string $note
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Order $order
 */
class OrderLog extends \common\components\coremodels\ZeedActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'user_id', 'created_at', 'updated_at'], 'integer'],
            [['status'], 'string', 'max' => 50],
            [['note'], 'string', 'max' => 255],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'order_id' => Yii::t('app', 'Order ID'),
            'status' => Yii::t('app', 'Status'),
            'user_id' => Yii::t('app', 'User ID'),
            'note' => Yii::t('app', 'Note'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }

    /**
     * @inheritdoc
     * @return \common\models\activequery\OrderLogQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\activequery\OrderLogQuery(get_called_class());
    }
}
