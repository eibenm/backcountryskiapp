<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Skiroutes;

/**
 * SkiroutesSearch represents the model behind the search form about `app\models\Skiroutes`.
 */
class SkiroutesSearch extends Skiroutes
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'elevation_gain', 'skiarea_id'], 'integer'],
            [['name_route', 'quip', 'overview', 'short_desc', 'notes', 'avalanche_info', 'directions', 'gps_guidance', 'vertical', 'aspects', 'snowfall', 'avalanche_danger', 'skier_traffic', 'bounds_southwest', 'bounds_northeast', 'mbtiles', 'kml'], 'safe'],
            [['distance'], 'number'],
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
    public function search($params)
    {
        $query = Skiroutes::find();

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
            'elevation_gain' => $this->elevation_gain,
            'distance' => $this->distance,
            'skiarea_id' => $this->skiarea_id
        ]);

        $query->andFilterWhere(['like', 'name_route', $this->name_route])
            ->andFilterWhere(['like', 'quip', $this->quip])
            ->andFilterWhere(['like', 'overview', $this->overview])
            ->andFilterWhere(['like', 'short_desc', $this->short_desc])
            ->andFilterWhere(['like', 'notes', $this->notes])
            ->andFilterWhere(['like', 'avalanche_info', $this->avalanche_info])
            ->andFilterWhere(['like', 'directions', $this->directions])
            ->andFilterWhere(['like', 'gps_guidance', $this->gps_guidance])
            ->andFilterWhere(['like', 'vertical', $this->vertical])
            ->andFilterWhere(['like', 'aspects', $this->aspects])
            ->andFilterWhere(['like', 'snowfall', $this->snowfall])
            ->andFilterWhere(['like', 'avalanche_danger', $this->avalanche_danger])
            ->andFilterWhere(['like', 'skier_traffic', $this->skier_traffic])
            ->andFilterWhere(['like', 'bounds_southwest', $this->bounds_southwest])
            ->andFilterWhere(['like', 'bounds_northeast', $this->bounds_northeast])
            ->andFilterWhere(['like', 'mbtiles', $this->mbtiles])
            ->andFilterWhere(['like', 'kml', $this->kml]);

        return $dataProvider;
    }
}
