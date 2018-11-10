<?php

use yii\helpers\Html;
use common\components\widgets\GridView;
use yii\widgets\Pjax;

use kartik\date\DatePicker;

use common\models\Order;
/* @var $this yii\web\View */
/* @var $searchModel common\models\search\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Orders');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Order'), ['create'], ['class' => 'btn btn-success']) ?>

        <span class="pull-right btn btn-danger" id="delete_selected_items_btn" data-url="/order/multipledelete">Delete Selected</span>
    </p>
    <div class="table table-responsive">
    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class'   => 'common\components\widgets\ZeedCheckboxColumn',
            ],
            'customer.username:text:Customer',
            [
                'attribute' => 'outlet_id',
                'filter' => \common\models\Outlet::getAllAslist(),
                'label' => 'Outlet',
                'value' => function ($model) {return @$model->outlet->label;}
            ],
            // 'total_price',
            [
                'attribute' => 'status',
                'filter' => Order::getStatusAsList()
            ],
            [
                'attribute' => 'delivery_time',
                'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'delivery_time',
                    'pickerButton' => false,
                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                    'pluginOptions' => [
                        'autoclose'=>true,
                        'format' => 'yyyy-mm-dd',
                    ],
                ]),
                'format' => 'raw',
                // 'filterInputOptions' => ['style' => 'padding-bottom: 0px']
            ],
            [
                'attribute' => 'created_at',
                'value' => function ($model) { return date('Y-m-d H:i:s', $model->created_at);},
                'filter' => ''
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
    </div>
</div>

<?= $this->registerJsFile('@web/js/multipledelete.js', ['depends' => [\yii\web\JqueryAsset::className()]]); ?>