<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use app\models\Voter;
use app\models\Vote;
use app\models\Category;
use Yii;
/**
 * VoterSearch represents the model behind the search form about `app\models\Voter`.
 */
class VoterSearch extends Voter
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'voted', 'tokenSent', 'step', 'status', 'createdById', 'updatedById'], 'integer'],
            [['name', 'email', 'phoneNo', 'votedAt', 'token', 'tokenSentAt', 'device', 'platform', 'platformVersion', 'browser', 'browserVersion', 'roleName', 'createdAt', 'updatedAt'], 'safe'],
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
        $query = Voter::find();

        $query->select(['Voter.*', '(SELECT COUNT(*) FROM VoterReminder WHERE voterId = Voter.id AND remindNo = 1) AS reminder1', '(SELECT COUNT(*) FROM VoterReminder WHERE voterId = Voter.id AND remindNo = 2) AS reminder2', '(SELECT COUNT(*) FROM VoterReminder WHERE voterId = Voter.id AND remindNo = 3) AS reminder3']);

        //$query->join('LEFT JOIN', 'VoterReminder vr1', 'vr1.voterId = Voter.id AND vr1.remindNo = 1');
        //$query->join('LEFT JOIN', 'VoterReminder vr2', 'vr2.voterId = Voter.id AND vr2.remindNo = 1');
        //$query->join('LEFT JOIN', 'VoterReminder vr3', 'vr3.voterId = Voter.id AND vr3.remindNo = 1');


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
            'voted' => $this->voted,
            'votedAt' => $this->votedAt,
            'tokenSent' => $this->tokenSent,
            'tokenSentAt' => $this->tokenSentAt,
            'step' => $this->step,
            'status' => $this->status,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
            'createdById' => $this->createdById,
            'updatedById' => $this->updatedById,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phoneNo', $this->phoneNo])
            ->andFilterWhere(['like', 'token', $this->token])
            ->andFilterWhere(['like', 'device', $this->device])
            ->andFilterWhere(['like', 'platform', $this->platform])
            ->andFilterWhere(['like', 'platformVersion', $this->platformVersion])
            ->andFilterWhere(['like', 'browser', $this->browser])
            ->andFilterWhere(['like', 'browserVersion', $this->browserVersion])
            ->andFilterWhere(['like', 'roleName', $this->roleName]);

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

        $dataArray = array();

        //Added by Rooban to filter results for search
        $this->load($params);

        $voterQuery = Voter::find();

        $voterQuery->andFilterWhere([
            'voted' => $this->voted,
            ]);

        $voterQuery->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'email', $this->email]);

        //TODO - Get the results count before the page limit
        $countCurrentResults = $voterQuery->count();

        //Added by Rooban to create custom pagination
        if(isset($params['page']) && isset($params['per-page'])){

            /*$offset = $params['page'] * $params['per-page'];
            if($params['per-page'] == $offset){
                $offset = 0;
            }*/
            $offset = ($params['page']-1) * $params['per-page'];
            $voters = $voterQuery->limit($params['per-page'])->offset($offset)->orderBy(['id' => SORT_DESC])->all();
        } else {
            $voters = $voterQuery->limit(10)->orderBy(['id' => SORT_DESC])->all();
        }

        $categoryQuery = Category::find();
        $categories = $categoryQuery->all();

        $a = 0;
        foreach ($voters as $voter) {
			//if (0 == $voter->voted) {
			//	continue;
			//}
			
            //echo "\n<br />Voter.name: " . $voter->name;
            //echo "\n<br />" . $voter->name;
            $dataArray[$a]['id'] =  $voter->id;
            $dataArray[$a]['name'] =  $voter->name;
            $dataArray[$a]['email'] =  $voter->email;
            $dataArray[$a]['phoneNo'] =  $voter->phoneNo;
            //$dataArray[$a]['voted'] =  !empty($voter->voted) ? '<span class="glyphicon glyphicon-check"></span>' : '<span class="glyphicon glyphicon-minus"></span>';
            $dataArray[$a]['voted'] =  !empty($voter->voted) ? Yii::t('app', 'Yes') : Yii::t('app', 'No');
            //$dataArray[$a]['votedAt'] =  ($voter->votedAt) ? date('d/m/Y (H:i)', strtotime($voter->votedAt)) : '-';
			$dataArray[$a]['votedAt'] =  Yii::$app->formatter->asDateTime($voter->votedAt);
			//$dataArray[$a]['votedAt'] =  date('d/m/Y (H:i a)', strtotime(Yii::$app->formatter->asDateTime($voter->votedAt)));
			
            $b = 0;
            foreach ($categories as $category) {
                //echo "\tCategory.name: " . $category->name;
                //echo "\t" . $category->name;
                //$vote = Vote::find()->where(['voterId' => $voter->id, 'categoryId' => $category->id])->exists() ? '<span class="glyphicon glyphicon-check"></span>' : '<span class="glyphicon glyphicon-minus"></span>';
                $vote = Vote::find()->where(['voterId' => $voter->id, 'categoryId' => $category->id])->one();
                $name = null;
                if (null !== $vote) {
                    $name = $vote->nominee->name;
                }
                //$vote = Vote::find()->where(['voterId' => $voter->id, 'categoryId' => $category->id])->exists() ? 1 : null;
                //echo "\tVote" . $vote;
                //echo "\t" . $vote;
                $dataArray[$a][$category->name] = !empty($name) ? $name : null;
                $b++;
            }
            $a++;
        }
        //print_r($dataArray);

        $dataProvider = new ArrayDataProvider([
            'allModels' => $dataArray,
            'key'   => 'id',
            'sort' => [
                'attributes' => ['id', 'name', 'email', 'phoneNo', 'voted'],
            ],
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        //return $dataProvider;
        return [$dataProvider, $countCurrentResults];
    }
}
