<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use app\models\search\SkiroutesImageSearch;
use yii\grid\ActionColumn;
use yii\widgets\Pjax;
use kartik\file\FileInput;

$searchModel = new SkiroutesImageSearch();
$dataProvider = $searchModel->search(Yii::$app->request->queryParams, $routemodel->id);

?>

<div class="container-fluid margin-top_20">

    <?php Pjax::begin(['timeout' => Yii::$app->constants->pjaxTimeout]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => ['class' => 'table-responsive'],
        'id' => 'image-grid',
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
                        return ['skiroutes-image/delete','id' => $model->id];
                    }
                },
                'contentOptions' => ['style' => 'text-align: center;']
            ],
            'image.filename',
            'image.caption',
            [
                'label' => 'image',
                'format' => 'html',
                'value' => function ($model) {
                    return (isset($model->image)) ? Html::img('@web/images/' . $model->image->avatar, ['height' => '100px']) : '';
                }
            ],
            [
                'class' => ActionColumn::className(),
                'template' => '{download}',
                'buttons' => [
                    'download' => function ($url, $model) {
                        return Html::a('<span style="padding: 5px;' . ($model->image ? '' : ' display: none;') .'" class="glyphicon glyphicon-download-alt"></span>', $url, [
                            'title' => 'Download Image', 'data-pjax' => 0
                        ]);
                    }
                ],
                'urlCreator' => function ($action, $model) {
                    if ($action === 'download') {
                        return ['skiroutes-image/download', 'id' => $model->id];
                    }
                },
                'header' => 'Download'
            ],
            'image.kml_image:boolean'
        ]
    ]); ?>
    <?php Pjax::end(); ?>

</div>

<hr class="divider" />

<div class="container-fluid margin-top_20">
    <div class="images-form-form">

        <?php $form = ActiveForm::begin([
            'options' => ['enctype' => 'multipart/form-data']
        ]); ?>

        <div class="row">
            <div class="col-sm-6">
                <?= $form->field($model, 'caption')->textarea(['rows' => 6]) ?>
            </div>
            
            <div class="col-sm-6">
                <label class="control-label">Add Attachments (Please resize images to under 2 MB)</label>
                <h5 style="margin-top: 0;">All Images will be automagically resized to 900px wide if over.</h5>
                <?= $form->field($model, 'file')->widget(FileInput::classname(), [
                    'options' => ['accept' => 'image/*'],
                    'pluginOptions' => [
                        'allowedFileExtensions' => ['jpg', 'png', 'gif'],
                        'showUpload' => false
                    ]
                ]); ?>
            </div> 
        </div>
        
        <div class="row">
            <div class="col-sm-6">
                <?= $form->field($model, 'kml_image')->checkbox() ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Create', ['class' => 'btn btn-success']) ?>
        </div>
        
        <?php ActiveForm::end(); ?>
        
    </div>
</div>