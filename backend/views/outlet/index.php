<?php

use yii\helpers\Html;
use common\components\widgets\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\search\OutletSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Outlets');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="outlet-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Outlet'), ['create'], ['class' => 'btn btn-success']) ?>

        <span class="pull-right btn btn-danger" id="delete_selected_items_btn" data-url="/outlet/multipledelete">Delete Selected</span>
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

            [
                'attribute' => 'id',
                'options' => ['width' => '70px'],
            ],
            'label',
            'address:ntext',
            // 'phone',
            // 'status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
    </div>
</div>

<?= $this->registerJsFile('@web/js/multipledelete.js', ['depends' => [\yii\web\JqueryAsset::className()]]); ?>