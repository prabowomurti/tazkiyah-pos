<?php

namespace backend\controllers;

use Yii;
use common\models\User;
use common\models\search\UserSearch;
use common\components\corecontrollers\ZeedController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends ZeedController
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
                        'actions' => ['index', 'view', 'edit', 'create', 'update', 'delete', 'multipledelete', 'profile'],
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post())) 
        {
            if (strlen($model->password) < 6)
            {
                $model->addError('password', 'Password should contain at least 6 characters.');
                return $this->render('create', [
                    'model' => $model,
                ]);
            }

            $model->setPassword($model->password);
            $model->generateAuthKey();

            if ($model->save())
                return $this->redirect(['view', 'id' => $model->id]);
            else 
            {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
                
        }
        else 
            return $this->render('create', [
                'model' => $model,
            ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->post())
        {
            $post_user = Yii::$app->request->post('User');

            $model->username  = $post_user['username'];
            $model->email     = $post_user['email'];
            $model->role      = $post_user['role'];
            $model->status    = $post_user['status'];

            // update password
            if ( ! empty($post_user['password']))
            {
                if (strlen($post_user['password']) < 6)
                {
                    $model->addError('password', 'Password should contain at least 6 characters.');
                    return $this->render('update', [
                        'model' => $model,
                    ]);
                }

                $model->setPassword($post_user['password']);
            }

            if ($model->save())
                return $this->redirect(['view', 'id' => $model->id]);
            else 
                return $this->render('update', ['model' => $model,]);
        }
        else 
            return $this->render('update', ['model' => $model]);
    }

    /**
     * Updates the logged in user's profile
     * @param  boolean $success 
     * @return mixed
     */
    public function actionProfile($success = false)
    {
        $model = $this->findModel(Yii::$app->user->id);

        if (Yii::$app->request->post())
        {
            $post_user = Yii::$app->request->post('User');

            $model->username  = $post_user['username'];

            // update password
            if ( ! empty($post_user['password']))
            {
                if (strlen($post_user['password']) < 6)
                {
                    $model->addError('password', 'Password should contain at least 6 characters.');
                    return $this->render('update', [
                        'model' => $model,
                    ]);
                }

                $model->setPassword($post_user['password']);
            }

            if ($model->save())
                return $this->redirect(['profile', 'success' => true]);
            else 
                return $this->render('profile', ['model' => $model]);
        }
        else 
            return $this->render('profile', ['model' => $model, 'success' => $success]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Delete multiplede IDs
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
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
