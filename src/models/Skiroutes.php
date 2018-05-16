<?php

namespace app\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "skiroutes".
 *
 * @property integer $id
 * @property string $name_route
 * @property string $quip
 * @property string $overview
 * @property string $short_desc
 * @property string $notes
 * @property string $avalanche_info
 * @property string $directions
 * @property string $gps_guidance
 * @property integer $elevation_gain
 * @property string $vertical
 * @property string $aspects
 * @property double $distance
 * @property string $snowfall
 * @property string $avalanche_danger
 * @property string $skier_traffic
 * @property string $kml
 * @property string $bounds_southwest
 * @property string $bounds_northeast
 * @property string $mbtiles
 * @property integer $skiarea_id
 *
 * @property Gps[] $gps
 * @property Skiareas $skiarea
 * @property SkiroutesImage[] $skiroutesImages
 */
class Skiroutes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'skiroutes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name_route', 'quip', 'overview', 'short_desc', 'notes', 'avalanche_info', 'directions', 'gps_guidance', 'vertical', 'aspects', 'snowfall', 'avalanche_danger', 'skier_traffic', 'bounds_southwest', 'bounds_northeast', 'mbtiles'], 'string'],
            [['bounds_southwest', 'bounds_northeast'], 'match', 'pattern' => '/[0-9]{2}\.[0-9]{3,},\s?\-[0-9]{3}\.[0-9]{3,}/', 'message' => '{attribute} must match the example format: 40.45123, -120.45124'],
            [['bounds_southwest', 'bounds_northeast'], 'filter', 'filter' => 'trim'],
            [['mbtiles'], 'match', 'pattern' => '/^.+?\.mbtiles$/', 'message' => '{attribute} must be in the exact format "mytiles.mbtiles"'],
            [['elevation_gain', 'skiarea_id'], 'integer'],
            [['distance'], 'number'],
            [['kml'], 'file']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'quip' => 'Quip',
            'name_route' => 'Name Route',
            'overview' => 'Overview',
            'short_desc' => 'Short Desc',
            'notes' => 'Notes',
            'avalanche_info' => 'Avalanche Info',
            'directions' => 'Directions',
            'gps_guidance' => 'Waypoint Guidance',
            'elevation_gain' => 'Elevation Gain',
            'vertical' => 'Vertical',
            'aspects' => 'Aspects',
            'distance' => 'Distance',
            'snowfall' => 'Snowfall',
            'avalanche_danger' => 'Terrain Danger',
            'skier_traffic' => 'Skier Traffic',
            'kml' => 'Kml',
            'bounds_southwest' => 'Southwest Bounds',
            'bounds_northeast' => 'Northeast Bounds',
            'mbtiles' => 'mbtiles',
            'skiarea_id' => 'Skiarea'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGps()
    {
        return $this->hasMany(Gps::className(), ['route_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSkiarea()
    {
        return $this->hasOne(Skiareas::className(), ['id' => 'skiarea_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSkiroutesImages()
    {
        return $this->hasMany(SkiroutesImage::className(), ['route_id' => 'id']);
    }
    
    //---------------------- General Functions ---------------------------------
    
    
    public static function dropdown()
    {
        return ArrayHelper::map(Skiareas::find()->all(), 'id', 'name_area');
    }
    
    public static function skiareas() {
        return $skiareas = Skiareas::find()
            ->orderBy('name_area')
            ->all();
    }
    
    /**
     * @param model $data - Instance of Skiroutes model
     * @return string $retval - Bootstrap table
     */
    public function waypoints($data)
    {
        if ($data->gps) {
            $retval = '<div class="table-responsive">';
            $retval .= '<table class="table table-bordered table-condensed">';
            $retval .= '<tr class="info">'
                . '<th>Waypoint</th>'
                . '<th>Lat</th>'
                . '<th>Lon</th>'
                . '<th>Lat (DMS)</th>'
                . '<th>Lon (DMS)</th>'
                . '</tr>';
            foreach ($data->gps as $gps) {
                $retval .= "<tr class='warning'>"
                    . "<td>$gps->waypoint</td>"
                    . "<td>$gps->lat</td>"
                    . "<td>$gps->lon</td>"
                    . "<td>$gps->lat_dms</td>"
                    . "<td>$gps->lon_dms</td>"
                    . '</tr>';
            }
            $retval .= '</table>';
            $retval .= '</div>';
            return $retval;
        }
    }
    
    public function viewimages($data)
    {
        if ($data->skiroutesImages) {
            $retval = '';
            foreach ($data->skiroutesImages as $image) {
                if (isset($image->image)) {
                    $retval .= '<div style="margin: 10px; display: inline-block;"> '
                        . Html::img('@web/images/' . $image->image->avatar, ['height' => '100px'])
                        . Html::a('<span class="glyphicon glyphicon-download-alt margin-left_10" aria-hidden="true"></span>', ['skiroutes-image/download', 'id' => $image->id])
                        . '</div>';
                }
            }
            return $retval;
        }
    }
}
