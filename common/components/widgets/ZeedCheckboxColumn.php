<?php

namespace common\components\widgets;

/**
 * Extends CheckboxColumn functionalities
 * 
 * @author Prabowo Murti <prabowo.murti@gmail.com>
 */
class ZeedCheckboxColumn extends \yii\grid\CheckboxColumn
{
    public function init()
    {
        parent::init();

        // makes <td> area checkable
        $this->options['width'] = '30px';
        $this->checkboxOptions['onclick'] = 'var event = arguments[0] || window.event; event.stopPropagation();';
        $this->checkboxOptions['style']   = 'cursor:pointer';
        $this->contentOptions['class']    = 'td_checkbox';
        $this->contentOptions['style']    = 'cursor:pointer';
        $this->contentOptions['onclick']  = 'this.getElementsByTagName("input")[0].checked = ! this.getElementsByTagName("input")[0].checked;';
    }
}