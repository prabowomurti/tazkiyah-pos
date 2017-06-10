<?php

namespace common\models\activequery;

/**
 * This is the ActiveQuery class for [[\common\models\Order]].
 *
 * @see \common\models\Order
 */
class OrderQuery extends \common\components\coremodels\ZeedActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \common\models\Order[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\Order|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
