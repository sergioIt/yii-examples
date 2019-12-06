<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 24.11.16
 * Time: 16:14
 */

namespace app\models;

use app\helpers\param\CurrencyParam;
use app\helpers\RbacHelper;
use yii\data\ActiveDataProvider;
use Yii;
use yii\db\Expression;
use kartik\daterange\DateRangeBehavior;

/**
 * Class CardSearch
 * @package app\models
 *
 * @property $customer_phone string
 * @property $customer_name string
 * @property $customer_email string
 * @property int $customer_status
 * @property int $customer_mode
 *
 * @property string $created_dt_start
 * @property string $created_dt_end
 *
 * @property string $updated_dt_start
 * @property string $updated_dt_end
 *
 * @property string $reg_date_dt_start
 * @property string $reg_date_dt_end
 *
 * @property string $last_seen_dt_start
 * @property string $last_seen_dt_end
 *
 * @property string $recall_date_dt_start
 * @property string $recall_date_dt_end
 *
 *
 *
 *
 */
class CardSearch extends Card
{
    const SCENARIO_LIST_ALL = 'all';
    const SCENARIO_LIST_ALL_VIP = 'all_vip';
    const SCENARIO_LIST_MY = 'list_my';
    const SCENARIO_LIST_MY_VIP = 'list_my_vip';
    const SCENARIO_SEARCH = 'search';

    /**
     * Array of customers id that found by search conditions lo
     *
     * @var array
     */

    public $customer_name;
    public $customer_status;
    public $customer_mode;

    public $created_dt_start;
    public $created_dt_end;

    public $updated_dt_start;
    public $updated_dt_end;

    public $reg_date_dt_start;
    public $reg_date_dt_end;


    public $last_seen_dt_start;
    public $last_seen_dt_end;

    public $recall_date_dt_start;
    public $recall_date_dt_end;

    public $last_comment_date_dt_start;
    public $last_comment_date_dt_end;

    public $last_deposit_date_dt_start;
    public $last_deposit_date_dt_end;

    public $currency;
    public $reg_date;
    public $email;
    public $verification_status;
    public $last_seen;

    public $mode;
    public $first_name;
    public $phone;

