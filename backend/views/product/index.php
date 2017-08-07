<?php

use yii\helpers\Html;
use common\components\widgets\GridView;
use yii\widgets\Pjax;

use common\models\Product;
/* @var $this yii\web\View */
/* @var $searchModel common\models\search\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Products');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Product'), ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('app', 'Sort'), ['sort'], ['class' => 'btn btn-default']) ?>

        <span class="pull-right btn btn-danger" id="delete_selected_items_btn" data-url="/product/multipledelete">Delete Selected</span>
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
            'label',
            'barcode',
            'description',
            'price',
            [
                'attribute' => 'visible',
                'filter' => Product::getVisibleAsList(),
                'value' => function ($model) {return empty($model->visible) ? 'Hidden' : 'Visible';}
            ],
            // 'position',
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
    </div>
</div>

<?= $this->registerJsFile('@web/js/multipledelete.js', ['depends' => [\yii\web\JqueryAsset::className()]]); ?>