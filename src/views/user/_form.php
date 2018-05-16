<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\User;

/* @var $this yii\web\View */
/* @var $user app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">
    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-user']); ?>
                <?= $form->field($user, 'username', ['enableAjaxValidation' => false]) ?>
                <b>Leave blank to not change password</b>
                <?= $form->field($user, 'newPassword')->passwordInput() ?>
                <?= $form->field($user, 'newPasswordConfirm')->passwordInput() ?>
                <?= $form->field($user, 'name_first') ?>
                <?= $form->field($user, 'name_last') ?>
                <?= $form->field($user, 'email', ['enableAjaxValidation' => true]) ?>
                <?= $form->field($user, 'usertype')->dropDownList(User::userTypesdropdown(), ['prompt' => '']) ?>
                <div class="form-group">
                    <?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('Cancel', ['index'], ['class'=>'btn btn-success']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