    /**
     * what need to select to show at search result
     *
     * @var array
     */
    public static $resultFields = [

        'id',
        'full_name',
        'full_name_pp',
        'first_name',
        'last_name',
        'email',
        'phone',
        'locale',
        'currency',
        'real',
        'status',
        'verify_denial_id',
        'active',
    ];

    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            [
                'class'              => DateRangeBehavior::class,
                'attribute'          => 'created',
                'dateStartAttribute' => 'created_dt_start',
                'dateEndAttribute'   => 'created_dt_end',
                'dateStartFormat'    => 'Y-m-d',
                'dateEndFormat'      => 'Y-m-d',
            ],
            [
                'class'              => DateRangeBehavior::class,
                'attribute'          => 'updated',
                'dateStartAttribute' => 'updated_dt_start',
                'dateEndAttribute'   => 'updated_dt_end',
                'dateStartFormat'    => 'Y-m-d',
                'dateEndFormat'      => 'Y-m-d',
            ],
            [
                'class'              => DateRangeBehavior::class,
                'attribute'          => 'reg_date',
                'dateStartAttribute' => 'reg_date_dt_start',
                'dateEndAttribute'   => 'reg_date_dt_end',
                'dateStartFormat'    => 'Y-m-d',
                'dateEndFormat'      => 'Y-m-d',
            ],
            [
                'class'              => DateRangeBehavior::class,
                'attribute'          => 'last_seen',
                'dateStartAttribute' => 'last_seen_dt_start',
                'dateEndAttribute'   => 'last_seen_dt_end',
                'dateStartFormat'    => 'Y-m-d',
                'dateEndFormat'      => 'Y-m-d',
            ],
            [
                'class'              => DateRangeBehavior::class,
                'attribute'          => 'recall_date',
                'dateStartAttribute' => 'recall_date_dt_start',
                'dateEndAttribute'   => 'recall_date_dt_end',
                'dateStartFormat'    => 'Y-m-d',
                'dateEndFormat'      => 'Y-m-d',
            ],
            [
                'class'              => DateRangeBehavior::class,
                'attribute'          => 'last_comment_date',
                'dateStartAttribute' => 'last_comment_date_dt_start',
                'dateEndAttribute'   => 'last_comment_date_dt_end',
                'dateStartFormat'    => 'Y-m-d',
                'dateEndFormat'      => 'Y-m-d',
            ],
            [
                'class'              => DateRangeBehavior::class,
                'attribute'          => 'last_deposit_date',
                'dateStartAttribute' => 'last_deposit_date_dt_start',
                'dateEndAttribute'   => 'last_deposit_date_dt_end',
                'dateStartFormat'    => 'Y-m-d',
                'dateEndFormat'      => 'Y-m-d',
            ],
        ];
    }

    /**
     * @return array
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_LIST_ALL] = ['customer_id', 'user_id', 'status', 'created', 'updated', 'currency', 'mode','first_name', 'email', 'phone','reg_date','last_seen','recall_date', 'last_comment_date', 'last_deposit_date', 'customer_phone'];
        $scenarios[self::SCENARIO_LIST_ALL_VIP] = ['customer_id', 'user_id', 'status', 'created', 'updated', 'currency', 'mode','first_name', 'email', 'phone','reg_date','last_seen','recall_date', 'last_comment_date', 'last_deposit_date'];
        $scenarios[self::SCENARIO_LIST_MY] = ['customer_id', 'status', 'created', 'updated', 'currency', 'mode','verification_status', 'first_name', 'email', 'phone','reg_date','last_seen','recall_date', 'last_comment_date', 'last_deposit_date'];
        $scenarios[self::SCENARIO_LIST_MY_VIP] = ['customer_id', 'status', 'created', 'updated', 'currency', 'mode', 'first_name', 'email', 'phone', 'reg_date','last_seen','recall_date', 'customer_phone'];

        // next attributes are used in card search for validating input and load to model
        $scenarios[self::SCENARIO_SEARCH] = [
            'customer_id',
            'customer_phone',
            'customer_name',
            'customer_status',
            'customer_mode',
            'customer_email',
            'created',
            'updated',
            'currency',
            'mode'
        ];
        return $scenarios;
    }

    /**
     * only fields in rules() are searchable
     * @return array
     */
    public function rules()
    {
        return [
            [['customer_id', 'user_id', 'status', 'mode'], 'integer', 'on' => [self::SCENARIO_LIST_ALL, self::SCENARIO_LIST_ALL_VIP]],
            [['currency'], 'string', 'on' => [self::SCENARIO_LIST_ALL, self::SCENARIO_LIST_ALL_VIP]],
            [['customer_id', 'status', 'mode'], 'integer', 'on' => self::SCENARIO_LIST_MY],
            [['customer_id'], 'integer', 'on' => [self::SCENARIO_LIST_MY_VIP]],
            [['customer_phone'], 'string', 'min' => 6, 'on' => [
                    self::SCENARIO_SEARCH,
                    self::SCENARIO_LIST_ALL,
                    self::SCENARIO_LIST_MY_VIP,
            ]],
            [['customer_name'], 'string', 'min' => 4, 'max' => 100, 'on' => self::SCENARIO_SEARCH],
            [['customer_name'], 'trim', 'on' => self::SCENARIO_SEARCH],
            [['customer_email'], 'trim', 'on' => self::SCENARIO_SEARCH],
            [['customer_email'], 'string', 'min' => 4, 'max' => 50, 'on' => self::SCENARIO_SEARCH],
            [['customer_id'], 'integer', 'on' => self::SCENARIO_SEARCH],
            [['customer_id'], 'trim', 'on' => self::SCENARIO_SEARCH],
            [['customer_status', 'customer_mode'], 'integer', 'on' => self::SCENARIO_SEARCH],
            [['verification_status'], 'integer'],
            [['customer_mode', 'customer_status'], 'validateNotEmptySearch', 'on' => self::SCENARIO_SEARCH],
            [['phone'], 'string', 'max' => 4,
                'message' => 'Phone search by last 4 digits, since that, max 4 symbols allowed'],
        ];
    }

    /**
     * Check use main filters (not empty search)
     * @param $attribute
     */
    public function validateNotEmptySearch($attribute)
    {
        if ($this->isEmptySearch()) {
            $this->addError($attribute, 'Filter works only with main search');
        }
    }

    /**
     * @param $params
     *
     * @return ActiveDataProvider
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     * @throws \yii\base\InvalidArgumentException
     */
    public function search($params)
    {
        $currentUser = Support::getCurrent();

        $query = Card::find()
            ->select(['c.*'])
            ->from(Card::tableName() . ' c')
            ->where('c.status > 0')
            ->with(['localCustomer', 'user', 'lastPayment'])
            ->leftJoin(['u' => Customers::tableName()], 'u.id=c.customer_id')
            // join last_deposit
            ->addSelect(['last_deposit_date' => 'ld.last_deposit_date',])
            ->addSelect(['customer_phone' => 'u.phone',])
            // @todo оптимизировать через distinct on
            ->leftJoin([
                'ld' =>
                    Payments::find()->select([
                        'customer_id',
                        'last_deposit_date' => 'MAX(deposit)',
                    ])->andWhere(['status' => Payments::STATUS_APPROVED])->groupBy(['customer_id']),
            ], 'ld.customer_id = c.customer_id');

        if ($this->scenario === self::SCENARIO_LIST_ALL || $this->scenario === self::SCENARIO_LIST_ALL_VIP)
        {
            // join last_comment
            // @todo оптимизировать через distinct on
            $query->addSelect(['last_comment_date' => 'lc.last_comment_date',])
                  ->leftJoin([
                      'lc' =>
                          CardComment::find()->select([
                              'card_id',
                              'last_comment_date' => 'MAX(created)',
                          ])->groupBy(['card_id']),
                  ], 'lc.card_id = c.id');

            if (RbacHelper::can(RbacHelper::ROLE_ADMIN)) {

                $sellersByCountry = Support::find()->byCountry($currentUser->country_id)->select('id')->column();
                $query->andWhere(['in', 'c.user_id', $sellersByCountry]);
            }
        }

        $currencies = CurrencyParam::getUserCurrencies();

        if ($this->scenario === self::SCENARIO_LIST_ALL) {
            $query->andWhere(['in', 'u.currency', $currencies]);
        }

        if ($this->scenario === self::SCENARIO_LIST_MY_VIP) {
            $query->andWhere(['>','u.profit_percent',0]);
            $query->andWhere(['c.user_id' => Yii::$app->user->getId()]);

        }

        /**
         * @todo сделать через exists(select 1 from customers where customers.id = c.customer_id and  customer.profit_percent>0
         */
        if($this->scenario === self::SCENARIO_LIST_ALL_VIP){
            $query->andWhere(['>','u.profit_percent', 0]);
            $query->andWhere(['in','u.currency', $currencies]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 25,
            ],
        ]);

        $dataProvider->setSort([
            'defaultOrder' => ['customer_id' => SORT_DESC],
            'attributes' => [
                'id' =>[
                    'asc' => ['id' => SORT_ASC],
                    'desc' => ['id' => SORT_DESC],
                ],
                'customer_id' =>[
                    'asc' => ['customer_id' => SORT_ASC],
                    'desc' => ['customer_id' => SORT_DESC],
                ],
                'created' =>[
                    'asc' => ['created' => SORT_ASC],
                    'desc' => ['created' => SORT_DESC],
                ],
                'updated' =>[
                    'asc' => ['updated' => SORT_ASC],
                    'desc' => ['updated' => SORT_DESC],
                ],
                'user_id' =>[
                    'asc' => ['user_id' => SORT_ASC],
                    'desc' => ['user_id' => SORT_DESC],
                ],
                'status' =>[
                    'asc' => ['status' => SORT_ASC],
                    'desc' => ['status' => SORT_DESC],
                ],
                'recall_date' =>[
                    'asc' => ['recall_date' => SORT_ASC],
                    'desc' => ['recall_date' => SORT_DESC],
                ],
                'last_seen' =>[
                    'asc' => [new Expression('last_seen NULLS FIRST')],
                    'desc' => [new Expression('last_seen DESC NULLS LAST')],
                ],
                'reg_date' =>[
                    'asc' => [new Expression('reg_date NULLS FIRST')],
                    'desc' => [new Expression('reg_date DESC NULLS LAST')],
                ],
                'first_name' =>[
                    'asc' => [new Expression('first_name NULLS FIRST')],
                    'desc' => [new Expression('first_name DESC NULLS LAST')],
                ],
                'email' =>[
                    'asc' => [new Expression('email NULLS FIRST')],
                    'desc' => [new Expression('email DESC NULLS LAST')],
                ],
                'last_comment_date' =>[
                    'asc' => [new Expression('last_comment_date NULLS FIRST')],
                    'desc' => [new Expression('last_comment_date DESC NULLS LAST')],
                ],
                'last_deposit_date' =>[
                    'asc' => [new Expression('last_deposit_date NULLS FIRST')],
                    'desc' => [new Expression('last_deposit_date DESC NULLS LAST')],
                ],
                'bonus_percent' => [
                    'asc' => [new Expression('bonus_percent NULLS FIRST')],
                    'desc' => [new Expression('bonus_percent DESC NULLS LAST')],
                ],
            ]
        ]);



        // load the search form data and validate
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

       $query->andFilterWhere(['between','date(c.created)',$this->created_dt_start, $this->created_dt_end]);
       $query->andFilterWhere(['between','date(c.updated)',$this->updated_dt_end, $this->updated_dt_end]);
       $query->andFilterWhere(['between','date(c.recall_date)',$this->recall_date_dt_start, $this->recall_date_dt_end]);

        $query->andFilterWhere(['between', 'to_timestamp(u.reg_date)::date', $this->reg_date_dt_start,$this->reg_date_dt_end]);

        $query->andFilterWhere(['between', 'to_timestamp(u.last_seen)::date', $this->last_seen_dt_start,  $this->last_seen_dt_end]);
        $query->andFilterWhere(['between', 'date(lc.last_comment_date)', $this->last_comment_date_dt_start,  $this->last_comment_date_dt_end]);
        $query->andFilterWhere(['between', 'date(ld.last_deposit_date)', $this->last_deposit_date_dt_start,  $this->last_deposit_date_dt_end]);

        $query->andFilterWhere(['at.bonus_percent' => $this->bonus_percent]);

