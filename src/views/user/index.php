<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\grid\ActionColumn;
use app\models\User;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;

Yii::$app->formatter->dateFormat = 'php:Y-m-d H:i';

?>

<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?></p>

    <?php Pjax::begin(['timeout' => Yii::$app->constants->pjaxTimeout]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => ['class' => 'table-responsive'],
        'columns' => [
            [
                'class' => ActionColumn::className(),
                'template' => '{update}{delete}',
                'buttonOptions' => ['style' => 'padding: 5px;'],
                'headerOptions' => ['style'=>'width: 70px;'],
            ],
            'username',
            'name_first',
            'name_last',
            //'auth_key',
            //'password_hash',
            //'password_reset_token',
            'email:email',
            [
                'attribute' => 'created_at',
                'format' => 'date',
                'filter' => ''
            ],
            [
                'attribute' => 'updated_at',
                'format' => 'date',
                'filter' => ''
            ],
            [
                'attribute' => 'usertype',
                'content' => function ($model) { return $model->getUserType(); },
                'filter' => User::userTypesdropdown()
            ]
        ]
    ]); ?>
    <?php Pjax::end(); ?>

</div>
