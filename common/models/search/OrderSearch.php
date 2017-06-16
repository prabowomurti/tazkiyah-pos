<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Order;

/**
 * OrderSearch represents the model behind the search form about `common\models\Order`.
 */
class OrderSearch extends Order
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'customer_id', 'outlet_id', 'created_at', 'updated_at'], 'integer'],
            [['code', 'status', 'total_price', 'note', 'delivery_time', 'customer.username'], 'safe'],
        ];
    }

    /**
     * Model attributes
     * @return array array of attributes
     */
    public function attributes()
    {
        return array_merge(parent::attributes(), ['customer.username']);
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
        $query = Order::find();

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

        // set an alias for the Order table
        $query->from(['o' => Order::tableName()]);

        // $query->joinWith(['RELATION NAME' => function ($query) {$query->from(['ALIAS' => 'RELATION TABLE NAME']);}]);
        $query->joinWith(['customer' => function ($query) {$query->from(['customer' => 'customer']);}]);

        // grid filtering conditions
        $query->andFilterWhere(['like', 'customer.username', $this->getAttribute('customer.username')]);

        // grid filtering conditions
        $query->andFilterWhere([
            'o.id' => $this->id,
            'customer_id' => $this->customer_id,
            'outlet_id' => $this->outlet_id,
            'tax' => $this->tax,
            'o.created_at' => $this->created_at,
            'o.updated_at' => $this->updated_at,
        ]);

        $query->andFilterCompare('total_price', $this->total_price);

        $query->andFilterWhere(['like', 'delivery_time', $this->delivery_time]);

        $query->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'note', $this->note]);

        return $dataProvider;
    }
}
