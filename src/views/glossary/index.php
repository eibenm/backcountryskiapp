<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\GlossarySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Glossary';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="glossary-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Glossary', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(['timeout' => Yii::$app->constants->pjaxTimeout]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => ['class' => 'table-responsive'],
        'columns' => [
            [
                'class' => ActionColumn::className(),
                'template' => '{update}{delete}',
                'buttons' => [
                    'update' => function ($url) {
                        return Html::a('<span style="padding: 5px;" class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => 'Update',
                        ]);
                    },
                ],
                'headerOptions' => ['style'=>'min-width:90px;'],
                'contentOptions' => ['style'=>'text-align: center;']
            ],
            'term:ntext',
            [
                'attribute' => 'description',
                'content' => function($model) {
                    return '<div style="max-height: 100px; overflow: hidden;">' . $model->description . '</div>';
                }
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>

</div>
