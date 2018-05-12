<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\forms\SignupForm;
use app\models\search\UserSearch;
use yii\web\Controller;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    
    /**
     * @var array Actions that are restricted to admin permissions
     */
    private $adminActions = [
        'index',
        'create',
        'update',
        'delete'
    ];
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => [
                    'index',
                    'create',
                    'update',
                    'delete',
                    'account'
                ],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'index',
                            'create',
                            'update',
                            'delete',
                            'account',
                        ],
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            if (in_array($action->id, $this->adminActions)) {
                                return Yii::$app->user->identity->usertype === User::USERTYPE_ADMIN; 
                            }
                            return true;
                        }
                    ]
                ],
                'denyCallback' => function ($rule, $action) {
                    if (in_array($action->id, $this->adminActions)) {
                        throw new ForbiddenHttpException('User must be logged in and have ADMIN permissions to access this page.');
                    }
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');
                }
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ]
            ]
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SignupForm();
        
        $loadedPost = $model->load(Yii::$app->request->post());
        
        // validate for ajax request
        if ($loadedPost && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        
        // validate for normal request
        if ($loadedPost && $model->signup()) {
            Yii::$app->session->setFlash('success', 'User created successfully.');
            return $this->redirect(['index']);
        }
        
        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $user = $this->findModel($id);
        $user->scenario = 'update';
        
        $loadedPost = $user->load(Yii::$app->request->post());
        
        // validate for ajax request
        if ($loadedPost && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($user);
        }
        
        // validate for normal request
        if ($loadedPost && $user->validate()) {
            if ($user->newPassword) {
                $user->setPassword($user->newPassword);
                Yii::$app->session->setFlash('success', 'Password updated successfully.');
            }
            if ($user->save(false)) {
                Yii::$app->session->setFlash('success', 'User updated successfully.');
                return $this->redirect(['index']);
            }
            else {
                Yii::$app->session->setFlash('danger', 'There was an error updating the user.');
                return $this->render('update', [
                    'user' => $user,
                ]);
            }
        }
        
        return $this->render('update', [
            'user' => $user,
        ]);
    }
    
    /**
     * Updates parts an existing User model.
     * For use of non-admins.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionAccount()
    {
        $user = Yii::$app->user->identity;
        $user->scenario = 'account';
        
        $loadedPost = $user->load(Yii::$app->request->post());
        
        // validate for ajax request
        if ($loadedPost && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($user);
        }
        
        // validate for normal request
        if ($loadedPost && $user->validate()) {
            if (!empty($user->newPassword)) {
                $user->setPassword($user->newPassword);
            }
            $user->save(false);
            Yii::$app->session->setFlash('success', 'Account updated.');
            return $this->redirect(['index']);
        }
        
        return $this->render('account', [
            'user' => $user,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $user = $this->findModel($id);
        $username = $user->username;
        
        if ($user->delete()) {
            Yii::$app->session->setFlash('success', 'User ' . $username . ' deleted successfully.');
            return $this->redirect(['index']);
        }

        Yii::$app->session->setFlash('danger', 'There was an error deleting the user.');
        return $this->redirect(['index']);
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
        }
        else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
