<?php 

namespace api\modules\v1\controllers;

use common\components\ZeedActiveController;

use common\models\User;
use common\models\Product;

class ProductController extends ZeedActiveController
{
    public $modelClass = 'api\modules\v1\models\Product';

    public function actions()
    {
        return ['index'];
    }

    public function verbs()
    {
        return ['index' => ['POST']];
    }

    public function actionIndex()
    {
        static::checkAccessToken();

        // return all products
        $products = \common\models\Product::find()->
            where(['visible' => 1])->
            orderBy('position')->
            all();

        $return = [];
        foreach ($products as $product) 
        {
            $attributes = [];
            foreach ($product->productAttributes as $attribute)
            {
                $attributes[] = [
                    'id'    => $attribute->id,
                    'price' => $attribute->price,
                    'label' => $attribute->attributeCombinationLabel,
                ];
            }
            $return[] = [
                'id'          => $product->id,
                'label'       => $product->label,
                'description' => $product->description,
                'price'       => $product->price,
                'position'    => $product->position,
                'attributes'  => $attributes
            ];
        }

        return ['status' => 200, 'products' => $return];
    }

    
}