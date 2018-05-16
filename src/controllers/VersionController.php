<?php

namespace app\controllers;

use Yii;
use app\models\Version;
use app\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * VersionController implements the CRUD actions for Version model.
 */
class VersionController extends Controller
{
    /**
     * @var array Actions that are restricted to admin permissions
     */
    private $adminActions = [
        'create',
        'update',
        'delete'
    ];

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'create',
                            'delete'
                        ],
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            if (in_array($action->id, $this->adminActions)) {
                                return Yii::$app->user->identity->usertype === User::USERTYPE_ADMIN;
                            }
                            return true;
                        },
                    ],
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
                ],
            ],
        ];
    }

    /**
     * Creates a new Version model.
     * @return mixed
     */
    public function actionCreate()
    {
        $version = new Version();
        
        $lastRecord = Version::find()->orderBy(['id' => SORT_DESC])->one();
        
        if (!$lastRecord) { // This is the first record
            $version->version = '0.0';
            $version->live = 0;
        }
        else { // Record already exists
            $version->version = strval(floatval($lastRecord->version) + 0.1);
            $version->live = 0;
        }
        
        if ($version->save()) {
            Yii::$app->session->setFlash('success', 'New version '  . $version->version  . ' successfully created.');
        }
        else {
            Yii::$app->session->setFlash('danger', 'Something went wrong.');
        }
        
        return $this->redirect(['admin/index']);
    }

    /**
     * Deletes an existing Version model.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        
        $lastModel = Version::find()->orderBy(['id' => SORT_DESC])->one();
        
        if ($lastModel) {
            
            $lastModel->live = 1;
            $lastModel->save();
            
            // Export most recent json file
            \app\controllers\AdminController::export();

            // Make it live
            return $this->redirect(['admin/make-live']);
        }
        
        return $this->redirect(['admin/index']);
    }

    /**
     * Finds the Version model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Version the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($version = Version::findOne($id)) !== null) {
            return $version;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
