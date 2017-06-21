<?php

namespace common\components\corecontrollers;

use Yii;
use yii\web\Controller;
use common\models\User;
use yii\web\NotFoundHttpException;

/**
 * Simple and sweet class for our Controller
 * 
 * @author Prabowo Murti <prabowo.murti@gmail.com>
 */
class ZeedController extends Controller
{

    /**
     * Delete multiplede IDs
     * @return mixed
     */
    public function actionMultipledelete()
    {
        if (User::role() != User::ROLE_SUPERADMIN && User::role() != User::ROLE_ADMIN)
            throw new NotFoundHttpException('The requested page does not exist.');

        if (Yii::$app->request->isAjax)
        {
            $selected_ids = Yii::$app->request->post('selectedItems');
            foreach ($selected_ids as $id)
                $this->findModel($id)->delete();
        }
    }

    /**
     * Sort model's positions
     * @return mixed
     */
    public function actionSort()
    {
        if (User::role() != User::ROLE_SUPERADMIN && User::role() != User::ROLE_ADMIN)
            throw new NotFoundHttpException('The requested page does not exist.');

        // getting the model's class name, based on the controller name
        $model_name = ucwords(str_replace('-', ' ', $this->id));
        $model = '\common\models\\' . str_replace(' ', '', $model_name);

        if (Yii::$app->request->isAjax)
        {
            $sorted_ids = Yii::$app->request->post('position');
            if ( ! empty($sorted_ids))
                $model::updatePosition($sorted_ids);
            
            return ;
        }

        $rows = $model::find()->
            orderBy('position')->
            all();

        $open_tag = '<td>';
        $end_tag  = '</td>';
        $items = [];
        foreach ($rows as $key => $item)
        {
            $items[$key]['content'] = '<td style="width:28px"><span class="fa fa-arrows"></span></td><td style="width: 64px">' . $item['id'] . $end_tag . $open_tag . $item['label'] . $end_tag;
            $items[$key]['id']      = $item['id'];
        }

        return $this->render('//layouts/sort', ['items' => $items, 'model_name' => \yii\helpers\Inflector::pluralize($model_name)]);
    }
}