//        $sql = $query->createCommand()->rawSql;

        // adjust the query by adding the filters
            $query->andFilterWhere(['c.id' => $this->id]);
            $query->andFilterWhere(['c.customer_id' => $this->customer_id]);
            $query->andFilterWhere(['c.status' => $this->status]);
            $query->andFilterWhere(['u.currency' => $this->currency]);
            $query->andFilterWhere(['u.real' => $this->mode]);
            $query->andFilterWhere(['c.user_id' => $this->user_id]);

            $query->andFilterWhere(['ilike','u.first_name', $this->first_name]);
            $query->andFilterWhere(['ilike','u.email', $this->email]);
            $query->andFilterWhere(['like', new Expression('RIGHT(u.phone, 4)') , $this->phone]);

        if ($this->customer_phone) {

            // задача стоит так: search/ main phone должен искать и opt phone
            // поэтому, если задан поиск по номеру телефона,  то поступаем так же как при поиске по имени:

            // ищем клиентов в карточках по окончанию заданного номера
            $cardsByPhone = Card::find()
                                ->select('customer_id')
                                ->where(
                                    ['like', 'opt_phone', '%' . $this->customer_phone, false])->asArray()->column();

            // и далее ищем только среди клиентов по окончанию номера либо по id клиентов найденных в карточках по телефону
            $query->andWhere([
                    'or',
                    ['like', 'phone', '%' . $this->customer_phone, false],
                    ['in', 'id', $cardsByPhone],
                ]
            );
        }

        return $dataProvider;
    }


    /**
     * Check if all search params are empty
     *
     * @return bool
     */
    public function isEmptySearch()
    {
        return !$this->searchIsSet('customer_id')
            && !$this->searchIsSet('customer_phone')
            && !$this->searchIsSet('customer_name')
            && !$this->searchIsSet('customer_email');
    }

    /**
     * @param $field string field nam of search model
     * @return bool
     */
    public function searchIsSet($field){

        return ($this->{$field} !== '' && $this->{$field} !== null);
    }

}
