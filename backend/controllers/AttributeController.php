<?php

namespace backend\controllers;

use Yii;
use common\models\User;

use common\models\Attribute;
// use common\models\search\AttributeSearch as AttributeSearch;
use common\models\search\AttributeSearch;
use common\components\corecontrollers\ZeedController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * AttributeController implements the CRUD actions for Attribute model.
 */
class AttributeController extends ZeedController
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
                        'actions' => ['index', 'view', 'edit', 'create', 'update', 'delete', 'multipledelete', 'sort'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action)
                        {
                            return Yii::$app->user->identity['role'] == User::ROLE_SUPERADMIN ||
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
     * Lists all Attribute models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AttributeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Attribute model.
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
     * Creates a new Attribute model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Attribute();

        if ( ! empty(Yii::$app->request->post())) 
        {
            $post            = Yii::$app->request->post('Attribute');
            $model->label    = $post['label'];
            $model->position = $post['position'];
            $parent_id       = $post['parentId'];

            if (empty($parent_id)) 
                $model->makeRoot();
            else 
            {
                $parent = Attribute::findOne($parent_id);
                $model->appendTo($parent);
            }

            if ($model->save())
                return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Attribute model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ( ! empty(Yii::$app->request->post())) 
        {
            $post            = Yii::$app->request->post('Attribute');
            $model->label    = $post['label'];
            $model->position = $post['position'];
            $parent_id       = $post['parentId'];

            if ($model->save())
            {
                if (empty($parent_id)) // no parent, means that this node will become the root 
                {
                    if ( ! $model->isRoot())
                        $model->makeRoot();
                }   
                else // move the node to other root
                {
                    if ($model->id != $parent_id) // can not move node to self
                    {
                        $parent = Attribute::findOne($parent_id);
                        $model->appendTo($parent);
                    }
                }

                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Attribute model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model->isRoot())
            $model->deleteWithChildren(); // can not delete root without deleting its children
        else 
            $model->delete();

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
            {
                if (($model = Attribute::findOne($id)) !== null)
                {
                    if ($model->isRoot())
                        $model->deleteWithChildren();
                    else
                        $model->delete();
                }
            }
        }
    }

    /**
     * Finds the Attribute model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Attribute the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Attribute::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
