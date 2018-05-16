<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use app\models\search\GpsSearch;
use yii\grid\ActionColumn;
use yii\widgets\Pjax;
use yii\bootstrap\Collapse;

$searchModel = new GpsSearch();
$dataProvider = $searchModel->search(Yii::$app->request->queryParams, $routemodel->id);

?>

<div class="container-fluid margin-top_20">

    <?php Pjax::begin(['timeout' => Yii::$app->constants->pjaxTimeout]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => ['class' => 'table-responsive'],
        'id' => 'gps-grid',
        'columns' => [
            [
                'attribute' => 'id',
                'filter' => false,
                'headerOptions' => ['style' => 'display:none'],
                'filterOptions' => ['style' => 'display:none'],
                'contentOptions' => ['style' => 'display:none']
            ],
            [
                'class' => ActionColumn::className(),
                'template' => '{delete}',
                'urlCreator' => function ($action, $model) {
                    if ($action === 'delete') {
                        return ['gps/delete', 'id' => $model->id];
                    }
                },
                'contentOptions' => ['style' => 'text-align: center;']
            ],
            'waypoint',
            'lat_dms',
            'lon_dms',
            'lat',
            'lon'
        ]
    ]); ?>
    <?php Pjax::end(); ?>

</div>

<hr class="divider" />

<div class="container-fluid margin-top_20">
    <div class="gps-form">

        <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-sm-4">
                <?= $form->field($model, 'waypoint')->textInput() ?>
            </div>
        </div>
        
        <!--<label><i>Coordinates must be in decimal degrees.</i></label>-->
        <label><i>Coordinates will be automatically converted to decimal degrees.</i></label>
        
        <div class="row">
            <div class="col-sm-4">
                <?= $form->field($model, 'lat_dms')->textInput() ?>
            </div>
            <div class="col-sm-4">
                <?= $form->field($model, 'lon_dms')->textInput() ?>
            </div>
        </div>
        
        <!-- Coordinate Coversion Widget -->
        <!--<div class="row">
            <div class="col-sm-8">
            <?= Collapse::widget([
                'items' => [
                    [
                        'label' => 'Coordinate Conversion',
                        'content' => $this->render('_formGpsCoordConv')
                    ]
                ]
            ]) ?>
            </div>
        </div>-->

        <div class="form-group">
            <?= Html::submitButton('Create', ['class' => 'btn btn-success']) ?>
        </div>
        
        <?php ActiveForm::end(); ?>
        
    </div>
</div>