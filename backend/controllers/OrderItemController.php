<?php

namespace backend\controllers;

use Yii;
use common\models\User;
use common\models\Product;
use common\models\Order;

use common\models\OrderItem;
// use common\models\search\OrderItemSearch as OrderItemSearch;
use common\models\search\OrderItemSearch;
use common\components\corecontrollers\ZeedController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * OrderItemController implements the CRUD actions for OrderItem model.
 */
class OrderItemController extends ZeedController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'edit', 'create', 'update', 'delete', 'multipledelete'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action)
                        {
                            return 
                                Yii::$app->user->identity['role'] == User::ROLE_SUPERADMIN || 
                                Yii::$app->user->identity['role'] == User::ROLE_ADMIN;
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all OrderItem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrderItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrderItem model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new OrderItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new OrderItem();

        if (Yii::$app->request->post()) 
        {
            $post = Yii::$app->request->post('OrderItem');

            $model->order_id             = $post['order_id'];
            $model->product_id           = $post['product_id'];
            $model->product_attribute_id = ((int) $post['product_attribute_id'] ? (int) $post['product_attribute_id'] : null);
            $model->quantity             = (double) $post['quantity'];
            $model->discount             = (double) $post['discount'];
            $model->note                 = $post['note'];
            $product                     = Product::findOne($model->product_id);
            $model->product_label        = $product->label;
            $model->unit_price           = $product->price + ($model->productAttribute ? $model->productAttribute->price : 0);

            if ($model->save())
            {
                if ( ! Yii::$app->request->isAjax)
                    return $this->redirect(['view', 'id' => $model->id]);
                else 
                    return $model->order->total_price;
            }
            else 
                return $model->errors[0];

        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing OrderItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->isAjax)
        {
            $post = Yii::$app->request->post('OrderItem');
            $model->quantity = (double) $post['quantity'];

            if ($model->save())
                return $model->order->total_price;
            else 
                return array_values($model->errors)[0][0];
        }
        else 
        {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Deletes an existing OrderItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $order_id = $model->order_id;
        $model->delete();

        if ( ! Yii::$app->request->isAjax)
            return $this->redirect(['index']);
        else 
            return Order::findOne($order_id)->total_price;
    }

    /**
     * Delete multiple IDs
     * @return mixed
     */
    public function actionMultipledelete()
    {
        if (Yii::$app->request->isAjax)
        {
            $selected_ids = Yii::$app->request->post('selectedItems');
            foreach ($selected_ids as $id)
                $this->findModel($id)->delete();
        }
    }

    /**
     * Finds the OrderItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OrderItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OrderItem::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
