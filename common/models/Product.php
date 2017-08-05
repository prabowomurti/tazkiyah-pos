<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product".
 *
 * @property integer $id
 * @property string $label
 * @property string $description
 * @property integer $price
 * @property integer $visible
 * @property integer $position
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property OrderItem[] $orderItems
 * @property ProductAttribute[] $productAttributes
 */
class Product extends \common\components\coremodels\ZeedActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['label'], 'required'],
            [['visible', 'position', 'created_at', 'updated_at'], 'integer'],
            [['position'], 'default', 'value' => 0],
            [['price'], 'number', 'min' => '0.00'],
            [['price'], 'filter', 'filter' => 'doubleval'],
            [['label', 'description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'label' => Yii::t('app', 'Label'),
            'description' => Yii::t('app', 'Description'),
            'price' => Yii::t('app', 'Price'),
            'visible' => Yii::t('app', 'Visible'),
            'position' => Yii::t('app', 'Position'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(OrderItem::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductAttributes()
    {
        return $this->hasMany(ProductAttribute::className(), ['product_id' => 'id']);
    }

    /**
     * Check if product has attributes
     * @return boolean
     */
    public function hasProductAttributes()
    {
        return (bool) $this->productAttributes;
    }

    /**
     * @inheritdoc
     * @return \common\models\activequery\ProductQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\activequery\ProductQuery(get_called_class());
    }

    /**
     * Get all product as list / used in dropdownlist
     * @return [type] [description]
     */
    public static function getAllAsList()
    {
        $products = self::find()->
            select(['label', 'id'])->
            where(['visible' => 1])->
            orderBy('position')->
            indexBy('id')->
            column();

        return $products;

    }
}
