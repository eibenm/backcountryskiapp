<?php

namespace app\controllers;

use Yii;
use app\models\User;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\filters\AccessControl;
use yii\helpers\Json;

/**
 * UserController implements the CRUD actions for User model.
 */
class AdminController extends Controller
{
    /**
     * @var array Actions that are restricted to admin permissions
     */
    private $adminActions = [
        'index',
        'export',
        'download',
        'make-live',
        'get-data-json-version'
    ];

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
                            'export',
                            'download',
                            'make-live',
                            'get-data-json-version'
                        ],
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            if (in_array($action->id, $this->adminActions)) {
                                return Yii::$app->user->identity->usertype === User::USERTYPE_ADMIN;
                            }
                            return true;
                        },
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    if (in_array($action->id, $this->adminActions)) {
                        throw new ForbiddenHttpException('User must be logged in and have ADMIN permissions to access this page.');
                    }
                    throw new ForbiddenHttpException('You are not allowed to perform this action.');
                }
            ]
        ];
    }

    public function actionIndex()
    {
        $versionDataProvider = (new \app\models\search\VersionSearch())->search(Yii::$app->request->queryParams);

        $fileExists = file_exists(Yii::getAlias('@app/data/skiappdata.json'));
        
        $lastRecord = \app\models\Version::find()->orderBy(['id' => SORT_DESC])->one();
        
        $currentVersionID = $lastRecord ? $lastRecord->id : null;

        return $this->render('index', [
            'fileExists' => $fileExists,
            'versionDataProvider' => $versionDataProvider,
            'currentVersionID' => $currentVersionID
        ]);
    }

    /**
     * Export DB Schema to JSON file
     * @return mixed
     */
    public function actionExport()
    {
        if (self::export()) {
            Yii::$app->session->setFlash('success', 'File written successfully.');
            return $this->redirect(['admin/index']);
        }

        Yii::$app->session->setFlash('danger', 'There was an error writing the file.');
        return $this->redirect(['admin/index']);
    }

    public function actionDownload()
    {
        $filepath = Yii::getAlias('@app/data/skiappdata.json');

        if (file_exists($filepath)) {
            return Yii::$app->response->sendFile($filepath, basename($filepath));
        }

        Yii::$app->session->setFlash('danger', 'File ' . $filepath . ' does not exist');
        return $this->redirect(['admin/index']);
    }

    public function actionMakeLive()
    {
        $filepath = Yii::getAlias('@app/data/skiappdata.json');
        $livepath = Yii::getAlias('@app/skimontanadata/skiappdata.json');

        if (!file_exists($filepath)) {
            Yii::$app->session->setFlash('info', 'Please export DB to json file before making live.');
            return $this->redirect(['admin/index']);
        }

        if (file_exists($livepath)) {
            unlink($livepath);
        }

        if (!copy($filepath, $livepath)) {
            Yii::$app->session->setFlash('danger', 'Something went wrong making the file live.');
            return $this->redirect(['admin/index']);
        }
        
        $lastVersion = \app\models\Version::find()->orderBy(['id' => SORT_DESC])->one();
        $lastVersion->live = 1;
        $lastVersion->save();
        
        $oldLiveVersions = \app\models\Version::find()->where('live = 1 AND id != :id', [
            ':id' => $lastVersion->id
        ])->all();
        
        foreach ($oldLiveVersions as $version) {
            $version->live = 0;
            $version->save();
        }
            
        Yii::$app->session->setFlash('success', 'Most recent file is live.');
        return $this->redirect(['admin/index']);
    }
    
    public function actionGetDataJsonVersion()
    {
        $filepath = Yii::getAlias('@app/data/skiappdata.json');
        
        if (file_exists($filepath)) {
            $fileJson = json_decode(file_get_contents($filepath));
            echo Json::encode($fileJson->version);
        }
        else {
            echo Json::encode(false);
        }
    }
    
    public static function export()
    {
        $skiAreas = \app\models\Skiareas::find()->all();
        $glossaries = \app\models\Glossary::find()->all();

        $version = \app\models\Version::find()->orderBy(['id' => SORT_DESC])->one();

        $data = [
            'version' => $version->version,
            'skiAreas' => [],
            'glossary' => []
        ];

        foreach ($skiAreas as $skiArea) {
            $area = [
                'name_area' => $skiArea->name_area,
                'conditions' => $skiArea->conditions,
                'color' => $skiArea->color,
                'bounds_southwest' => $skiArea->bounds_southwest,
                'bounds_northeast' => $skiArea->bounds_northeast,
                'permissions' => $skiArea->permissions,
                'skiarea_image' => $skiArea->image ? [
                    'filename' => $skiArea->image->filename,
                    'avatar' => $skiArea->image->avatar
                ] : null,
                'skiarea_routes' => []
            ];

            foreach ($skiArea->skiroutes as $skiRoute) {
                $route = [
                    'name_route' => $skiRoute->name_route,
                    'quip' => $skiRoute->quip,
                    'overview' => $skiRoute->overview,
                    'short_desc' => $skiRoute->short_desc,
                    'notes' => $skiRoute->notes,
                    'avalanche_info' => $skiRoute->avalanche_info,
                    'directions' => $skiRoute->directions,
                    'gps_guidance' => $skiRoute->gps_guidance,
                    'elevation_gain' => $skiRoute->elevation_gain,
                    'vertical' => $skiRoute->vertical,
                    'aspects' => $skiRoute->aspects,
                    'distance' => $skiRoute->distance,
                    'snowfall' => $skiRoute->snowfall,
                    'avalanche_danger' => $skiRoute->avalanche_danger,
                    'skier_traffic' => $skiRoute->skier_traffic,
                    'kml' => $skiRoute->kml,
                    'bounds_southwest' => $skiRoute->bounds_southwest,
                    'bounds_northeast' => $skiRoute->bounds_northeast,
                    'mbtiles' => $skiRoute->mbtiles,
                    'skiroute_gps' => [],
                    'skiroute_images' => []
                ];

                foreach ($skiRoute->gps as $gps) {
                    $route['skiroute_gps'][] = [
                        'waypoint' => $gps->waypoint,
                        'lat' => $gps->lat,
                        'lon' => $gps->lon,
                        'lat_dms' => $gps->lat_dms,
                        'lon_dms' => $gps->lon_dms
                    ];
                }

                foreach ($skiRoute->skiroutesImages as $image) {
                    $route['skiroute_images'][] = $image->image ? [
                        'filename' => $image->image->filename,
                        'avatar' => $image->image->avatar,
                        'caption' => $image->image->caption,
                        'kml_image' => (int)$image->image->kml_image
                    ] : null;
                }

                $area['skiarea_routes'][] = $route;
            }

            $data['skiAreas'][] = $area;
        }

        foreach ($glossaries as $gloassary) {
            $entry = [
                'term' => $gloassary->term,
                'description' => $gloassary->description
            ];

            $data['glossary'][] = $entry;
        }

        $filepath = Yii::getAlias('@app/data/skiappdata.json');

        if (file_exists($filepath)) {
            unlink($filepath);
        }

        $printOptions = YII_ENV_DEV ? (JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : JSON_UNESCAPED_UNICODE;
        
        if (file_put_contents($filepath, json_encode($data, $printOptions))) {
            return true;
        }
    }
}
