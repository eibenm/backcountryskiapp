<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\color\ColorInput;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\Skiareas */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="container-fluid margin-top_20">
    <div class="skiareas-form">

        <?php $form = ActiveForm::begin([
            'options' => ['enctype'=>'multipart/form-data']
        ]); ?>

        <div class="row">
            <div class="col-sm-6">
                <?= $form->field($model, 'name_area')->textInput() ?>
            </div>
            <div class="col-sm-6">
                <?= $form->field($model, 'color')->widget(ColorInput::classname(), [
                    'options' => ['placeholder' => 'Select color ...'],
                ]); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <?= $form->field($model, 'conditions')->textarea(['rows' => 12]) ?>
            </div>
            <div class="col-sm-6">
                <?= $form->field($model, 'bounds_southwest')->textInput(['placeholder' => 'Ex: 40.45123, -120.45124']) ?>
                <?= $form->field($model, 'bounds_northeast')->textInput(['placeholder' => 'Ex: 40.45123, -120.45124']) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <?= Html::label('Add Attachments (Please resize images to under 2 MB)') ?>
                <?= $form->field($image, 'file')->widget(FileInput::classname(), [
                    'options' => ['accept' => 'image/*'],
                    'pluginOptions' => [
                        'allowedFileExtensions' => ['jpg', 'png', 'gif'],
                        'showUpload' => false
                    ]
                ]); ?>
                <?php
                    $imageName = isset($model->image->filename) ? $model->image->filename : 'No File';
                    echo '<p><i>(Currently: ' . $imageName . ')</i></p>';
                    if ($imageName !== 'No File') {
                        echo Html::img('@web/images/' . $model->image->avatar, ['height' => '200px;']);
                    }
                ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success margin-top_20' :
                                                                                                                'btn btn-primary margin-top_20']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
