<?php

namespace common\components\coremodels;

use Yii;

/**
 * Simple and sweet class for our Active Record
 * 
 * @author Prabowo Murti <prabowo.murti@gmail.com>
 */

class ZeedActiveRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            \yii\behaviors\TimestampBehavior::className(),
        ];
    }

    /**
     * Get 'visible' possible values for dropdownlist
     * @return array array of possible values
     */
    public static function getVisibleAsList()
    {
        return ['Hidden', 'Visible'];
    }

    /**
     * Get model's label as list, used in dropdownlist
     * @param  array  $where conditional where
     * @param  string $orderBy order by field
     * @return array array of (id, label)
     */
    public static function getLabelAsList($where = [], $orderBy = 'label')
    {
        return self::find()->
            select('label, id')->
            where($where)->
            orderBy($orderBy)->
            indexBy('id')->
            column();
    }

    /**
     * Update position order of models
     * @param  array  $data array of model IDs
     * @return boolean always true
     */
    public static function updatePosition($data = [])
    {
        foreach ($data as $position => $id)
        {
            $item = self::findOne($id);
            $item->position = $position;
            $item->save();
        }

        return true;
    }
}