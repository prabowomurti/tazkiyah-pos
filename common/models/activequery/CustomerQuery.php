<?php

namespace common\models\activequery;

/**
 * This is the ActiveQuery class for [[\common\models\Customer]].
 *
 * @see \common\models\Customer
 */
class CustomerQuery extends \common\components\coremodels\ZeedActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \common\models\Customer[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\Customer|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
