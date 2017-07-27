<?php

namespace backend\controllers;

use Yii;
use common\models\User;

use common\models\Customer;
use common\models\Employee;
use common\models\Product;
use common\models\Order;
use common\models\OrderLog;
use common\models\OrderItem;
use common\components\corecontrollers\ZeedController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * OperatorController
 */
class OperatorController extends ZeedController
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
                        'actions' => ['index', 'submit-order', 'add-customer'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action)
                        {
                            return 
                                Yii::$app->user->identity['role'] == User::ROLE_SUPERADMIN ||
                                Yii::$app->user->identity['role'] == User::ROLE_OPERATOR;
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
     * Lists all Customer models.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->layout = 'operator';

        $products = Product::find()->
            where(['visible' => '1'])->
            orderBy('position')->
            limit(12)->
            all();

        $all_products = Product::find()->
            where(['visible' => 1])->
            orderBy('label')->
            all();

        $products_as_array = Product::find()->
            where(['visible' => 1])->
            select('label, id')->
            orderBy('position')->
            indexBy('id')->
            column();

        $customers_as_array = Customer::getAllAsList();
        
        return $this->render('index', [
            'products' => $products,
            'all_products' => $all_products,
            'products_as_array' => $products_as_array,
            'customers_as_array' => $customers_as_array
            ]);
    }

    public function actionAddCustomer()
    {
        $model = new Customer();

        if (Yii::$app->request->post()) 
        {
            $post = Yii::$app->request->post('Customer');

            $model->username = $post['username'];
            $model->phone    = $post['phone_number'];
            $model->gender   = $post['gender'];

            if ($model->save())
            {
                if ( ! Yii::$app->request->isAjax)
                    return $this->redirect(['view', 'id' => $model->id]);
                else 
                {
                    $return['id'] = $model->id;
                    $phone = (! empty($model->phone) ? ' (' . substr_replace($model->phone, 'XXXX', -4) . ')' : '');
                    $return['label'] = $model->username . $phone;
                    return json_encode($return);
                }
            }
            else 
                return $model->errors[0];

        }
    }
}
