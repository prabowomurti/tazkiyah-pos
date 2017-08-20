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
            // preparing attributes
            $attributes = [];
            foreach ($product->productAttributes as $attribute)
            {
                $attributes[] = [
                    'id'    => $attribute->id,
                    'combination_ids' => $attribute->attributeCombinationIDs,
                    'price' => $attribute->price,
                    'label' => $attribute->attributeCombinationLabel,
                ];
            }

            $return[] = [
                'id'               => $product->id,
                'label'            => $product->label,
                'barcode'          => $product->barcode,
                'description'      => $product->description,
                'price'            => $product->price,
                'position'         => $product->position,
                'attribute_groups' => $product->getAttributeGroups(),
                'attributes'       => $attributes,
            ];
        }

        return ['status' => 200, 'products' => $return];
    }

    
}