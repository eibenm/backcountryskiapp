<?php

namespace app\controllers;

use Yii;
use app\models\Skiareas;
use app\models\search\SkiareasSearch;
use app\models\File;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

/**
 * SkiareasController implements the CRUD actions for Skiareas model.
 */
class SkiareasController extends Controller
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
     * Lists all Skiareas models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SkiareasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Skiareas model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Skiareas();
        $image = new File();
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $image->file = UploadedFile::getInstance($image, 'file');
            if ($image->file) {
                $image->caption = '';
                $image->kml_image = 0;
                if ($image->uploadImage()) {
                    $model->image_id = $image->id;
                }
            }
            $model->permissions = true;
            $model->save(false);
            return $this->redirect(['index']);
        }
        
        return $this->render('create', [
            'model' => $model,
            'image' => $image
        ]);
    }

    /**
     * Updates an existing Skiareas model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $image = $model->image_id ? File::findOne($model->image_id) : new File();
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $newImage = UploadedFile::getInstance($image, 'file');
            if ($newImage) {
                if ($image->avatar) {
                    $image->deleteCurrentImage();
                }
                $image->file = $newImage;
                if ($image->uploadImage()) {
                    $model->image_id = $image->id;
                }
            }
            $model->save(false);
            return $this->redirect(['index']);
        }
        
        return $this->render('update', [
            'model' => $model,
            'image' => $image
        ]);
    }
    
    public function actionDownload($id)
    {
        $model = $this->findModel($id);
        $filePath = Yii::$app->params['imagePath'] . $model->image->avatar;
        return Yii::$app->response->sendFile($filePath, $model->image->filename);
    }

    /**
     * Finds the Skiareas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Skiareas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Skiareas::findOne($id)) !== null) {
            return $model;
        }
        else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
