<?php

use yii\helpers\Html;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model app\models\Skiroutes */

$this->title = 'Create Ski Route';
$this->params['breadcrumbs'][] = ['label' => 'Skiroutes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="skiroutes-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= Tabs::widget([
        'items' => [
            [
                'label' => 'Route',
                'content' => $this->render('_form', [
                    'model' => $model
                ]),
                'active' => true
            ]
        ]
    ]); ?>

</div>
