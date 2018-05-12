<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to login:</p>

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-md-3\">{input}</div>\n<div class=\"col-md-7\">{error}</div>",
            'labelOptions' => ['class' => 'col-md-2 control-label'],
        ],
    ]); ?>

    <?= $form->field($model, 'username') ?>
    <?= $form->field($model, 'password')->passwordInput() ?>
    <?= $form->field($model, 'rememberMe', [
        'template' => "{label}<div class=\"col-lg-offset-2 col-lg-3\">{input}</div>\n<div class=\"col-lg-7\">{error}</div>",
    ])->checkbox() ?>
    
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            <br /><br />
            <?php //echo Html::a('Forgot Password?', ['/site/request-password-reset']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
