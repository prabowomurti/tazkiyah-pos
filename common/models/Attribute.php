<?php

namespace common\models;

use Yii;

use creocoder\nestedsets\NestedSetsBehavior;

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
            [['label'], 'required'],
            [['tree', 'lft', 'rgt', 'depth', 'position'], 'integer'],
            [['position'], 'default', 'value' => 0],
            [['label'], 'string', 'max' => 255],
        ];
    }

    public function behaviors() {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'tree',
                // 'leftAttribute' => 'lft',
                // 'rightAttribute' => 'rgt',
                // 'depthAttribute' => 'depth',
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    /**
     * Get all tree as list, except the node itself and all the children
     * @param integer $exception_id not include the ID
     * @return array array of the tree
     */
    public static function getTree($exception_id = 0)
    {
        // don't include the node and the children
        $children = [];
        if ( ! empty($exception_id))
            $children = array_merge(
                self::findOne($exception_id)->children()->column(),
                [$exception_id]);

        // get full tree
        $rows = self::find()->
            select('id, label, depth')->
            orderBy('tree, lft')->
            where(['NOT IN', 'id', $children])->
            all();

        $return = [];
        foreach ($rows as $row)
            $return[$row->id] = str_repeat('-', $row->depth) . ' ' . $row->label;

        return $return;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'       => Yii::t('app', 'ID'),
            'label'    => Yii::t('app', 'Label'),
            'tree'     => Yii::t('app', 'Tree'),
            'lft'      => Yii::t('app', 'Lft'),
            'rgt'      => Yii::t('app', 'Rgt'),
            'depth'    => Yii::t('app', 'Depth'),
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

    /**
     * Get parent's ID 
     * @return integer ID of parent node
     */
    public function getParentId()
    {
        $parent = $this->getParent();
        return $parent ? $parent->id : NULL ;
    }

    /**
     * Get parent of node
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->parents(1)->one();
    }
    
}
