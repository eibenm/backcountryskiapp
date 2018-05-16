<?php

use yii\helpers\Html;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model app\models\Skiroutes */

$this->title = 'Update Ski Route: ' . $model->name_route;
$this->params['breadcrumbs'][] = ['label' => 'Ski Routes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name_route, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="skiroutes-update">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= Tabs::widget([
        'items' => [
            [
                'label' => 'Route',
                'content' => $this->render('_form', [
                    'model' => $model
                ]),
                'options'=>['class' => (!isset($_GET['photoID']) && !isset($_GET['gpsID'])) ? 'fade in' : 'fade']
            ],
            [
                'label' => 'GPS',
                'content' => $this->render('_formGps', [
                    'model' => $gps,
                    'routemodel' => $model
                ]),
                'active' => isset($_GET['gpsID']) ? true : false,
                'options' => ['class' => isset($_GET['gpsID']) ? 'fade in' : 'fade']
            ],
            [
                'label' => 'Images',
                'content' => $this->render('_formImages', [
                    'model' => $image,
                    'routemodel' => $model
                ]),
                'active' => isset($_GET['photoID']) ? true : false,
                'options' => ['class' => isset($_GET['photoID']) ? 'fade in' : 'fade']
            ]
        ]
    ]); ?>

</div>