<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 18.11.16
 * Time: 19:52
 */

namespace app\models;

use app\tests\unit\helpers\AsteriskApiHelperTest;
use Yii;

use \yii\db\ActiveRecord;

/**
 * This is the model class for table "cards_comments".
 *
 * @property integer $id
 * @property string $created
 * @property integer $card_id
 * @property integer $user_id
 * @property string $text
 *
 * @property Card $card
 * @property Support $user
 */

class CardComment extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cards_comments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created'], 'safe'],
            [['card_id', 'text'], 'required'],
            [['card_id', 'user_id'], 'integer'],
            [['text'], 'string'],
            [['card_id'], 'exist', 'skipOnError' => true, 'targetClass' => Card::class, 'targetAttribute' => ['card_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Support::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created' => 'Created',
            'card_id' => 'Card ID',
            'user_id' => 'User ID',
            'text' => 'Text',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCard()
    {
        return $this->hasOne(Card::class, ['id' => 'card_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Support::class, ['id' => 'user_id']);
    }

    public function afterSave($insert, $changedAttributes)
    {

        if($insert){

            $change = new CardChanges();
            $change->card_id = $this->card->id;
            /**
             * здесь было уловие, что если есть текущий юзер, то записываем на него, иначе на владельца комента
             * но ситуация, когда нет текущего юзера, возможна только в AsteriskApiV3Helper->setNotOnPhone
            * и в этом случае изменение записывается на владельца комента, который не бывает пустым
            * а во всех остальных случаях срабатывает 1-ое условие - текущий юзер
            * но так как эта логика срабатывает автоматически сразу после сохранения нового комента,
            * то текущий юзер это и есть владелей только что созданного комента,
            * поэтому нет смысла в этой проверке, к тому же не удавалось протестировать создателя изменения
             *
            * @see AsteriskApiHelperTest::testUpdateNotPhone()
            *
            */
            $change->user_id = $this->user_id;
            $change->type = CardChanges::TYPE_COMMENT_ADD;

            if(! $change->save()){

                Yii::error('Error saving card change. errors: ' . var_export($change->errors, true));
            }

        }
        parent::afterSave($insert, $changedAttributes);
    }


}
