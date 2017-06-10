<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product_attribute".
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $price
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property AttributeCombination[] $attributeCombinations
 * @property Attribute[] $attributes
 * @property OrderItem[] $orderItems
 * @property Product $product
 */
class ProductAttribute extends \common\components\coremodels\ZeedActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_attribute';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'price', 'created_at', 'updated_at'], 'integer'],
            [['created_at', 'updated_at'], 'required'],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'product_id' => Yii::t('app', 'Product ID'),
            'price' => Yii::t('app', 'Price'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttributeCombinations()
    {
        return $this->hasMany(AttributeCombination::className(), ['product_attribute_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttributes()
    {
        return $this->hasMany(Attribute::className(), ['id' => 'attribute_id'])->viaTable('attribute_combination', ['product_attribute_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(OrderItem::className(), ['product_attribute_id' => 'id']);
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
     * @return \common\models\activequery\ProductAttributeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\activequery\ProductAttributeQuery(get_called_class());
    }
}
