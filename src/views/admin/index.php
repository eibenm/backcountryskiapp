<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\widgets\Pjax;

/* @var $this yii\web\View */

$this->title = 'Admin';
$this->params['breadcrumbs'][] = $this->title;

$urlManager = Yii::$app->urlManager;

$this->registerJs('
    $(".make-live").click(function(e) {
        e.preventDefault();
        var makeLiveUrl = this.href;
        $.get("' . $urlManager->createAbsoluteUrl(['admin/get-data-json-version']) . '", function(version) {
            if (version === false) {
                alert("Export your DB to JSON fist before attempting to make it live!");
                return false;
            }
            if (confirm("Do you want to make the json file for version " + version + " live?")) {
                window.location.href = makeLiveUrl;
            }
        }, "json");
    }); 
');

?>

<h2>This is the export page.</h2>

<?php if ($fileExists) {
    echo Html::tag('p', Html::a('Download File', ['admin/download']));
} ?>

<p>
    <a href="<?= $urlManager->createUrl(['admin/export']) ?>" class="btn btn-primary">Export DB To JSON</a>
    <a href="<?= $urlManager->createUrl(['admin/make-live']) ?>" class="btn btn-info make-live">Make current JSON live</a>
</p>

<hr />

<div class="row">
    <div class="col-md-6">
        <p>
            <a href="<?= $urlManager->createUrl(['version/create']) ?>" class="btn btn-success">Create Version</a>
        </p>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <?php Pjax::begin(['timeout' => Yii::$app->constants->pjaxTimeout]); ?>
        <?= GridView::widget([
            'dataProvider' => $versionDataProvider,
            'columns' => [
            [
                'class' => ActionColumn::className(),
                'template' => '{delete}',
                'buttons' => [
                    'delete' => function($url, $model) use ($currentVersionID) {
                        if ($model->id === $currentVersionID) {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                'title' => 'Delete', 'data-method' => 'post'
                            ]);
                        }
                    }
                ],
                'urlCreator' => function ($action, $model) {
                    if ($action === 'delete') { return ['version/delete','id' => $model->id]; }
                },
                'headerOptions' => ['style' => 'width:90px;'],
                'contentOptions' => ['style' => 'text-align: center;']
            ],
            'version:ntext',
            'live:boolean'
            ],
        ]); ?>
        <?php Pjax::end(); ?>
    </div>
</div>