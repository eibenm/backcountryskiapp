<?php

namespace app\controllers;

use Yii;
use app\models\Skiroutes;
use app\models\Skiareas;
use app\models\search\SkiroutesSearch;
use app\models\SkiroutesImage;
use app\models\File;
use app\models\Gps;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;


/**
 * SkiroutesController implements the CRUD actions for Skiroutes model.
 */
class SkiroutesController extends Controller
{
    const COLUMNS_TO_SHOW = 'ski_area_columnsToShow';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'index',
                            'view',
                            'create',
                            'update',
                            'delete',
                            'download-kml',
                            'view-map',
                            'view-all-map',
                            'download-all-map',
                            'download-kmz'
                        ],
                        'roles' => ['@'],
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post']
                ]
            ]
        ];
    }

    /**
     * Lists all Skiroutes models.
     * @return string|\yii\web\Response
     */
    public function actionIndex()
    {
        $searchModel = new SkiroutesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $columnsToShow = [
            'name_route',
            'elevation_gain',
            'vertical',
            'distance'
        ];

        $columnsToShowRequest = Yii::$app->request->get('columnsToShow');

        if ($columnsToShowRequest) {
            $columnsToShow = array_keys($columnsToShowRequest);
            Yii::$app->session[self::COLUMNS_TO_SHOW] = $columnsToShow;
        } else if (Yii::$app->session[self::COLUMNS_TO_SHOW]) {
            $columnsToShow = Yii::$app->session[self::COLUMNS_TO_SHOW];
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'columnsToShow' => $columnsToShow
        ]);
    }

    /**
     * Displays a single Skiroutes model.
     * @param integer $id
     * @return string|\yii\web\Response
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Skiroutes model.
     * If creation is successful, the browser will be redirected to the 'update' page.
     * @param integer $area_id
     * @return string|\yii\web\Response
     */
    public function actionCreate($area_id)
    {
        $model = new Skiroutes();
        $model->skiarea_id = $area_id;

        if ($model->load(Yii::$app->request->post())) {
            $model->kml = UploadedFile::getInstance($model, 'kml');
            if ($model->kml && $model->validate()) {
                $model->kml->saveAs(Yii::$app->params['uploadPath'] . $model->kml->baseName . '.' . $model->kml->extension);
            }
            if ($model->save()) {
                return $this->redirect(['update', 'id' => $model->id]);
            }
        }
        return $this->render('create', [
            'model' => $model
        ]);
    }

    /**
     * Updates an existing Skiroutes model.
     * If update is successful, the browser will be redirected to the 'update' page.
     * @param integer $id
     * @return string|\yii\web\Response
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $image = new File();
        $gps = new Gps();
        $newKml = UploadedFile::getInstance($model, 'kml');
        $oldKml = $model->kml;

        // Delete KML if new one is uploaded
        if ($newKml) {
            if ($model->kml) {
                $filePath = Yii::$app->params['uploadPath'] . $model->kml;
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
        }

        if (Yii::$app->request->post('Skiroutes')) {
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                if ($newKml) {
                    $model->kml = $newKml;
                    $model->kml->saveAs(Yii::$app->params['uploadPath'] . $model->kml->baseName . '.' . $model->kml->extension);
                }
                if (!$newKml && $oldKml) {
                    $model->kml = $oldKml;
                }
                if ($model->save()) {
                    return $this->redirect(['update', 'id' => $model->id]);
                }
            }
        }

        if (Yii::$app->request->post('Gps')) {
            $gps->load(Yii::$app->request->post());
            $gps->route_id = $model->id;
            if($gps->save()) {
                return $this->redirect(['update', 'id' => $model->id, 'gpsID' => '']);
            }
        }

        if (Yii::$app->request->post('File')) {
            if ($image->load(Yii::$app->request->post())) {
                $image->file = UploadedFile::getInstance($image, 'file');
                if ($image->file) {
                    if ($image->uploadImage()) {
                        $routeImage = new SkiroutesImage();
                        $routeImage->route_id = $model->id;
                        $routeImage->image_id = $image->id;
                        $routeImage->save();
                        return $this->redirect(['update', 'id' => $id, 'photoID' => '']);
                    }
                }
                Yii::$app->session->setFlash('danger', 'A image must be uploaded.');
                return $this->redirect(['update', 'id' => $model->id, 'photoID' => '']);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'image' => $image,
            'gps' => $gps
        ]);
    }

    /**
     * Deletes an existing Skiroutes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return \yii\web\Response
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        foreach ($model->gps as $gps) {
            $gps->delete();
        }

        foreach ($model->skiroutesImages as $image) {
            $image->delete();
            $image->image->delete();
            $image->image->deleteCurrentImage();
        }

        if ($model->kml) {
            $filePath = Yii::$app->params['uploadPath'] . $model->kml;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Download KML for route
     * @param integer $id
     * @return \yii\web\Response
     */
    public function actionDownloadKml($id)
    {
        $model = $this->findModel($id);
        $fullpath = Yii::$app->params['uploadPath'] . $model->kml;
        return Yii::$app->response->sendFile($fullpath);
    }

    /**
     * Download kmz for the route
     * @param integer $id
     * @return \yii\web\Response
     */
    public function actionDownloadKmz($id)
    {
        $model = $this->findModel($id);

        $skiRouteGpsKml = '';
        foreach ($model->gps as $gps) {
            $skiRouteGpsKml .= <<<XML
        <Placemark>
            <name>$gps->waypoint</name>
            <Snippet maxLines="1">{$gps->skiroute->name_route}</Snippet>
            <description>
                <![CDATA[
                <html>
                    <head>
                        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
                    </head>
                    <body style="margin:0px 0px 0px 0px;overflow:auto;background:#FFFFFF;" cellpadding="10">
                        <table style="font-family:Arial,Verdana,Times;font-size:12px;text-align:left;width:100%;border-spacing:0px; padding:3px 3px 3px 3px">
                            <tr style="text-align:center;font-weight:bold;background:#9CBCE2">
                                <td colspan="2">{$gps->waypoint}</td>
                            </tr>
                            <tr>
                                <td style="padding:4px; white-space:nowrap;">Ski Area:</td>
                                <td style="padding:4px; white-space:nowrap;">{$gps->skiroute->skiarea->name_area}</td>
                            </tr>
                            <tr bgcolor="#D4E4F3">
                                <td style="padding:4px; white-space:nowrap;">Ski Route:</td>
                                <td style="padding:4px; white-space:nowrap;">{$gps->skiroute->name_route}</td>
                            </tr>
                            <tr>
                                <td style="padding:4px; white-space:nowrap;">Coordinates (DD):</td>
                                <td style="padding:4px; white-space:nowrap;">{$gps->lat}, {$gps->lon}</td>
                            </tr>
                            <tr bgcolor="#D4E4F3">
                                <td style="padding:4px; white-space:nowrap;">Coordinates (DMS):</td>
                                <td style="padding:4px; white-space:nowrap;">{$gps->lat_dms}, {$gps->lon_dms}</td>
                            </tr>
                        </table>
                    </body>
                </html>
                ]]>
            </description>
            <styleUrl>#SkiRouteStyleMap</styleUrl>
            <Point>
                <altitudeMode>clampToGround</altitudeMode>
                <coordinates>{$gps->lon},{$gps->lat},0</coordinates>
            </Point>
        </Placemark>
XML;
        }

        $kml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<kml xmlns="http://www.opengis.net/kml/2.2" xmlns:gx="http://www.google.com/kml/ext/2.2" xmlns:kml="http://www.opengis.net/kml/2.2" xmlns:atom="http://www.w3.org/2005/Atom">
    <Document>

        <!-- Maker Styles -->
        <StyleMap id="SkiRouteStyleMap">
            <Pair>
                <key>normal</key>
                <styleUrl>#SkiRouteStyle</styleUrl>
            </Pair>
            <Pair>
                <key>highlight</key>
                <styleUrl>#SkiRouteStyle_h</styleUrl>
            </Pair>
        </StyleMap>
        <Style id="SkiRouteStyle">
            <IconStyle>
                <scale>1.1</scale>
                <Icon>
                    <href>skiicon.png</href>
                </Icon>
            </IconStyle>
            <LabelStyle>
                <scale>0</scale>
            </LabelStyle>
        </Style>
        <Style id="SkiRouteStyle_h">
            <IconStyle>
                <scale>1.4</scale>
                <Icon>
                    <href>skiicon.png</href>
                </Icon>
            </IconStyle>
            <LabelStyle>
                <scale>1.0</scale>
            </LabelStyle>
            <BalloonStyle>
                <!-- styling of the balloon text -->
                <text><![CDATA[
                    <b><font size="+1">Route: $[Snippet]</font></b>
                    <br/><br/>
                    <font face="Courier">$[description]</font>
                ]]></text>
            </BalloonStyle>
        </Style>

        <name>{$model->name_route}</name>
        {$skiRouteGpsKml}
    </Document>
</kml>
XML;

        $webdir = Yii::$app->basePath . DIRECTORY_SEPARATOR . 'web' . DIRECTORY_SEPARATOR;
        $filename = $webdir . 'downloads' . DIRECTORY_SEPARATOR . $model->name_route . '.kmz';

        if (file_exists($filename)) {
            unlink($filename);
        }

        $zip = new \ZipArchive();
        $zip->open($filename, \ZipArchive::CREATE);
        $zip->addFromString('doc.kml', $kml);
        $zip->addFile($webdir . 'skiicon.png', 'skiicon.png');
        $zip->close();

        Yii::$app->response->sendFile($filename, basename($filename), ['mimeType' => 'application/vnd.google-earth.kmz']);
        Yii::$app->end();
    }

    /**
     * View map for a route
     * @param integer $id
     * @return string|\yii\web\Response
     */
    public function actionViewMap($id)
    {
        $model = $this->findModel($id);

        $gpsdata = array_map(function($data) {
            return [
                'waypoint' => $data->waypoint,
                'lat' => $data->lat,
                'lon' => $data->lon
            ];
        }, $model->gps);

        return $this->renderPartial('//maps/maproutes', [
            'gpsdata' => $gpsdata,
            'routename' => $model->name_route
        ]);
    }

    /**
     * View map with all routes
     * @return string|\yii\web\Response
     */
    public function actionViewAllMap()
    {
        $gpsdataArray = [];

        foreach (Skiroutes::find()->with('gps')->orderBy('name_route')->each() as $model) {
            $northeastBounds = explode(',', $model->bounds_northeast);
            $southwestBounds = explode(',', $model->bounds_southwest);
            $gpsdataArray[$model['name_route']] = [
                'gps' => array_map(function($data) {
                    return [
                        'waypoint' => $data->waypoint,
                        'lat' => $data->lat,
                        'lon' => $data->lon
                    ];
                }, $model->gps),
                'bounds' => [
                    'northeast' => [
                        'lat' => (isset($northeastBounds[0]) ? (float)trim($northeastBounds[0]) : ''),
                        'lon' => (isset($northeastBounds[1]) ? (float)trim($northeastBounds[1]) : '')
                    ],
                    'southwest' => [
                        'lat' => (isset($southwestBounds[0]) ? (float)trim($southwestBounds[0]) : ''),
                        'lon' => (isset($southwestBounds[1]) ? (float)trim($southwestBounds[1]) : '')
                    ]
                ]
            ];
        }

        return $this->renderPartial('//maps/mapallroutes', [
            'gpsdataArray' => $gpsdataArray
        ]);
    }

    /**
     * Download KMZ of all routes
     */
    public function actionDownloadAllMap()
    { // ['name_area' => SORT_DESC, 'skiroutes.name_route' => SORT_ASC]
        $skiAreaKml = '';
        $skiAreas = Skiareas::find()
            ->orderBy(['name_area' => SORT_ASC/*, 'skiroutes.name_route' => SORT_ASC*/])
            ->each();
        foreach ($skiAreas as $skiarea) {
            $skiRouteKml = '';
            foreach ($skiarea->skiroutesSorted as $skiroute) {
                $skiRouteGpsKml = '';
                foreach ($skiroute->gps as $gps) {
                    $skiRouteGpsKml .= <<<XML
                <Placemark>
                    <name>{$gps->waypoint}</name>
                    <Snippet maxLines="1">{$gps->skiroute->name_route}</Snippet>
                    <description>
                        <![CDATA[
                        <html>
                            <head>
                                <meta http-equiv="content-type" content="text/html; charset=UTF-8">
                            </head>
                            <body style="margin:0px 0px 0px 0px;overflow:auto;background:#FFFFFF;" cellpadding="10">
                                <table style="font-family:Arial,Verdana,Times;font-size:12px;text-align:left;width:100%;border-spacing:0px; padding:3px 3px 3px 3px">
                                    <tr style="text-align:center;font-weight:bold;background:#9CBCE2">
                                        <td colspan="2">{$gps->waypoint}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding:4px; white-space:nowrap;">Ski Area:</td>
                                        <td style="padding:4px; white-space:nowrap;">{$gps->skiroute->skiarea->name_area}</td>
                                    </tr>
                                    <tr bgcolor="#D4E4F3">
                                        <td style="padding:4px; white-space:nowrap;">Ski Route:</td>
                                        <td style="padding:4px; white-space:nowrap;">{$gps->skiroute->name_route}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding:4px; white-space:nowrap;">Coordinates (DD):</td>
                                        <td style="padding:4px; white-space:nowrap;">{$gps->lat}, {$gps->lon}</td>
                                    </tr>
                                    <tr bgcolor="#D4E4F3">
                                        <td style="padding:4px; white-space:nowrap;">Coordinates (DMS):</td>
                                        <td style="padding:4px; white-space:nowrap;">{$gps->lat_dms}, {$gps->lon_dms}</td>
                                    </tr>
                                </table>
                            </body>
                        </html>
                        ]]>
                    </description>
                    <styleUrl>#SkiRouteStyleMap</styleUrl>
                    <Point>
                        <altitudeMode>clampToGround</altitudeMode>
                        <coordinates>{$gps->lon},{$gps->lat},0</coordinates>
                    </Point>
                </Placemark>
XML;
                }

                $northeastBounds = explode(',', $skiroute->bounds_northeast);
                $southwestBounds = explode(',', $skiroute->bounds_southwest);
                $northeastBoundsLat = (float)trim($northeastBounds[0]);
                $northeastBoundsLon = (float)trim($northeastBounds[1]);
                $southwestBoundsLat = (float)trim($southwestBounds[0]);
                $southwestBoundsLon = (float)trim($southwestBounds[1]);

                $latDiff = $northeastBoundsLat - $southwestBoundsLat;

                $polyString = $northeastBoundsLon . ',' . $northeastBoundsLat . ',0 ';
                $polyString .= $northeastBoundsLon . ',' . ($northeastBoundsLat - $latDiff) . ',0 ';
                $polyString .= $southwestBoundsLon . ',' . $southwestBoundsLat . ',0 ';
                $polyString .= $southwestBoundsLon . ',' .  ($southwestBoundsLat + $latDiff) . ',0 ';
                $polyString .= $northeastBoundsLon . ',' . $northeastBoundsLat . ',0 ';

                $skiRouteKml .= <<<XML
            <Folder>
                <name>$skiroute->name_route</name>
                $skiRouteGpsKml
                <Placemark>
                    <name>Bounds</name>
                    <styleUrl>#boundsStyle</styleUrl>
                    <Polygon>
                        <altitudeMode>clampToGround</altitudeMode>
                        <outerBoundaryIs>
                            <LinearRing>
                                <coordinates>
                                    $polyString
                                </coordinates>
                            </LinearRing>
                        </outerBoundaryIs>
                    </Polygon>
                </Placemark>
            </Folder>
XML;
            }
            $skiAreaKml .= <<<XML
        <Folder>
            <name>$skiarea->name_area</name>
            $skiRouteKml
        </Folder>
XML;
        }

        $kml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<kml xmlns="http://www.opengis.net/kml/2.2" xmlns:gx="http://www.google.com/kml/ext/2.2" xmlns:kml="http://www.opengis.net/kml/2.2" xmlns:atom="http://www.w3.org/2005/Atom">
    <Document>

        <!-- Maker Styles -->
        <StyleMap id="SkiRouteStyleMap">
            <Pair>
                <key>normal</key>
                <styleUrl>#SkiRouteStyle</styleUrl>
            </Pair>
            <Pair>
                <key>highlight</key>
                <styleUrl>#SkiRouteStyle_h</styleUrl>
            </Pair>
        </StyleMap>
        <Style id="SkiRouteStyle">
            <IconStyle>
                <scale>1.1</scale>
                <Icon>
                    <href>skiicon.png</href>
                </Icon>
            </IconStyle>
            <LabelStyle>
                <scale>0</scale>
            </LabelStyle>
        </Style>
        <Style id="SkiRouteStyle_h">
            <IconStyle>
                <scale>1.4</scale>
                <Icon>
                    <href>skiicon.png</href>
                </Icon>
            </IconStyle>
            <LabelStyle>
                <scale>1.0</scale>
            </LabelStyle>
            <BalloonStyle>
                <!-- styling of the balloon text -->
                <text><![CDATA[
                    <b><font size="+1">Route: $[Snippet]</font></b>
                    <br/><br/>
                    <font face="Courier">$[description]</font>
                ]]></text>
            </BalloonStyle>
        </Style>
        <Style id="boundsStyle">
            <LineStyle>
                <width>1.5</width>
            </LineStyle>
            <PolyStyle>
                <color>7dff0000</color>
            </PolyStyle>
        </Style>

        <name>Ski Bozeman Routes</name>
        $skiAreaKml
    </Document>
</kml>
XML;

        $webdir = Yii::$app->basePath . DIRECTORY_SEPARATOR . 'web' . DIRECTORY_SEPARATOR;
        $filename = $webdir . 'downloads' . DIRECTORY_SEPARATOR . 'Ski Bozeman Routes.kmz';

        if (file_exists($filename)) {
            unlink($filename);
        }

        $zip = new \ZipArchive();
        $zip->open($filename, \ZipArchive::CREATE);
        $zip->addFromString('doc.kml', $kml);
        $zip->addFile($webdir . 'skiicon.png', 'skiicon.png');
        $zip->close();

        Yii::$app->response->sendFile($filename, basename($filename), ['mimeType' => 'application/vnd.google-earth.kmz']);
        Yii::$app->end();
    }

    /**
     * Finds the Skiroutes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Skiroutes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Skiroutes::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
