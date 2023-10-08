<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Category;
use app\modules\api\components\ApiStatusMessage;

/**
 * CategorySearch represents the model behind the search form about `app\models\Category`.
 */
class CategorySearch extends Category
{
    const SCENARIO_API_SEARCH = 'apiSearch';

    public $all = true;
    public $limit = 10;
    public $page = 1;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'order', 'createdById', 'updatedById'], 'integer'],
            [['name', 'createdAt', 'updatedAt'], 'safe'],

            // API Search
            [['limit', 'page'], 'integer', 'message' => ApiStatusMessage::VALIDATION_FAILED, 'on' => self::SCENARIO_API_SEARCH],
            [['all'], 'safe', 'on' => self::SCENARIO_API_SEARCH]
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // Bypass scenarios() implementation in the parent class
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
        $query = Category::find();

        // Add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $this->load($params);

        /*
        if (!$this->validate()) {
            // Uncomment the following line if you do not want to return any records when validation fails
            //$query->where('0 = 1');
            return $dataProvider;
        }
        */

        // Grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'order' => $this->order,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
            'createdById' => $this->createdById,
            'updatedById' => $this->updatedById,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }

    /**
     * Search for API requests
     * @return array
     */
    public function apiSearch()
    {
        $query = Category::find();

        if (!$this->all) {
            $offset = ($this->page - 1) * $this->limit;

            $query->limit($this->limit);

            $query->offset($offset);
        }

        $query->orderBy(['order' => SORT_ASC]);

        $total = $query->count();

        $models = $query->all();

        return ['total' => $total, 'models' => $models];
    }
}
