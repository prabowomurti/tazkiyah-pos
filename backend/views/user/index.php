<?php

use yii\helpers\Html;
use common\components\widgets\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create User'), ['create'], ['class' => 'btn btn-success']) ?>

        <span class="pull-right btn btn-danger" id="delete_selected_items_btn" data-url="<?=Yii::$app->urlManager->createUrl(Yii::$app->controller->id . '/multipledelete');?>">Delete Selected</span>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class'   => 'common\components\widgets\ZeedCheckboxColumn',
            ],
            [
                'attribute' => 'id',
                'options'   => ['width' => '70px']
            ],
            'username',
            // 'auth_key',
            // 'password_hash',
            // 'password_reset_token',
            'email:email',
            [
                'attribute' => 'role',
                'filter' => \common\models\User::getRoleAsArray(), 
                'value' => function ($model)
                {
                    $role_as_array = \common\models\User::getRoleAsArray();
                    return $role_as_array[$model->role];
                }
            ],
            // 'status',
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>

<?= $this->registerJsFile('@web/js/multipledelete.js', ['depends' => [\yii\web\JqueryAsset::className()]]); ?>
