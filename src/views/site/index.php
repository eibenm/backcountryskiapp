<?php
/* @var $this yii\web\View */
$this->title = 'Home';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Backcountry Ski App</h1>
        <p class="lead">Webapp to enter data for <i>The Bozeman and Big Sky Backcountry Ski Guide.</i></p>
        <?php if (Yii::$app->user->isGuest): ?>
            <p><a class="btn btn-lg btn-success" href="<?= Yii::$app->urlManager->createUrl(['site/login']) ?>">Log In</a></p>
        <?php endif; ?>
    </div>

    <hr class="divider"/>
    
    <div class="body-content">

        <div class="row text-center">
            <div class="col-lg-6">
                <h2>Create a New Ski Area</h2>
                <p>Create a New Backcountry Ski area.  Allowed one picture per area.</p>
                <p><a class="btn btn-info" href="<?= Yii::$app->urlManager->createUrl(['skiareas/index']) ?>">Ski Area &raquo;</a></p>
            </div>
            <div class="col-lg-6">
                <h2>Create a New Ski Route</h2>
                <p>Create a new route for a given Backcountry Ski area.  There may be multiple gps waypoints and pictures per route.</p>
                <p><a class="btn btn-info" href="<?= Yii::$app->urlManager->createUrl(['skiroutes/index']) ?>">Ski Route &raquo;</a></p>
            </div>
        </div>
        
        <hr class="divider"/>
        
        <div class="row text-center">
            <div class="col-lg-offset-3 col-lg-6">
                <h2>Glossary</h2>
                <p>Listing of glossary terms for the application.</p>
                <p><a class="btn btn-info" href="<?= Yii::$app->urlManager->createUrl(['glossary/index']) ?>">Glossary &raquo;</a></p>
            </div>
        </div>

    </div>
    
</div>
