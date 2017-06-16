<?php

use yii\helpers\Html;
use common\components\widgets\GridView;
use yii\widgets\Pjax;

use common\models\Attribute;
/* @var $this yii\web\View */
/* @var $searchModel common\models\search\AttributeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Attributes');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="attribute-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Attribute'), ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('app', 'Sort'), ['sort'], ['class' => 'btn btn-default']) ?>

        <span class="pull-right btn btn-danger" id="delete_selected_items_btn" data-url="/attribute/multipledelete">Delete Selected</span>
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
            [
                'attribute' => 'tree',
                'label'     => 'Root',
                'filter'    => Attribute::find()->roots()->select('label, id')->indexBy('id')->column(),
                'value'     => function ($model)
                    {
                        if ( ! $model->isRoot())
                            return $model->parents()->one()->label;
                        return 'No Parent';
                    }
            ],
            'parent.label',
            // 'depth',
            // 'position',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
    </div>
</div>

<?= $this->registerJsFile('@web/js/multipledelete.js', ['depends' => [\yii\web\JqueryAsset::className()]]); ?>