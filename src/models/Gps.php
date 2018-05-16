<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "gps".
 *
 * @property integer $id
 * @property string $waypoint
 * @property double $lat
 * @property double $lon
 * @property double $lat_dms
 * @property double $lon_dms
 * @property integer $route_id
 *
 * @property Skiroutes $skiroute
 */
class Gps extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gps';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['waypoint', 'lat_dms', 'lon_dms'], 'string'],
            [['lat', 'lon'], 'number'],
            [['route_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'waypoint' => 'Waypoint',
            'lat' => 'Lat',
            'lon' => 'Lon',
            'lat_dms' => 'Lat DMS',
            'lon_dms' => 'Lon DMS',
            'route_id' => 'Route ID',
        ];
    }
    
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $post = Yii::$app->request->post('Gps');
            if ($post) {
                $lat_dms = $post['lat_dms'];
                $lon_dms = $post['lon_dms'];
                preg_match('/(\d{1,2})[^\d]+?(\d{1,2})[^\d]+?(\d{1,2}\.\d+?)/', $lat_dms, $latdms_match);
                preg_match('/(\d{1,3})[^\d]+?(\d{1,2})[^\d]+?(\d{1,2}\.\d+?)/', $lon_dms, $londms_match);
                if ($latdms_match) {
                    $this->lat = strval(round(floatval($latdms_match[1]) + (floatval($latdms_match[2]) / 60.0) + (floatval($latdms_match[3]) / 3600.0), 6));
                }
                if ($londms_match) {
                    $this->lon = '-' . strval(round(floatval($londms_match[1]) + (floatval($londms_match[2]) / 60.0) + (floatval($londms_match[3]) / 3600.0), 6));
                }
            }
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSkiroute()
    {
        return $this->hasOne(Skiroutes::className(), ['id' => 'route_id']);
    }
}
