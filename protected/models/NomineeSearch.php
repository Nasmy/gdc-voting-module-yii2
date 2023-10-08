<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use app\models\Nominee;
use app\models\Vote;
use app\modules\api\components\ApiStatusMessage;

/**
 * NomineeSearch represents the model behind the search form about `app\models\Nominee`.
 */
class NomineeSearch extends Nominee
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
            [['id', 'createdById', 'updatedById'], 'integer'],
            [['name', 'description', 'imageName', 'createdAt', 'updatedAt', 'categoryName'], 'safe'],

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
        $query = Nominee::find();

        $query->select(['Nominee.*', 'Category.name AS categoryName']);

        $query->join('LEFT JOIN', 'CategoryNominee', 'CategoryNominee.nomineeId = Nominee.id');
        $query->join('LEFT JOIN', 'Category', 'Category.id = CategoryNominee.categoryId');

        // Add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            //'sort'=> ['defaultOrder' => ['categoryName' => SORT_ASC, 'name' => SORT_ASC]]
            'sort'=> ['defaultOrder' => ['categoryOrder' => SORT_ASC, 'name' => SORT_ASC]],
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $dataProvider->sort->attributes['categoryName'] = [
            'asc' => ['Category.name' => SORT_ASC],
            'desc' => ['Category.name' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['categoryOrder'] = [
            'asc' => ['Category.order' => SORT_ASC],
            'desc' => ['Category.order' => SORT_DESC],
        ];

        //$query->groupBy(['categoryName']);

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
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
            'createdById' => $this->createdById,
            'updatedById' => $this->updatedById,
        ]);

        $query->andFilterWhere(['like', 'Nominee.name', $this->name])
            ->andFilterWhere(['like', 'Nominee.description', $this->description])
            ->andFilterWhere(['like', 'imageName', $this->imageName]);

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ArrayDataProvider
     */
    public function searchVotes($params)
    {
        //$voters = Vote::find()->where(['=', 'nomineeId', $params['nomineeId']])->all();
        //echo "\n<br />voters: ";
        //print_r($voters);
        //exit;

        /*
        $query = Vote::find();

        $query->where(['=', 'nomineeId', $params['nomineeId']]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        */
        $dataArray = array();

        $votes = Vote::find()->where(['=', 'nomineeId', $params['nomineeId']])->andWhere(['=', 'categoryId', $params['categoryId']])->all();
        //echo "\n<br />votes: ";
        //print_r($votes);
        //exit;

        $a = 0;
        foreach ($votes as $vote) {
            $voter = $vote->voter;
            //echo "\n<br />voter: ";
            //print_r($voter);
            //exit;
            //$dataArray[$a]['id'] = $voter->id;
			if (0 == $voter->voted) {
				continue;
			}
			
            $dataArray[$a]['id'] = $voter->id;
            $dataArray[$a]['name'] = $voter->name;
            $dataArray[$a]['email'] = $voter->email;
            $dataArray[$a]['phoneNo'] = $voter->phoneNo;
            //$dataArray[$a]['votedDate'] =  Yii::$app->formatter->asDate($voter->createdAt, 'php:d/m/Y');
            //$dataArray[$a]['votedTime'] = Yii::$app->formatter->asDate($voter->createdAt, 'php:H:m:i');			
			$dataArray[$a]['votedDate'] =  Yii::$app->formatter->asDate($voter->votedAt);
            $dataArray[$a]['votedTime'] = Yii::$app->formatter->asTime($voter->votedAt);
            $a++;
        }

        //echo "\n<br />dataArray: ";
        //print_r($dataArray);
        //exit;

        $dataProvider = new ArrayDataProvider([
            'allModels' => $dataArray,
            'key'   => 'id',
            'sort' => [
                'attributes' => ['name', 'email', 'phoneNo'],
            ],
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);

        return $dataProvider;
    }

    /**
     * Search for API requests
     * @return array
     */
    public function apiSearch()
    {
        $query = Nominee::find();

        if (!$this->all) {
            $offset = ($this->page - 1) * $this->limit;

            $query->limit($this->limit);

            $query->offset($offset);
        }

        //$query->orderBy(['order' => SORT_ASC]);

        $total = $query->count();

        $models = $query->all();

        return ['total' => $total, 'models' => $models];
    }
}
