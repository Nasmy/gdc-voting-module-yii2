<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use app\models\Producer;
use app\models\Register;
use app\models\Category;
use Yii;
/**
 * ProducerSearch represents the model behind the search form about `app\models\Producer`.
 */
class ProducerSearch extends Producer
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
        $query = Producer::find();

        $query->select(['Producer.*', '(SELECT COUNT(*) FROM ProducerReminder WHERE producerId = Producer.id AND remindNo = 1) AS reminder1', '(SELECT COUNT(*) FROM ProducerReminder WHERE producerId = Producer.id AND remindNo = 2) AS reminder2', '(SELECT COUNT(*) FROM ProducerReminder WHERE producerId = Producer.id AND remindNo = 3) AS reminder3']);



        // Add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $this->load($params);

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
    public function searchRegister($params)
    {

        $dataArray = array();

        //Added by Rooban to filter results for search
        $this->load($params);

        $producerQuery = Producer::find();

        $producerQuery->andFilterWhere([
            'registered' => $this->registered,
            ]);

        $producerQuery->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'email', $this->email]);

        //TODO - Get the results count before the page limit
        $countCurrentResults = $producerQuery->count();

        //Added by Rooban to create custom pagination
        if(isset($params['page']) && isset($params['per-page'])){

            /*$offset = $params['page'] * $params['per-page'];
            if($params['per-page'] == $offset){
                $offset = 0;
            }*/
            $offset = ($params['page']-1) * $params['per-page'];
            $producers = $producerQuery->limit($params['per-page'])->offset($offset)->orderBy(['id' => SORT_DESC])->all();
        } else {
            $producers = $producerQuery->limit(10)->orderBy(['id' => SORT_DESC])->all();
        }

        $categoryQuery = Category::find();
        $categories = $categoryQuery->all();

        $a = 0;
        foreach ($producers as $producer) {

            $dataArray[$a]['id'] =  $producer->id;
            $dataArray[$a]['name'] =  $producer->name;
            $dataArray[$a]['email'] =  $producer->email;
            $dataArray[$a]['phoneNo'] =  $producer->phoneNo;
            $dataArray[$a]['registered'] =  !empty($producer->voted) ? Yii::t('app', 'Yes') : Yii::t('app', 'No');

			$dataArray[$a]['registeredAt'] =  Yii::$app->formatter->asDateTime($producer->registeredAt);
	
			
            $b = 0;
            foreach ($categories as $category) {

                $register = Register::find()->where(['producerId' => $producer->id, 'categoryId' => $category->id])->one();
                $name = null;
                if (null !== $register) {
                    $name = $register->nominee->name;
                }

                $dataArray[$a][$category->name] = !empty($name) ? $name : null;
                $b++;
            }
            $a++;
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $dataArray,
            'key'   => 'id',
            'sort' => [
                'attributes' => ['id', 'name', 'email', 'phoneNo', 'registered'],
            ],
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        //return $dataProvider;
        return [$dataProvider, $countCurrentResults];
    }
}
