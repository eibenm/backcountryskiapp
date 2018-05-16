<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\User $user
 */

$this->title = 'Account';
$this->params['breadcrumbs'][] = $this->title;
?>

    <h1><?= Html::encode($this->title) ?></h1>
    
    <?php $form = ActiveForm::begin(['id' => 'account-form']); ?>
    
    <div class="user-account">
        <div class="row">
            <div class="col-lg-5">
                <b style="color: #FF0000;">Required for any account changes.</b>
                <?= $form->field($user, 'currentPassword', ['enableAjaxValidation' => true])->passwordInput() ?>
            </div>
        </div> 
        <hr />
        <div class="row">
            <div class="col-lg-5">
                <?= $form->field($user, 'username', ['enableAjaxValidation' => true]) ?>
                <?= $form->field($user, 'email', ['enableAjaxValidation' => true]) ?>
                <?= $form->field($user, 'newPassword')->passwordInput() ?>
                <?= $form->field($user, 'newPasswordConfirm')->passwordInput() ?>
                <div class="form-group">
                    <?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('Cancel', ['/site/index'], ['class'=>'btn btn-success']) ?>
                </div>
            </div>
        </div> 
    </div>
    
    <?php ActiveForm::end(); ?>