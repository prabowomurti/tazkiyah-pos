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
            // preparing the attribute groups
            $attribute_groups = [];
            foreach ($product->getAttributeGroups() as $key => $group)
            {
                if (isset($attribute_groups[$group['parent_id']]))
                {
                    $attribute_groups[$group['parent_id']]['children'][] = ['id' => $group['child_id'], 'child_label' => $group['child_label']];
                }
                else 
                {
                    $attribute_groups[$group['parent_id']] = ['id' => $group['parent_id'], 'parent_label' => $group['parent_label']];
                    $attribute_groups[$group['parent_id']]['children'][] = ['id' => $group['child_id'], 'child_label' => $group['child_label']];
                }
            }

            // remove the index
            $attribute_groups = array_values($attribute_groups);

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
                'attribute_groups' => $attribute_groups,
                'attributes'       => $attributes,
            ];
        }

        return ['status' => 200, 'products' => $return];
    }

    
}