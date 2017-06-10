<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "attribute".
 *
 * @property integer $id
 * @property string $label
 * @property integer $tree
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property integer $position
 *
 * @property AttributeCombination[] $attributeCombinations
 * @property ProductAttribute[] $productAttributes
 */
class Attribute extends \common\components\coremodels\ZeedActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'attribute';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['label', 'lft', 'rgt', 'depth'], 'required'],
            [['tree', 'lft', 'rgt', 'depth', 'position'], 'integer'],
            [['label'], 'string', 'max' => 255],
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
            'tree' => Yii::t('app', 'Tree'),
            'lft' => Yii::t('app', 'Lft'),
            'rgt' => Yii::t('app', 'Rgt'),
            'depth' => Yii::t('app', 'Depth'),
            'position' => Yii::t('app', 'Position'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttributeCombinations()
    {
        return $this->hasMany(AttributeCombination::className(), ['attribute_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductAttributes()
    {
        return $this->hasMany(ProductAttribute::className(), ['id' => 'product_attribute_id'])->viaTable('attribute_combination', ['attribute_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return \common\models\activequery\AttributeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\activequery\AttributeQuery(get_called_class());
    }
}
