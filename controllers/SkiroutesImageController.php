<?php

namespace app\controllers;

use Yii;
use app\models\SkiroutesImage;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * SkiroutesImageController implements the CRUD actions for SkiroutesImage model.
 */
class SkiroutesImageController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
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
     * Deletes an existing SkiroutesImage model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $route_id = $model->route_id;
        $model->delete();
        $model->image->delete();
        $model->image->deleteCurrentImage();
        return $this->redirect(['skiroutes/update','id' => $route_id, 'photoID' => '']);
    }
    
    public function actionDownload($id)
    {
        $model = $this->findModel($id);
        $filePath = Yii::$app->params['imagePath'] . $model->image->avatar;
        return Yii::$app->response->sendFile($filePath, $model->image->filename);
    }

    /**
     * Finds the SkiroutesImage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SkiroutesImage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SkiroutesImage::findOne($id)) !== null) {
            return $model;
        }
        else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
