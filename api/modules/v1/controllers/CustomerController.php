<?php 

namespace api\modules\v1\controllers;

use common\components\ZeedActiveController;

use common\models\User;
use common\models\Customer;

class CustomerController extends ZeedActiveController
{
    public $modelClass = 'api\modules\v1\models\Customer';

    public function actions()
    {
        return ['index', 'create'];
    }

    public function verbs()
    {
        return ['index' => ['POST'], 'create' => ['POST']];
    }

    public function actionIndex()
    {
        static::checkAccessToken();

        // return all products
        $customers = Customer::find()->
            orderBy('username')->
            all();

        foreach ($customers as $customer)
        {
            $return[] = [
                'id' => $customer->id,
                'username' => $customer->username,
                'phone_number' => substr_replace($customer->phone, 'XXX', -3)
            ];
        }

        return ['status' => 200, 'customers' => $return];
    }

    public function actionCreate()
    {
        static::checkAccessToken();

        $params = \Yii::$app->request->post();
        if (empty($params['username']) || empty($params['phone_number']))
            static::missingParameter('Name and phone number should not be empty');

        if (! empty($params['gender']) && ! in_array($params['gender'], ['male', 'female']))
        {
            static::missingParameter('Gender is not valid');
        }

        $address = $params['address'];
        
        $customer = new Customer;
        $customer->username = $params['username'];
        $customer->phone = $params['phone_number'];
        $customer->address = $address;
        $customer->gender = $params['gender'];

        if ( ! $customer->save())
            static::exception('Can not save customer');

        return [
            'status' => 200,
            'message' => 'Customer saved', 
            'customer' => [
                'id'           => $customer->id,
                'username'     => $customer->username,
                'phone_number' => $customer->phone,
                'address'      => $customer->address,
                'gender'       => $customer->gender,
            ]
        ];
    }
}