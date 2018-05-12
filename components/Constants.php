<?php

namespace app\components;

use Yii;
use yii\base\Component;

/**
 * The Constants class is used to store globally required constants.
 *
 * Constants is configured as an application component in [[\app\components]] by default.
 * You can access that instance via `Yii::$app->constants`.
 *
 * @property string $mapboxAccessToken The access token for MapBox account
 * @property integer $pjaxTimeout Asynch timeout before tables refresh via post
 *
 * @author Matt Eiben <eibenm@gmail.com>
 * @since 2.0
 */
class Constants extends Component
{
    private $_mapboxAccessToken;
    private $_pjaxTimeout;
    private $_googleApiKey;

    public function init()
    {
        parent::init();
        $this->_mapboxAccessToken = '';
        $this->_pjaxTimeout = 5000;
        $this->_googleApiKey = 'AIzaSyD2VRI5OGSgNUSsNzm_EEyr1O22IigLI2E';
    }
    
    public function getMapboxAccessToken()
    {
        return $this->_mapboxAccessToken;
    }
    
    public function getPjaxTimeout()
    {
        return $this->_pjaxTimeout;
    }

    public function getGoogleApiKey()
    {
        return $this->_googleApiKey;
    }
}