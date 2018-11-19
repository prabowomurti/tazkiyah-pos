<?php

namespace backend\controllers;

use Yii;
use common\models\User;

use common\models\Customer;
use common\models\Employee;
use common\models\Product;
use common\models\ProductAttribute;
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
                        'actions' => ['index', 'add-order', 'add-customer'],
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
            limit(Product::MAX_PRODUCTS_SHOWN)->
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

    /**
     * Add order from ajax call
     * @return mixed
     */
    public function actionAddOrder()
    {
        if ( ! Yii::$app->request->isAjax)
            throw new NotFoundHttpException('Action should be called in AJAX.');

        $post = Yii::$app->request->post();
        $post_order = $post['Order'];
        $post_items = $post['Items'];

        $order           = new Order();
        $order->tax      = $post_order['tax'];
        $order->discount = $post_order['discount'];
        $order->status   = Order::STATUS_DONE;
        $order->note     = $post_order['note'];
        $order->customer_id = $post_order['customer_id'];

        if ( ! $order->save())
            throw new NotFoundHttpException('Can not save order. Please check the error log.');

        foreach ($post_items as $key => $item)
        {

            $order_item = new OrderItem();
            $order_item->order_id = $order->id;
            $order_item->product_id = $item['id'];
            $product_attribute_id = (int) $item['product_attribute_id'];
            if ( ! empty($product_attribute_id))
            {
                // check for valid attribute_id
                if ( ! ProductAttribute::isValidAttributeID($order_item->product_id, $product_attribute_id))
                {
                    throw new NotFoundHttpException('Can not add attribute ID : ' . $product_attribute_id . ', to : ' . $cached_product->label);
                }

                $order_item->product_attribute_id = $product_attribute_id;
            }
            $order_item->quantity = (double) $item['quantity'];
            $order_item->discount = (double) $item['discount'];
            $order_item->note = $item['note'];

            $cached_product = Product::findOne($order_item->product_id);
            // also cached the product (and attribute combination (eg : Roti Maryam - Extra, Isi Daging Sapi) if any)
            $order_item->product_label = $cached_product->label . (
                $order_item->product_attribute_id ? 
                    ' - ' . ProductAttribute::findOne($order_item->product_attribute_id)->getAttributeCombinationLabel() :
                    '');
            $order_item->unit_price = $cached_product->price + ($order_item->productAttribute ? $order_item->productAttribute->price : 0);

            if ( ! $order_item->save())
                throw new NotFoundHttpException('Can not save order item : ' . $order_item->product_label);
        }

        return $order->id;

    }
}
