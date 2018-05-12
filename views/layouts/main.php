<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\models\User;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <link rel="shortcut icon" href="<?= Yii::$app->request->baseUrl; ?>/favicon.ico" type="image/x-icon" />
        <?php $this->head() ?>
    </head>
    <body>

    <?php $this->beginBody() ?>
        
        <?php
            $flashes = Yii::$app->session->getAllFlashes();

            // If there are any flashes present, display them
            // Types are:
            // success (green), info (blue), warning (yellow), danger (red)
                    
            if ($flashes) {
                foreach ($flashes as $type => $message) {
                    echo '
                    <div class="alert alert-' . $type . ' alert-dismissible fade in">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>' .
                        Yii::$app->session->getFlash($type) . '
                    </div>';
                }
                $this::registerJs('setTimeout(function() { $(".alert").alert("close"); }, 4000);');
            }
        ?>
        
        <div class="wrap">
            <?php

                NavBar::begin([
                    'brandLabel' => Yii::$app->name,
                    'brandUrl' => Yii::$app->homeUrl,
                    'options' => [
                        'class' => 'navbar-inverse navbar-fixed-top',
                    ]
                ]);
                
                // User is Guest
                if (Yii::$app->user->isGuest) {
                    $items = [
                        ['label' => 'Home', 'url' => ['/site/index']],
                        //['label' => 'About', 'url' => ['/site/about']],
                        //['label' => 'Contact', 'url' => ['/site/contact']],
                        ['label' => 'Login', 'url' => ['/site/login']]
                    ];
                }
                // User is Logged In
                else {
                    $items = [
                        ['label' => 'Home', 'url' => ['/site/index']],
                        //['label' => 'About', 'url' => ['/site/about']],
                        //['label' => 'Contact', 'url' => ['/site/contact']],
                        [
                            'label' => 'Backcountry Ski App',
                            'items' => [
                                '<li class="dropdown-header">Ski Info</li>',
                                ['label' => 'Ski Areas', 'url' => ['/skiareas/index']],
                                ['label' => 'Ski Routes', 'url' => ['/skiroutes/index']],
                                '<li class="divider"></li>',
                                '<li class="dropdown-header">General Information</li>',
                                ['label' => 'Glossary', 'url' => ['/glossary/index']]
                            ],
                            'activateParents' => true
                            //'visible' => !Yii::$app->user->isGuest
                        ],
                        ['label' => 'Account', 'url' => ['/user/account']],
                        ['label' => 'Users', 'url' => ['/user/index'], 'visible' => Yii::$app->user->identity->usertype === User::USERTYPE_ADMIN],
                        ['label' => 'Admin', 'url' => ['/admin/index'], 'visible' => Yii::$app->user->identity->usertype === User::USERTYPE_ADMIN],
                        ['label' => 'Logout (' . Yii::$app->user->identity->username . ')','url' => ['/site/logout'],'linkOptions' => ['data-method' => 'post']]
                    ];
                }
                
                echo Nav::widget([
                    'options' => ['class' => 'navbar-nav navbar-right'],
                    'items' => $items
                ]);

                NavBar::end();

            ?>

            <div class="container">
                <?= Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []
                ]) ?>

                <?= $content ?>
            </div>
        </div>

        <footer class="footer">
            <div class="container">
                <p class="pull-left">&copy; Gneiss Software <?= date('Y') ?></p>
            </div>
        </footer>

    <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>