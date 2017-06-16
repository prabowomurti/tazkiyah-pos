<?php

namespace backend\controllers;

use Yii;
use common\models\User;

use common\models\ProductAttribute;
// use common\models\search\ProductAttributeSearch as ProductAttributeSearch;
use common\models\search\ProductAttributeSearch;
use common\components\corecontrollers\ZeedController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ProductAttributeController implements the CRUD actions for ProductAttribute model.
 */
class ProductAttributeController extends ZeedController
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
                        'actions' => ['index', 'view', 'edit', 'create', 'update', 'delete', 'multipledelete', 'generator'],
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
     * Lists all ProductAttribute models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductAttributeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ProductAttribute model.
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
     * Creates a new ProductAttribute model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ProductAttribute();

        $post = Yii::$app->request->post();
        if ($model->load($post)) 
        {
            if ($model->save())
            {
                if ( ! Yii::$app->request->isAjax)
                    return $this->redirect(['view', 'id' => $model->id]);
                else // ajax request means this comes from item's update form
                {
                    $new_attributes = $post['attributes'];

                    if ($model->addAttributes($new_attributes))
                        return ; // success
                    else 
                    {
                        $model->delete();
                        throw new \yii\web\HttpException(500, 'Duplicate entry for the attribute combination');
                    }
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ProductAttribute model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $post = Yii::$app->request->post();

        // Ajax request comes from item's update form
        if (Yii::$app->request->isAjax)
        {
            if ($model->load($post) && $model->save())
                return ; //success
            else 
                throw new \yii\web\HttpException(500, $model->errors);
        }

        if ($model->load($post))
        {
            if ($model->save())
            {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } 

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Generate attributes for specific product
     * @param  integer $product_id product ID
     * @return mixed
     */
    public function actionGenerator($product_id = 0)
    {
        if (empty($product_id))
            $this->redirect(['product/index']);

        $model = \common\models\Product::findOne($product_id);

        $post = Yii::$app->request->post();
        if ( ! empty($post['product_id']))
        {
            $post_attributes = $post['attributes'];

            // grouping attributes based on the parent_id
            foreach ($post_attributes as $attribute_id => $price_impact) 
            {
                $parent_id = \common\models\Attribute::findOne($attribute_id)->parentId;
                if ( ! empty($parent_id)) // don't include the root
                    $attributes[$parent_id][] = $attribute_id;
            }

            $attributes = array_values($attributes);

            $combinations = self::createCombinations($attributes);

            foreach ($combinations as $combination)
            {
                $price_impact = 0;

                // make sure combination is an array
                if ( ! is_array($combination))
                    $combination = [$combination];

                foreach ($combination as $attribute_id)
                    $price_impact += $post_attributes[$attribute_id];

                $product_attribute             = new ProductAttribute;
                $product_attribute->product_id = $post['product_id'];
                $product_attribute->price      = $price_impact;
                
                if ($product_attribute->save())
                {
                    if ( ! $product_attribute->addAttributes($combination))
                        $product_attribute->delete();
                }
            }

            return $this->redirect(['product/update', 'id' => $product_id]);
        }

        return $this->render('generator', [
            'product_id' => $product_id,
            'model' => $model
        ]);
    }

    /**
     * Create combinations based on attributes in array
     * @param  array   $arrays array of attributes
     * @param  integer $i      counter
     * @return array          combination of array/attributes
     */
    protected static function createCombinations($arrays = [], $i = 0)
    {
        if ( ! isset($arrays[$i])) 
            return [];

        if ($i == count($arrays) - 1) 
            return $arrays[$i];

        // get combinations from subsequent arrays
        $tmp = self::createCombinations($arrays, $i + 1);

        $result = [];

        // concat each array from tmp with each element from $arrays[$i]
        foreach ($arrays[$i] as $v) 
        {
            foreach ($tmp as $t) 
            {
                $result[] = is_array($t) ? 
                    array_merge(array($v), $t) :
                    [$v, $t];
            }
        }

        return $result;

        
        // $result = array();
        // $arrays = array_values($arrays);

        // $sizeIn = sizeof($arrays);
        // $size = $sizeIn > 0 ? 1 : 0;

        // foreach ($arrays as $array)
        //     $size = $size * sizeof($array);

        // for ($i = 0; $i < $size; $i ++)
        // {
        //     $result[$i] = array();
        //     for ($j = 0; $j < $sizeIn; $j ++)
        //         array_push($result[$i], current($arrays[$j]));

        //     for ($j = ($sizeIn -1); $j >= 0; $j --)
        //     {
        //         if (next($arrays[$j]))
        //             break;
        //         elseif (isset ($arrays[$j]))
        //             reset($arrays[$j]);
        //     }
        // }
        // return $result;
        
    }

    /**
     * Deletes an existing ProductAttribute model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        if ( ! Yii::$app->request->isAjax)
            return $this->redirect(['index']);
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
     * Finds the ProductAttribute model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProductAttribute the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProductAttribute::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
