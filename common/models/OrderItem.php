<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order_item".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $product_id
 * @property string $product_label
 * @property integer $product_attribute_id
 * @property double $quantity
 * @property double $discount
 * @property double $unit_price
 * @property string $note
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Order $order
 * @property ProductAttribute $productAttribute
 * @property Product $product
 */
class OrderItem extends \common\components\coremodels\ZeedActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'product_id', 'product_attribute_id', 'created_at', 'updated_at'], 'integer'],
            [['product_label', 'note'], 'string', 'max' => 255],
            [['quantity', 'unit_price', 'discount'], 'number'],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'id']],
            [['product_attribute_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProductAttribute::className(), 'targetAttribute' => ['product_attribute_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                   => Yii::t('app', 'ID'),
            'order_id'             => Yii::t('app', 'Order ID'),
            'product_id'           => Yii::t('app', 'Product ID'),
            'product_label'        => Yii::t('app', 'Product Label'),
            'product_attribute_id' => Yii::t('app', 'Product Attribute ID'),
            'quantity'             => Yii::t('app', 'Quantity'),
            'discount'             => Yii::t('app', 'Discount'),
            'unit_price'           => Yii::t('app', 'Unit Price'),
            'note'                 => Yii::t('app', 'Note'),
            'created_at'           => Yii::t('app', 'Created At'),
            'updated_at'           => Yii::t('app', 'Updated At'),
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
     * @return \yii\db\ActiveQuery
     */
    public function getProductAttribute()
    {
        return $this->hasOne(ProductAttribute::className(), ['id' => 'product_attribute_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /**
     * @inheritdoc
     * @return \common\models\activequery\OrderItemQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\activequery\OrderItemQuery(get_called_class());
    }

    /**
     * - Recalculate the total_price whenever the order item changed
     * @param  [type] $insert            [description]
     * @param  [type] $changedAttributes [description]
     * @return [type]                    [description]
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        // recalculate total_price field in order table
        $order_id = $this->order_id;
        $products = self::find()->
            select(['unit_price', 'quantity', 'discount'])->
            where(['order_id' => $order_id])->
            all();

        $total_price = 0;
        foreach ($products as $product) 
        {
            $total_price += $product->unit_price * $product->quantity - $product->discount;
        }

        $order = Order::findOne($order_id);

        $order->total_price = $total_price + $order->tax - $order->discount;
        if ($order->total_price < 0)
            $order->total_price = 0;
        $order->save();
    }

    /**
     * - Decrease total_price based on deleted order item
     * @return [type] [description]
     */
    public function beforeDelete()
    {
        if (parent::beforeDelete())
        {
            $order = Order::findOne($this->order_id);
            $order->total_price -= $this->unit_price * $this->quantity - $this->discount;
            $order->save();

            return TRUE;
        }
        else
            return FALSE;
    }
}
