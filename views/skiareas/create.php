<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Skiareas */

$this->title = 'Create New Mountain Range';
$this->params['breadcrumbs'][] = ['label' => 'Mountain Ranges', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="skiareas-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'image' => $image
    ]) ?>

</div>
