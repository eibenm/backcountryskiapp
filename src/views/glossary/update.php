<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Glossary */

$this->title = 'Update Glossary: ' . ' ' . $model->term;
$this->params['breadcrumbs'][] = ['label' => 'Glossary', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="glossary-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>