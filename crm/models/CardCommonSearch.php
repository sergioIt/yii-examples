<?php
/**
 * Created by Valerii Tikhoirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 03.08.2018, 16:51
 */

namespace app\models;

use yii\data\ActiveDataProvider;

/**
 * Class CardCommonSearch
 * @package app\models
 */
class CardCommonSearch extends Customers
{
    /**
     * @var string
     */
    public $full_name;

    /** @inheritdoc */
    public function rules(): array
    {
        return [
            [['id'], 'integer'],
            [['currency', 'email', 'status'], 'string'],
            [['real'], 'boolean'],
            [['phone'], 'string', 'min' => 6],
            [['full_name'], 'string', 'min' => 4],
            [['currency', 'status', 'real'], 'onlyMainSearchValidate'], // Filter works only with main search
        ];
    }

    /**
     * Restricts some filters
     * @param $attribute
     */
    public function onlyMainSearchValidate($attribute)
    {
        if (!$this->id && !$this->full_name && !$this->email && !$this->phone) {
            $this->addErrors([$attribute => 'Filter works only with main search']);
        }
    }

    /**
     * @param array $params
     *
     * @return ActiveDataProvider
     * @throws \ReflectionException
     * @throws \yii\base\InvalidArgumentException
     */
    public function search(array $params): \yii\data\ActiveDataProvider
    {
        $query = Customers::find()->with('card');

        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'pagination' => [
                'pageSize' => 50,
            ],
            'sort'       => [
                'defaultOrder' => ['id' => SORT_DESC],
            ],
        ]);

        $params = $this->paramsFilter($params);

        // load the search form data and validate
        if (!$this->load($params) || !$this->validate()) {
            // fake condition to get empty results
            $query = Customers::find()->where('1 <> 1');
            $dataProvider->query = $query;
            return $dataProvider;
        }

        // search by name
        $fullName = mb_strtolower(trim($this->full_name));
        if (strlen($fullName) > 0) {
            $cardsByName = Card::find()
               ->select('customer_id')
               ->where(
                   ['like', 'lower(opt_name)', $fullName]
               )->asArray()->column();

            $query
                ->andWhere([
                    'or',
                    ['like', 'lower(full_name_pp)', $fullName],
                    ['like', 'lower(first_name)', $fullName],
                    ['like', 'lower(last_name)', $fullName],
                    ['in', 'id', $cardsByName]
                ]);
        }

        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['currency' => $this->currency]);
        $query->andFilterWhere(['real' => $this->real]);
        $query->andFilterWhere(['status' => $this->status]);

        if ($this->email) {
            $query->andWhere(['like', 'lower(email)', mb_strtolower(trim($this->email))]);
        }

        if ($this->phone) {

            // задача стоит так: search/ main phone должен искать и opt phone
            // поэтому, если задан поиск по номеру телефона,  то поступаем так же как при поиске по имени:

            // ищем клиентов в карточках по окончанию заданного номера
            $cardsByPhone = Card::find()
                ->select('customer_id')
                ->where(
                    ['like', 'opt_phone', '%' . $this->phone, false])->asArray()->column();

            // и далее ищем только среди клиентов по окончанию номера либо по id клиентов найденных в карточках по телефону
            $query->andWhere([
                'or',
                    ['like', 'phone', '%' . $this->phone, false],
                    ['in', 'id', $cardsByPhone],
                ]
            );
        }

        return $dataProvider;
    }

    /**
     * Trim params
     * Return empty array if all params is empty
     *
     * @param array $params
     *
     * @return array
     * @throws \ReflectionException
     */
    protected function paramsFilter(array $params): array
    {
        $key = (new \ReflectionClass($this))->getShortName();
        if (array_key_exists($key, $params)) {
            $trimParams = array_map('trim', $params[$key]);

            if (array_key_exists('full_name', $trimParams) && mb_strlen($trimParams['full_name']) > 2) { // name can be 3 chars, but validate set 4 chars (with spaces)
                $trimParams['full_name'] = $params[$key]['full_name'];
            }

            return empty(array_filter($trimParams, function ($paramValue) {
                return (string)$paramValue !== '';
            })) ? [] : [$key => $trimParams];
        }

        return $params;
    }
}
