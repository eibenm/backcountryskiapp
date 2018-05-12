<?php

use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $columnsToShow array */

?>

<div class="collapse column-form" id="column-form">
    <div class="well">

    <?php

    $this->registerJs('

        // Reload gridview on columns change submit
        $(document).ready(function(){
            $("#update-ski-areas-grid").on("pjax:end", function() {
                $.pjax.reload({container:"#ski-areas-grid-pjax","push":false,"replace":false,"timeout":' . Yii::$app->constants->pjaxTimeout . ',"scrollTo":false});
            });
        });

    ');

    Pjax::begin([
        'id' => 'update-ski-areas-grid',
        'timeout' => Yii::$app->constants->pjaxTimeout,
        'enablePushState' => false
    ]);

    $columns = [
        'name_route' => 'Name Route',
        'elevation_gain' => 'Elevation Gain',
        'vertical' => 'Vertical',
        'aspects' => 'Aspects',
        'distance' => 'Distance',
        'snowfall' => 'Snowfall',
        'avalanche_danger' => 'Avalanche Danger',
        'skier_traffic' => 'Skier Traffic',
        'download_kml' => 'Download KML',
        'bounds' => 'Bounds',
        'count_photos' => 'Count Photos'
    ];

    echo Html::beginForm(Yii::$app->urlManager->createUrl($this->context->route), 'get', [
        'id' => 'columns-to-show-form',
        'data-pjax' => true
    ]);

    echo Html::tag('div', Html::a('Default View', '#', ['id' => 'columns-default-view']));

    $checkBoxes = '';

    foreach ($columns as $field => $label) {
        $checkBoxes .= Html::checkbox("columnsToShow[$field]", in_array($field, $columnsToShow), ['label' => $label, 'labelOptions' => ['style' => 'display: block;']]);
    }

    echo Html::tag('div', $checkBoxes, ['style' => 'margin-top: 10px; margin-bottom: 10px;']);

    echo Html::submitButton('Update Grid', ['class' => 'btn btn-primary']);
    echo Html::a('Close', '#', ['id' => 'close-columns-to-show', 'style' => 'margin-left: 10px;']);

    echo Html::endForm();

    Pjax::end();

    ?>

    </div>
</div>