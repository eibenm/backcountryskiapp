<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Skiareas;

/**
 * SkiareasSearch represents the model behind the search form about `app\models\Skiareas`.
 */
class SkiareasSearch extends Skiareas
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'image_id', 'permissions'], 'integer'],
            [['name_area', 'conditions', 'color', 'bounds_southwest', 'bounds_northeast'], 'safe'],
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
        $query = Skiareas::find();

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
            'image_id' => $this->image_id,
            'permissions' => $this->permissions,
        ]);

        $query->andFilterWhere(['like', 'name_area', $this->name_area])
            ->andFilterWhere(['like', 'conditions', $this->conditions])
            ->andFilterWhere(['like', 'color', $this->color])
            ->andFilterWhere(['like', 'bounds_southwest', $this->bounds_southwest])
            ->andFilterWhere(['like', 'bounds_northeast', $this->bounds_northeast]);

        return $dataProvider;
    }
}
