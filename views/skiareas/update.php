<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Skiareas */

$this->title = 'Update Mountain Range: '  . $model->name_area;
$this->params['breadcrumbs'][] = ['label' => 'Mountain Ranges', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="skiareas-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'image' => $image
    ]) ?>

</div>
