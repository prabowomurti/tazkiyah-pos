<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Employee;

/**
 * EmployeeSearch represents the model behind the search form about `common\models\Employee`.
 */
class EmployeeSearch extends Employee
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'outlet_id'], 'integer'],
            [['user.username', 'outlet.label'], 'safe']
        ];
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), ['user.username', 'outlet.label']);
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
        $query = Employee::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // set an alias for the Employee table
        $query->from(['e' => Employee::tableName()]);

        // $query->joinWith(['RELATION NAME' => function ($query) {$query->from(['ALIAS' => 'RELATION TABLE NAME']);}]);
        $query->joinWith(['user' => function ($query) {$query->from(['user' => 'user']);}]);

        $query->joinWith(['outlet' => function ($query) {$query->from(['outlet' => 'outlet']);}]);

        // grid filtering conditions
        $query->andFilterWhere(['like', 'user.username', $this->getAttribute('user.username')]);
        $query->andFilterWhere(['like', 'outlet.label', $this->getAttribute('outlet.label')]);

        // grid filtering conditions
        $query->andFilterWhere([
            'e.id' => $this->id,
            'user_id' => $this->user_id,
            'outlet_id' => $this->outlet_id,
        ]);

        $dataProvider->sort->attributes['user.username'] = [
            'asc' => ['user.username' => SORT_ASC],
            'desc' => ['user.username' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['outlet.label'] = [
            'asc' => ['outlet.label' => SORT_ASC],
            'desc' => ['outlet.label' => SORT_DESC],
        ];

        return $dataProvider;
    }
}
