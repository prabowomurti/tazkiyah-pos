<?php

/**
 * Custom sortable inside an HTML table
 * 
 * @author Prabowo Murti <prabowo.murti@gmail.com>
 */

namespace common\components\widgets;

use yii\base\InvalidConfigException;
use yii\jui\Sortable;
use yii\helpers\Html;

class ZeedSortable extends Sortable
{

    public function run()
    {
        $options = $this->options;
        echo '<div class="table-responsive">';
        echo '<table class="table table-hover table-striped" id="sortable-table">' . "\n";
        echo '<thead><tr><th style="border-bottom:none !important"></th><th style="border-bottom: none !important">ID</th><th style="border-bottom: none !important">Label</th></tr></thead>' . "\n";
        echo Html::beginTag('tbody', $options) . "\n";
        echo $this->renderItems() . "\n";
        echo Html::endTag('tbody') . "\n";
        echo '</table>' . "\n";
        echo '</div>';
        $this->registerWidget('sortable');
    }

    public function renderItems()
    {
        $items = [];
        foreach ($this->items as $item) {
            $tag = 'tr';
            $items[] = Html::tag($tag, $item['content'], ['style' => 'cursor:move', 'id' => 'position_' . $item['id']]);
        }

        return implode("\n", $items);
    }


}