<?php

namespace app\models;
use app\helpers\param\Param;
use app\helpers\PhoneFilter;
use \yii\db\ActiveRecord;

use Yii;

/**
 * This is the model class for table "countries".
 *
 * @property integer $id
 * @property string $created
 * @property string $code
 * @property string $code2
 * @property string $name
 * @property string $currency
 * @property string $timezone
 */
class Country extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'countries';
    }

    public function rules()
    {
        return [
            [['created'], 'safe'],
            [['code', 'code2'], 'required'],
            [['code', 'code2'], 'string', 'max' => 3],
            [['name'], 'string', 'max' => 16],
            [['currency'], 'string', 'max' => 3],
            [['timezone'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created' => Yii::t('app', 'Country.Created'),
            'code' => Yii::t('app', 'Country.Code'),
            'code2' => Yii::t('app', 'Country.Code2'),
            'currency' => Yii::t('app', 'Currency'),
            'timezone' => Yii::t('app', 'TimeZone'),
        ];
    }

    /**
     * @see Country::getLanguageByCode()
     * @return string
     */
    public function getLanguage(): string
    {
        return self::getLanguageByCode($this->code);
    }

    /**
     * Get language by country
     *
     * @param string $code
     *
     * @return string
     */
    public static function getLanguageByCode(string $code): string
    {
        $languages = Param::getGroup('countries_languages');

        if (array_key_exists($code, $languages)) {
            return $languages[$code];
        }

        return 'en'; // default en lang
    }

    /**
     * @param string $currency
     *
     * @return self|null
     * @throws \yii\db\Exception
     */
    public static function getByCurrency(string $currency)
    {
        return self::find()->where(['currency' => $currency])->one();
    }

    /**
     * @return array
     */
    public static function allCodes():array {

        return self::find()->select(['code'])->indexBy('code')->asArray()->column();
    }
}
