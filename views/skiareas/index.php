<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\SkiareasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Mountain Range';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="skiareas-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create New Area', ['skiareas/create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(['timeout' => Yii::$app->constants->pjaxTimeout]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => ['class' => 'table-responsive'],
        'columns' => [
            [
                'class' => ActionColumn::className(),
                'template' => '{create-route}{update}',
                'buttons' => [
                    'create-route' => function ($url) {
                        return Html::a('<span style="padding: 5px;" class="glyphicon glyphicon-plus"></span>', $url, ['title' => 'Add New Route', 'data-pjax' => 0]);
                    },
                    'update' => function ($url) {
                        return Html::a('<span style="padding: 5px;" class="glyphicon glyphicon-pencil"></span>', $url, ['title' => 'Update', 'data-pjax' => 0]);
                    }
                ],
                'urlCreator' => function ($action, $model) {
                    if ($action === 'create-route') {
                        return ['skiroutes/create','area_id' => $model->id];
                    }
                    if ($action === 'update') {
                        return ['skiareas/update','id' => $model->id];
                    }
                },
                'headerOptions' => ['style' => 'min-width:90px;'],
                'contentOptions' => ['style' => 'text-align: center;']
            ],
            'name_area:ntext',
            [
                'attribute' => 'areaConditions',
                'contentOptions' => ['style' => 'max-width: 500px;']
            ],
            [
                'attribute' => 'color',
                'content' => function($data) {
                    return '<div style="width: 40px; height: 40px; background-color: ' . $data->color . '; margin: 0 auto; border: solid #C2C2C2 1px;"></div>';
                }
            ],
            [
                'label' => 'Bounds',
                'format' => 'html',
                'value' => function ($model) {
                    return '
                        <table style="border-spacing: 10px; border-collapse: separate; width=100%">
                            <tr><th>' . $model->getAttributeLabel('bounds_northeast') . ':</th></tr>
                            <tr><td>' . $model->bounds_northeast . '</td></tr>
                            <tr><th>' . $model->getAttributeLabel('bounds_southwest') . ':</th></tr>
                            <tr><td>' . $model->bounds_southwest . '</td></tr>
                        </table>';
                },
                'contentOptions' => ['style' => 'width: 1%; white-space: nowrap;']
            ],
            [
                'label' => 'Image',
                'format' => 'html',
                'value' => function ($model) {
                    return (isset($model->image->avatar)) ? Html::img('@web/images/' . $model->image->avatar, ['height' => '100px']) : '';
                }
            ],
            /*[
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
                        return ['skiareas/download', 'id' => $model->id];
                    }
                },
                'header' => 'Download'
            ]*/
        ]
    ]); ?>
    <?php Pjax::end(); ?>

</div>
