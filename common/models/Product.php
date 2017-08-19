<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product".
 *
 * @property integer $id
 * @property string $label
 * @property string $barcode
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
            [['label', 'barcode', 'description'], 'string', 'max' => 255],
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
            'barcode' => Yii::t('app', 'Barcode'),
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

    /**
     * Get attribute groups as array
     * @return [type] [description]
     */
    public function getAttributeGroups()
    {
        /**
         * The raw SQL looks like this : 
         * SELECT 
                `parent`.`id` AS `parent_id`,
                `parent`.`label` AS `parent_label`,
                children.id AS children_id,
                children.label AS children_label

            FROM `attribute` AS children
            INNER JOIN `attribute` `parent` ON parent.id = children.tree
            INNER JOIN attribute_combination AS ac ON ac.`attribute_id` = children.id
            INNER JOIN product_attribute AS pa ON pa.`id` = ac.`product_attribute_id`
            WHERE product_id = {PRODUCT_ID}
            GROUP BY `children`.`id`
            ORDER BY parent.position, children.position;
         */
        $product_id = $this->id;
        $query = self::find()->
            select('
                `parent`.`id` AS `parent_id`,
                `parent`.`label` AS `parent_label`,
                `child`.id AS `child_id`,
                `child`.`label` AS `child_label`')->
            from(Attribute::tableName() . ' AS child')->
            innerJoin(Attribute::tableName() . ' AS parent', 'parent.id = child.tree')->
            innerJoin('attribute_combination AS ac', 'ac.attribute_id = child.id')->
            innerJoin(ProductAttribute::tableName() . ' AS pa', 'pa.id = ac.product_attribute_id')->
            where(['pa.product_id' => $product_id])->
            groupBy('child.id')->
            orderBy('parent.position, child.position');

        return $query->asArray()->all();


    }
}
