<?php 

namespace api\modules\v1\controllers;

use common\components\ZeedActiveController;

use common\models\User;
use common\models\Customer;
use common\models\Order;
use common\models\Product;
use common\models\ProductAttribute;
use common\models\OrderItem;
use common\models\Outlet;

class OrderController extends ZeedActiveController
{
    public $modelClass = 'api\modules\v1\models\Order';

    public function actions()
    {
        return ['index', 'create', 'update'];
    }

    public function verbs()
    {
        return ['index' => ['POST'], 'create' => ['POST'], 'update' => ['POST', 'PUT']];
    }

    public function actionIndex()
    {
        static::checkAccessToken();

        // return all products
        $orders = Order::find()->
            orderBy('created_at')->
            all();

        $return = [];
        foreach ($orders as $order)
        {
            $items = [];
            foreach ($order->orderItems as $order_item)
            {
                $items[] = [
                    'id'                   => $order_item->id,
                    'product_id'           => $order_item->product_id,
                    'product_label'        => $order_item->product_label,
                    'product_attribute_id' => $order_item->product_attribute_id,
                    'attribute_label'      => (empty($order_item->productAttribute) ? '' : $order_item->productAttribute->attributeCombinationLabel),
                    'quantity'             => $order_item->quantity,
                    'discount'             => $order_item->discount,
                    'unit_price'           => $order_item->unit_price,
                    'note'                 => $order_item->note,
                ];
            }
            $return[] = [
                'id'          => $order->id,
                'outlet_id'   => $order->outlet_id,
                'customer'    => ['id' => $order->customer_id, 'username' => $order->customer ? $order->customer->username : ''],
                'items'       => $items,
                'code'        => $order->code,
                'tax'         => $order->tax,
                'discount'    => $order->discount,
                'total_price' => $order->total_price,
                'status'      => $order->status,
                'note'        => $order->note,
                'created_at'  => $order->created_at,
                'updated_at'  => $order->updated_at,
            ];
        }

        return ['status' => 200, 'orders' => $return];
    }

    public function actionCreate()
    {
        static::checkAccessToken();

        $params = \Yii::$app->request->post();

        if (empty($params['products']) || 
            empty($params['product_quantities']) ||
            (! isset($params['product_attributes'])) ||
            empty($params['outlet_id'])
            )
            static::missingParameter('Missing parameter : products, attributes, quantities, or outlet ID');

        $product_ids           = explode(',', $params['products']);
        $product_attribute_ids = explode(',', $params['product_attributes']);
        $product_quantities    = explode(',', $params['product_quantities']);

        if (count($product_ids) != count($product_attribute_ids) || 
            count($product_attribute_ids) != count($product_quantities))
        {
            static::exception('Wrong count of parameters. Please make sure to match count of products, attributes, and quantities');
        }

        if (isset($params['product_discounts']) && ! empty($params['product_discounts']))
        {
            $product_discounts = explode(',', $params['product_discounts']);
            if (count($product_discounts) != count($product_ids))
                static::exception('Wrong count of parameters. Please make sure to match count of product discounts and products');
        }

        if (isset($params['product_notes']) && ! empty($params['product_notes']))
        {
            $product_notes = json_decode($params['product_notes']);

            if (count($product_notes) != count($product_ids))
                static::exception('Wrong count of parameters. Please make sure to match count of product notes and products');
        }

        // check outlet
        $outlet = Outlet::findOne($params['outlet_id']);
        if (empty($outlet))
        {
            static::exception('Outlet does not exist');
        }

        // check customer 
        $customer_id = null;
        if ( ! empty($params['customer_id']))
        {
            $customer = Customer::findOne($params['customer_id']);
            if ($customer)
                $customer_id = $customer->id;
        }

        $order_note = empty($params['note']) ? '' : $params['note'];
        $order_discount = empty($params['discount']) ? 0.00 : (float) $params['discount'];
        $order_tax = empty($params['tax']) ? 0.00 : (float) $params['tax'];

        // save the order first
        $order              = new Order;
        $order->customer_id = $customer_id;
        $order->tax         = $order_tax;
        $order->discount    = $order_discount;
        $order->note        = $order_note;
        $order->outlet_id   = $outlet->id;
        $order->status      = $params['status'];
        
        $order->save();

        $order_price = 0.00;

        // validating products and attributes
        foreach ($product_ids as $key => $product_id)
        {
            $order_item = new OrderItem;

            $product = Product::findOne((int) $product_id);
            if (empty($product))
            {
                $order->delete();
                return static::exception('Product with ID : ' . $product_id . ' does not exist');
            }
            $order_item->product_id = $product->id;

            if ( ! empty($product_attribute_ids[$key]))
            {
                $product_attribute = ProductAttribute::findOne(['id' => (int) $product_attribute_ids[$key], 'product_id' => $product_id]);
                if (empty($product_attribute))
                {
                    $order->delete();
                    return static::exception('Product Attribute with ID : ' . $product_attribute_ids[$key] . ' does not exist');
                }

                $order_item->product_attribute_id = $product_attribute->id;
            }
            else 
            {
                if ($product->hasProductAttributes())
                {
                    $order->delete();
                    return static::exception($product->label . ' should have an attribute');
                }

                $order_item->product_attribute_id = null;
            }

            // define product's label, if it has attributes, concat the attribute combination label
            $order_item->product_label = $order_item->product_attribute_id ? $product->label . ' - ' . $product_attribute->getAttributeCombinationLabel() : $product->label;

            $order_item->quantity   = $product_quantities[$key];
            $order_item->discount   = $product_discounts[$key];
            $order_item->unit_price = $product->price + ($order_item->productAttribute ? $order_item->productAttribute->price : 0);
            $order_item->note       = isset($product_notes[$key]) ? $product_notes[$key] : '';
            $order_item->order_id   = $order->id;

            if ( ! $order_item->save())
            {
                $order->delete();
                return static::exception('Can not save order item');
            }

        }

        return [
            'status' => 200,
            'message' => 'Order saved',
            'order' => Order::findOne($order->id),
            'items' => OrderItem::findAll(['order_id' => $order->id]),
        ];

    }
}