<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;

/**
 * UserSearch represents the model behind the search form about `app\models\User`.
 */
class UserSearch extends User
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type', 'status', 'createdById', 'updatedById'], 'integer'],
            [['username', 'email', 'password', 'oldPassword', 'passwordResetToken', 'firstName', 'lastName', 'profilePicture', 'phoneNo', 'timeZone', 'roleName', 'lastAccess', 'createdAt', 'updatedAt'], 'safe'],
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
        $query = User::find();

        // Add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'type' => $this->type,
            'status' => $this->status,
            'lastAccess' => $this->lastAccess,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
            'createdById' => $this->createdById,
            'updatedById' => $this->updatedById,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
                ->andFilterWhere(['like', 'email', $this->email])
                ->andFilterWhere(['like', 'password', $this->password])
                ->andFilterWhere(['like', 'oldPassword', $this->oldPassword])
                ->andFilterWhere(['like', 'passwordResetToken', $this->passwordResetToken])
                ->andFilterWhere(['like', 'firstName', $this->firstName])
                ->andFilterWhere(['like', 'lastName', $this->lastName])
                ->andFilterWhere(['like', 'profilePicture', $this->profilePicture])
                ->andFilterWhere(['like', 'phoneNo', $this->phoneNo])
                ->andFilterWhere(['like', 'timeZone', $this->timeZone])
                ->andFilterWhere(['like', 'roleName', $this->roleName]);

        return $dataProvider;
    }

}
