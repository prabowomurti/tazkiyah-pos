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
            [['product_id', 'created_at', 'updated_at'], 'integer'],
            [['price'], 'number'],
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
    public function getTheAttributes() // can not use the reserved function "getAttributes()"
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
     * Add attribute combination to attribute_combination table
     * @param array $new_attributes new attribute IDs
     * @return boolean success or not
     */
    public function addAttributes($new_attributes = [])
    {
        if ( ! is_array($new_attributes))
            $new_attributes = [$new_attributes];

        if (self::doesAttributeCombinationExist($this->product_id, $new_attributes))
            return FALSE;

        foreach ($new_attributes as $attribute_id)
        {
            if (empty($attribute_id)) continue;

            $attribute = Attribute::findOne($attribute_id);
            $this->link('theAttributes', $attribute);
        }

        return TRUE;
    }

    /**
     * Checking if the combination of attributes already exist for the product
     * @param integer $product_id product's ID
     * @param  array  $attributes new attribute combination
     * @return boolean 
     */
    public static function doesAttributeCombinationExist($product_id = 0, $attributes = [])
    {
        $query = self::find()->
            select(['product_attribute_id', 'counter_attribute' => 'COUNT(DISTINCT attribute_id)'])->
            innerJoin('attribute_combination ac', 'ac.product_attribute_id = ' . self::tableName() . '.id')->
            where(['product_id' => (int) $product_id])->
            groupBy('product_attribute_id')->
            having(['counter_attribute' => count($attributes)])->
            andHaving('SUM(attribute_id NOT IN (' . implode(',', $attributes) . ')) = 0');

        return (boolean) $query->scalar();
    }

    /**
     * Get attribute combination labels, for example : 
     * Item A has 2 kinds of attributes : Size and Color
     * Size : S, M, L; Color : Red, Green
     * So the combination labels will be 
     * "S, Red", 
     * "S, Green", 
     * "M, Red", and so on
     * 
     * @return string the labels concatenated by ', '
     */
    public function getAttributeCombinationLabel()
    {
        $attribute_combination = $this->getTheAttributes()->select('label')->orderBy('position')->column();

        return implode(', ', $attribute_combination);
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
