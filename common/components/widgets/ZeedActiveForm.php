<?php 

namespace common\components\widgets;

class ZeedActiveForm extends \yii\bootstrap\ActiveForm
{
    public function init()
    {
        $this->layout = 'horizontal';
        $this->fieldConfig = [
            'horizontalCssClasses' => [
                'label' => 'control-label col-md-3 col-sm-3 col-xs-12',
                'wrapper' => 'col-sm-6 col-md-7 col-xs-12'
            ]
        ];

        parent::init();
    }
}