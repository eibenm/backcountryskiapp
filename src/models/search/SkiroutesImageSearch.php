<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SkiroutesImage;

/**
 * SkiroutesImageSearch represents the model behind the search form about `app\models\SkiroutesImage`.
 */
class SkiroutesImageSearch extends SkiroutesImage
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'route_id', 'image_id'], 'integer'],
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
        $query = SkiroutesImage::find();

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
            'route_id' => $this->route_id,
            'image_id' => $this->image_id,
        ]);
        
        $query->andWhere(['route_id' => $routeid]);

        return $dataProvider;
    }
}
