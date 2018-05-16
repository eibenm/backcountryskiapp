<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\User;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\SignupForm $model
 */

$this->title = 'Signup';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to signup:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
                <?= $form->field($model, 'username', ['enableAjaxValidation' => true]) ?>
                <?= $form->field($model, 'nameFirst') ?>
                <?= $form->field($model, 'nameLast') ?>
                <?= $form->field($model, 'email', ['enableAjaxValidation' => true]) ?>
                <?= $form->field($model, 'usertype')->dropDownList(User::userTypesdropdown(), ['prompt' => '']) ?>
                <?= $form->field($model, 'newPassword')->passwordInput() ?>
                <?= $form->field($model, 'newPasswordConfirm')->passwordInput() ?>
                <div class="form-group">
                    <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                    <?= Html::a('Cancel', ['index'], ['class'=>'btn btn-success']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
