<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\RolePermission;

/**
 * RolePermissionSearch represents the model behind the search form about `app\models\RolePermission`.
 */
class RolePermissionSearch extends RolePermission
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['roleName', 'permissionName', 'createdAt', 'updatedAt'], 'safe'],
            [['createdById', 'updatedById'], 'integer'],
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
        $query = RolePermission::find();

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
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
            'createdById' => $this->createdById,
            'updatedById' => $this->updatedById,
        ]);

        $query->andFilterWhere(['like', 'roleName', $this->roleName])
            ->andFilterWhere(['like', 'permissionName', $this->permissionName]);

        return $dataProvider;
    }
}
