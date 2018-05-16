<?php

$this->registerCssFile(Yii::$app->urlManager->getBaseUrl() . '/css/coordinate-conversion.css', [
    'depends' => ['yii\bootstrap\BootstrapAsset'],
], 'coordinate-conversion-css');

$this->registerJsFile(Yii::$app->urlManager->getBaseUrl() . '/js/coordinate-conversion.js', [
    'depends' => ['\yii\web\JqueryAsset']
], 'coordinate-conversion-js');

?>

<div class="coordinate-coversion">
    <ul>
        <li><h4>Coordintes <small>Degrees Minutes Seconds</small></h4></li>
        <li>
            <div class="table-responsive">
                <table class="table-condensed">
                    <tr>
                        <td>Lat - D M S:</td>
                        <td><input type="text" name="dDmsLat" maxlength ="2" size ="2" id="dDmsLat" class="form-control" /></td>
                        <td><input type="text" name="dDmsLat" maxlength="2" size="2" id ="mDmsLat" class="form-control" /></td>
                        <td><input type="text" name="sDmsLat" maxlength="5" size="5" id ="sDmsLat" class="form-control" /></td>
                    </tr>
                    <tr>
                        <td>Long - D M S:</td>
                        <td><input type="text" name="dDmsLong" maxlength="4" size="4" id="dDmsLong" class="form-control" /></td>
                        <td><input type="text" name="dDmsLong" maxlength="2" size="2" id="mDmsLong" class="form-control" /></td>
                        <td><input type="text" name="sDmsLong" maxlength="5" size="5" id="sDmsLong" class="form-control" /></td>
                    </tr>
                </table>
            </div>
        </li>
        <li style="margin-bottom:10px;">
            <a href="javascript:void(0);" onclick="convertDMS(event)">Convert DMS</a>
        </li>
        <li><h4>Coordintes <small>Degrees Decimal Minutes</small></h4></li>
        <li>
            <div class="table-responsive">
                <table class="table-condensed">
                    <tr>
                        <td>Lat -D M:</td>
                        <td><input type = "text" id = "d_ddm" size = "2" class="form-control" /></td>
                        <td><input type = "text" id = "m_ddm" size = "5" class="form-control" /></td>
                    </tr>
                    <tr>
                        <td>Long -D M:</td>
                        <td><input type = "text" id = "dl_ddm" size = "4" class="form-control" /></td>
                        <td><input type = "text" id = "ml_ddm" size = "5" class="form-control" /></td>
                    </tr>
                </table>
            </div>
        </li>
        <li>
            <a href="javascript:void(0);" onclick="convertDDM(event)">Convert DDM</a>
        </li>
    </ul>
</div>