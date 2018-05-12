<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Glossary */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="container-fluid margin-top_20">
    <div class="glossary-form">

        <?php $form = ActiveForm::begin(); ?>
        
        <div class="row">
            <div class="col-sm-6">
                <?= $form->field($model, 'term')->textInput() ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <?= $form->field($model, 'description')->textarea(['rows' => 10]) ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
