<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\Skiroutes */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="container-fluid margin-top_20">
    <div class="skiroutes-form">

        <?php $form = ActiveForm::begin([
            'options' => ['enctype'=>'multipart/form-data']
        ]); ?>

        <div class="row">
            <div class="col-sm-4">
                <?= $form->field($model, 'name_route')->textInput() ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-4">
                <?= $form->field($model, 'quip')->textInput() ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-4">
                <?= $form->field($model, 'overview')->textarea(['rows' => 10]) ?>
            </div>
            <div class="col-sm-4">
                <?= $form->field($model, 'short_desc')->textarea(['rows' => 10]) ?>
            </div>
            <div class="col-sm-4">
                <?= $form->field($model, 'notes')->textarea(['rows' => 10]) ?>
            </div>
        </div>

            <div class="row">
            <div class="col-sm-4">
                <?= $form->field($model, 'avalanche_info')->textarea(['rows' => 10]) ?>
            </div>
            <div class="col-sm-4">
                <?= $form->field($model, 'directions')->textarea(['rows' => 10]) ?>
            </div>
            <div class="col-sm-4">
                <?= $form->field($model, 'gps_guidance')->textarea(['rows' => 10]) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-4">
                <i>In feet</i>
                <?= $form->field($model, 'elevation_gain')->textInput() ?>
            </div>
            <div class="col-sm-4">
                <i>In miles</i>
                <?= $form->field($model, 'distance')->textInput() ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-4">
                <i>In feet</i>
                <?= $form->field($model, 'vertical')->textInput() ?>
            </div>
            <div class="col-sm-4">
                <i>In inches</i>
                <?= $form->field($model, 'snowfall')->textInput() ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-4">
                <?= $form->field($model, 'aspects')->textInput() ?>
            </div>
            <div class="col-sm-4">
                <?= $form->field($model, 'avalanche_danger')->textInput() ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-4">
                <?= $form->field($model, 'skier_traffic')->textInput() ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-4">
                <?= $form->field($model, 'bounds_southwest')->textInput(['placeholder' => 'Ex: 40.45123, -120.45124']) ?>
                <?= $form->field($model, 'bounds_northeast')->textInput(['placeholder' => 'Ex: 40.45123, -120.45124']) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <?= $form->field($model, 'kml')->widget(FileInput::classname(), [
                    'options' => ['accept' => '.kml, .kmz'],
                    'pluginOptions' => [
                        'allowedFileExtensions' => ['kml', 'kmz'],
                        'showUpload' => false
                    ]
                ]); ?>
                <p><i>(Currently: <?= $model->kml ? $model->kml : 'No File'; ?>)</i></p>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-4">
                <?= $form->field($model, 'mbtiles')->textInput(['placeholder' => 'Ex: mytiles.mbtiles']) ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
