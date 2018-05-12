<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Glossary */

$this->title = 'Create Glossary';
$this->params['breadcrumbs'][] = ['label' => 'Glossary', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="glossary-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>