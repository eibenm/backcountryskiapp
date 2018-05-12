<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Skiroutes */

$this->title = $model->name_route;
$this->params['breadcrumbs'][] = ['label' => 'Skiroutes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="skiroutes-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['skiroutes/update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['skiroutes/delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?php if ($model->gps): ?>
        <?= Html::a('<span class="glyphicon glyphicon-globe"></span> Map', ['skiroutes/view-map', 'id' => $model->id], [
            'class' => 'btn btn-primary',
            'target' => '_blank'
            ]
        ) ?>
        <?php endif; ?>
    </p>

    <div class="table-responsive">
        <?= DetailView::widget([
            'model' => $model,
            //'options' => ['class' => 'table-responsive'],
            'attributes' => [
                'name_route:ntext',
                'quip:ntext',
                'overview:ntext',
                'short_desc:ntext',
                'notes:ntext',
                'avalanche_info:ntext',
                'directions:ntext',
                'gps_guidance:ntext',
                'elevation_gain',
                'vertical',
                'aspects:ntext',
                'distance',
                'snowfall:ntext',
                'avalanche_danger:ntext',
                'skier_traffic:ntext',
                [
                    'label' => 'Bounds',
                    'format' => 'html',
                    'value' => '
                    <table style="border-spacing: 10px; border-collapse: separate; width=100%">
                        <tr><th>' . $model->getAttributeLabel('bounds_northeast') . ': </th><td>' . $model->bounds_northeast . '</td></tr>
                        <tr><th>' . $model->getAttributeLabel('bounds_southwest') . ': </th><td>' . $model->bounds_southwest . '</td></tr>
                    </table>',
                ],
                [
                    'attribute' => 'kml',
                    'format' => 'html',
                    'value' => Html::a($model->kml, ['skiroutes/download-kmz', 'id' => $model->id])
                ],
                [
                    'label' => 'Waypoints',
                    'value' => $model->waypoints($model),
                    'format' => 'html',
                    'visible' => $model->gps ? true : false
                ],
                [
                    'label' => 'Images',
                    'value' => $model->viewimages($model),
                    'format' => 'html',
                    'visible' => $model->skiroutesImages ? true : false
                ],
                'mbtiles:ntext'
            ]
        ]) ?>
    </div>

</div>
