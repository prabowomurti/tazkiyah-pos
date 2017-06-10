<?php

namespace common\models\activequery;

/**
 * This is the ActiveQuery class for [[\common\models\Employee]].
 *
 * @see \common\models\Employee
 */
class EmployeeQuery extends \common\components\coremodels\ZeedActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \common\models\Employee[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\Employee|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
