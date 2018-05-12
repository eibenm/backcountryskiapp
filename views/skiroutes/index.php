<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\bootstrap\ButtonDropdown;
use app\models\Skiroutes;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\SkiroutesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $columnsToShow array */

$this->title = 'Ski Routes';
$this->params['breadcrumbs'][] = $this->title;

$assetsUrl = Yii::$app->urlManager->getBaseUrl();

$this->registerJsFile($assetsUrl . '/js/columns-show.js', [ 'depends' => '\yii\web\JqueryAsset'], 'columns-show');
$this->registerCssFile($assetsUrl . '/css/columns-show.css', ['depends' => '\yii\bootstrap\BootstrapAsset'], 'coordinate-conversion-css');

?>
<div class="skiroutes-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="margin-bottom_20">
        <?= ButtonDropdown::widget([
            'label' => 'Create New Route for Area',
            'options' => [ 'class' => 'btn btn-success' ],
            'dropdown' => [
                'items' => array_map(function($data) {
                    return [
                        'label' => $data->name_area,
                        'url' => ['skiroutes/create', 'area_id' => $data->id]
                    ];
                }, Skiroutes::skiareas())
            ]
        ]) ?>
        <?= Html::a('All Routes Map', ['skiroutes/view-all-map'], ['class' => 'btn btn-primary', 'role' => 'button', 'target' => '_blank']) ?>
        <?= Html::a('All Routes KML <span class="glyphicon glyphicon-save"></span>', ['skiroutes/download-all-map'], ['class' => 'btn btn-primary', 'role' => 'button']) ?>
    </div>

    <?php

    echo Html::a('columns', '#column-form', ['data-toggle' => 'collapse', 'aria-expanded' => false, 'aria-controls' => 'column-form', 'class' => 'column-toggle']);

    echo $this->render('_columnsToShow', [
        'columnsToShow' => $columnsToShow
    ]);

    $columns = [];

    foreach ($columnsToShow as $column) {
        if ($column === 'download_kml') {
            $columns[] = [
                'class' => ActionColumn::className(),
                'template' => '{download}',
                'buttons' => [
                    'download' => function ($url, $model) {
                        return Html::a('<span style="padding: 5px;' . ($model->kml ? '' : ' display: none;') .'" class="glyphicon glyphicon-download-alt"></span>', $url, [
                            'title' => 'Download KML', 'data-pjax' => 0
                        ]);
                    }
                ],
                'urlCreator' => function ($action, $model) {
                    return ['skiroutes/download-kml', 'id' => $model->id];
                },
                'header' => 'KML'
            ];
        } else if ($column === 'bounds') {
            $columns[] = [
                'label' => 'Bounds',
                'format' => 'html',
                'value' => function ($model) {
                    return '<table style="border-spacing: 10px; border-collapse: separate; width=100%">' .
                        '<tr><th>' . $model->getAttributeLabel('bounds_northeast') . ':</th></tr>' .
                        '<tr><td>' . $model->bounds_northeast . '</td></tr>' .
                        '<tr><th>' . $model->getAttributeLabel('bounds_southwest') . ':</th></tr>' .
                        '<tr><td>' . $model->bounds_southwest . '</td></tr>' .
                    '</table>';
                },
                'contentOptions' => ['style' => 'width: 1%; white-space: nowrap;']
            ];
        } else if ($column === 'count_photos') {
            $columns[] = [
                'header' => 'Count Photos',
                'content' => function($model) {
                    return count($model->skiroutesImages);
                }
            ];
        } else {
            $columns[] = $column;
        }
    }

    Pjax::begin([
        'id' => 'ski-areas-grid-pjax',
        'timeout' => Yii::$app->constants->pjaxTimeout,
        'enablePushState' => false
    ]);

    echo GridView::widget([
        'id' => 'ski-areas-grid',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => ['class' => 'table-responsive'],
        'columns' => array_merge([
            [
                'class' => ActionColumn::className(),
                'template' => '{view}{update}{delete}',
                'urlCreator' => function ($action, $model) {
                    $url = '';
                    if ($action === 'update') {
                        $url = ['skiroutes/update','id' => $model->id];
                    } else if ($action === 'delete') {
                        $url = ['skiroutes/delete','id' => $model->id];
                    } else if ($action === 'view') {
                        $url = ['skiroutes/view','id' => $model->id];
                    }
                    return $url;
                },
                'headerOptions' => ['style' => 'min-width:90px;'],
                'contentOptions' => ['style' => 'text-align: center;'],
                'buttonOptions' => ['style' => 'padding: 5px;']
            ],
            [
                'class' => ActionColumn::className(),
                'template' => '{map}',
                'header' => 'Map',
                'buttons' => [
                    'map' => function ($url, $model) {
                        return Html::a('<span style="padding:5px;' . ($model->gps ? '' : ' display: none;') .'" class="glyphicon glyphicon-globe"></span>', $url, [
                            'title' => 'Map', 'target' => '_blank', 'data-pjax' => 0
                        ]);
                    }
                ],
                'urlCreator' => function ($action, $model) {
                    if ($action === 'map') {
                        return ['skiroutes/view-map', 'id' => $model->id];
                    }
                }
            ],
            [
                'class' => ActionColumn::className(),
                'template' => '{kml}',
                'header' => 'KML',
                'buttons' => [
                    'kml' => function ($url) {
                        return Html::a('<span style="padding:5px;" class="glyphicon glyphicon-download-alt"></span>', $url, [
                            'title' => 'Download', 'data-pjax' => 0
                        ]);
                    }
                ],
                'urlCreator' => function ($action, $model) {
                    if ($action === 'kml') {
                        return ['skiroutes/download-kmz', 'id' => $model->id];
                    }
                }
            ],
            [
                'attribute' => 'skiarea_id',
                'content' => function ($model) {
                    return $model->skiarea->name_area;
                },
                'filter' => Skiroutes::dropdown(),
                'filterInputOptions' => ['class' => 'form-control', 'style' => 'width: inherit;'],
            ]
        ], $columns)
    ]);

    Pjax::end();


    /*

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => ['class' => 'table-responsive'],
        'columns' => [
            [
                'class' => ActionColumn::className(),
                'template' => '{view}{update}{delete}',
                'buttons' => [
                    'update' => function ($url) {
                        return Html::a('<span style="padding: 5px;" class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => 'Update',
                        ]);
                    }
                ],
                'urlCreator' => function ($action, $model) {
                    if ($action === 'update') {
                        return ['skiroutes/update','id'=>$model->id];
                    }
                    if ($action === 'delete') {
                        return ['skiroutes/delete','id'=>$model->id];
                    }
                    if ($action === 'view') {
                        return ['skiroutes/view','id'=>$model->id];
                    }
                },
                'headerOptions' => ['style'=>'min-width:90px;'],
                'contentOptions' => ['style'=>'text-align: center;']
            ],
            [
                'class' => ActionColumn::className(),
                'template' => '{map}',
                'header' => 'Map',
                'buttons' => [
                    'map' => function ($url, $model) {
                        return Html::a('<span style="padding:5px;' . ($model->gps ? '' : ' display: none;') .'" class="glyphicon glyphicon-globe"></span>', $url, [
                            'title' => 'Map', 'target' => '_blank', 'data-pjax' => 0
                        ]);
                    }
                ],
                'urlCreator' => function ($action, $model) {
                    if ($action === 'map') {
                        return ['skiroutes/view-map', 'id' => $model->id];
                    }
                },
            ],
            [
                'class' => ActionColumn::className(),
                'template' => '{kml}',
                'header' => 'KML',
                'buttons' => [
                    'kml' => function ($url) {
                        return Html::a('<span style="padding:5px;" class="glyphicon glyphicon-download-alt"></span>', $url, [
                            'title' => 'Download', 'data-pjax' => 0
                        ]);
                    }
                ],
                'urlCreator' => function ($action, $model) {
                    if ($action === 'kml') {
                        return ['skiroutes/download-kmz', 'id' => $model->id];
                    }
                },
            ],
            [
                'attribute' => 'skiarea_id',
                'content' => function ($model) {
                    return $model->skiarea->name_area;
                },
                'filter' => Skiroutes::dropdown(),
                'filterInputOptions' => ['class' => 'form-control', 'style' => 'width: inherit;'],
            ],

            'name_route:ntext',
            'elevation_gain',
            'vertical',
            //'aspects:ntext',
            'distance',
            //'snowfall:ntext',
            //'avalanche_danger:ntext',
            //'skier_traffic:ntext',

            [
                'class' => ActionColumn::className(),
                'template' => '{download}',
                'buttons' => [
                    'download' => function ($url, $model) {
                        return Html::a('<span style="padding: 5px;' . ($model->kml ? '' : ' display: none;') .'" class="glyphicon glyphicon-download-alt"></span>', $url, [
                            'title' => 'Download KML', 'data-pjax' => 0
                        ]);
                    }
                ],
                'urlCreator' => function ($action, $model) {
                    if ($action === 'download') {
                        return ['skiroutes/download-kml', 'id' => $model->id];
                    }
                },
                'header' => 'KML'
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
                'header' => 'Count Photos',
                'content' => function($model) {
                    return count($model->skiroutesImages);
                }
            ]
        ]
    ]);

    */

    ?>

</div>
