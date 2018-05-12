<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user app\models\User */

$this->title = 'Update User: ' . ' ' . $user->username;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'user' => $user,
    ]) ?>

</div>
