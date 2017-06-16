<?php

namespace common\models\activequery;

/**
 * This is the ActiveQuery class for [[\common\models\OrderItem]].
 *
 * @see \common\models\OrderItem
 */
class OrderItemQuery extends \common\components\coremodels\ZeedActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \common\models\OrderItem[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\OrderItem|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}