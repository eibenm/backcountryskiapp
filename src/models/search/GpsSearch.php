<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Gps;

/**
 * GpsSearch represents the model behind the search form about `app\models\Gps`.
 */
class GpsSearch extends Gps
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'route_id'], 'integer'],
            [['waypoint', 'lat_dms', 'lon_dms'], 'safe'],
            [['lat', 'lon'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $routeid)
    {
        $query = Gps::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'lat' => $this->lat,
            'lon' => $this->lon,
            'route_id' => $this->route_id,
        ]);

        $query->andFilterWhere(['like', 'waypoint', $this->waypoint])
            ->andFilterWhere(['like', 'lat_dms', $this->lat_dms])
            ->andFilterWhere(['like', 'lon_dms', $this->lon_dms]);
        
        $query->andWhere(['route_id' => $routeid]);

        return $dataProvider;
    }
}
