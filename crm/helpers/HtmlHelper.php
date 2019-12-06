<?php

namespace app\helpers;

use app\models\Support;
use app\models\Task;
use Yii;
use yii\base\InvalidArgumentException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use \yii\bootstrap\Html as Bootstap;
/**
 * Class HtmlHelper
 * @package app\models
 *
 * Model for generating complex or often-used html elements
 */
class HtmlHelper extends Html
{

    /**
     * Generates link to card view
     * @param int $customerId
     * @param array $options - additional options
     * @return string
     */
    public static function customerModalButton(int $customerId, array $options = []): string
    {
        return self::a($customerId, '#', ArrayHelper::merge([
            'class'            => 'customer-modal-button',
            'title'            => Yii::t('app', 'CustomerView'),
            'data-customer_id' => $customerId,
            'padding'          => '5px',
        ], $options));
    }

    /**
     * Универсальная ajax-кнопка
     * В $options можно передать data-confirm - текст подтверждения действия
     *
     * @param string $text - текст кнопки
     * @param string $url - урл запроса
     * @param string $method - метод запроса
     * @param array $data - данные запроса
     * @param string $callbackName - название js-функции в случае успеха (должна быть объявлена через window.callback_name)
     * @param array $options - html-опции
     *
     * @return string
     * @throws \yii\base\InvalidArgumentException
     *
     * @see app.js - initAjaxButtons()
     */
    public static function ajaxButton(string $text, string $url, string $method = 'get', array $data = [], $callbackName = '', array $options = []): string
    {
        $url = Url::toRoute($url); # преобразование yii-шного роутинга
        $method = mb_strtolower($method);
        if (!in_array($method, ['get', 'post'])) { # проверка метода
            throw new InvalidArgumentException('Method must be GET or POST');
        }

        # данные в JSON
        $dataString = Json::encode($data);

        # чтобы не переписать важный класс, на который вешается событие
        $options['class'] = array_key_exists('class', $options) ? 'ajax-button ' . $options['class'] : 'ajax-button';

        return self::a($text, '#', ArrayHelper::merge([
            'data-url'      => $url,
            'data-method'   => $method,
            'data-data'     => $dataString,
            'data-callback' => $callbackName,
        ], $options));
    }

    /**
     * @param Support $user
     *
     * @return string
     */
    public static function supportLogin(Support $user): string
    {
        return self::tag('h6',
            $user->getLogin() . ' <span 
                id="support-online-label-' . $user->getId() . '" 
                class="support-online-label label label-success" style="display: none;">online</span>');
    }


    /**
     * @return string
     */
    public static function verifiedLabel(){

        return self::tag('span','verified',['class' => 'label label-success']);
    }

    /**
     * @return string
     */
    public static function notVerifiedLabel(){
        return self::tag('span','not verified',['class' => 'label label-default']);
    }

    /**
     * @param $id string
     * @return string
     */
    public static function submitBtn($id = 'save_btn_default')
    {

        return self::submitButton(Yii::t('app', 'Save'),
            [
                'class' => 'btn btn-primary',
                'id' => $id
            ]
        );
    }


    /**
     * Generate bootstrap primary label
     * 
     * @param $content
     * @param string $class_add
     * @param string $title
     * @return string
     */
    public static function label_primary($content, $class_add = '', $title = ''){

        $class = 'label label-primary';
        if($class_add !== ''){
            $class .= ' '.$class_add;
        }
       return Html::tag('span', $content, ['class' => $class, 'title' =>$title]);
    }

    /**
     *
     * Generates bootstrap info label
     *
     * @param string $content
     * @param string $class_add
     * @param string $title
     * @return string
     */
    public static function label_info($content, $class_add = '', $title = ''){

        $class = 'label label-info';

        if($class_add !== ''){
            $class .= ' '.$class_add;
        }
        return Html::tag('span', $content, ['class' => $class, 'title' =>$title]);

    }


    public static function label($content, $class = 'default', $class_add = '', $title = ''){

        $class = 'label label-'.$class;

        if($class_add !== ''){
            $class .= ' '.$class_add;
        }
        return Html::tag('span', $content, ['class' => $class, 'title' =>$title]);
    }

    /**
     * Generate bootstrap success label
     *
     * @param $content
     * @param string $class_add
     * @param string $title
     * @return string
     */
    public static function label_success($content, $class_add = '', $title = ''){

        $class = 'label label-success';
        if($class_add !== ''){
            $class .= ' '.$class_add;
        }
        return Html::tag('span', $content, ['class' => $class, 'title' =>$title]);
    }

    /**
     * Generate bootstrap danger label
     *
     * @param $content
     * @param string $class_add
     * @param string $title
     * @return string
     */
    public static function label_danger($content, $class_add = '', $title = ''){

        $class = 'label label-danger';
        if($class_add !== ''){
            $class .= ' '.$class_add;
        }
        return Html::tag('span', $content, ['class' => $class, 'title' =>$title]);
    }

    /***
     *
     * Generate bootstrap warning label
     *
     * @param $content
     * @param string $class_add
     * @param string $title
     * @return string
     */
    public static function label_warning($content, $class_add = '', $title = ''){


        $class = 'label label-warning';
        if($class_add !== ''){
            $class .= ' '.$class_add;
        }
        return Html::tag('span', $content, ['class' => $class, 'title' =>$title]);
    }

    /**
     *  Generate custom bootstrap label warning-dark
     *
     * @param $content
     * @param string $class_add
     * @param string $title
     * @return string
     */
    public static function label_warning_dark($content, $class_add = '', $title = ''){

        $class = 'label label-warning_dark';
        if($class_add !== ''){
            $class .= ' '.$class_add;
        }
        return Html::tag('span', $content, ['class' => $class, 'title' =>$title]);
    }

    /**
     * @param $content string
     * @param string $id
     * @param string $class_add
     * @param string $title
     * @return string
     */
      public static function label_warning_bright($content, $id = null, $class_add = null, $title = null){

        $class = 'label label-warning_bright color_black';

          $options = [];


          if($id !== null){
              $options['id'] = $id;
          }

          if ($class_add !== null){

              $class .= ' '.$class_add;
          }
            if ($title !== null){

                $options['title'] = $title;
          }

          $options['class'] = $class;


        return Html::tag('span', $content, $options);
    }



    /**
     * @param $content
     * @param string $class_add
     * @param string $title
     * @return string
     */
    public static function label_default($content, $class_add = '', $title = ''){


        $class = 'label label-default';
        if($class_add !== ''){
            $class .= ' '.$class_add;
        }
        return Html::tag('span', $content, ['class' => $class, 'title' =>$title]);
    }

    /**
     * @param string $content
     * @param string $class_add
     * @param string $title
     * @return string
     */
    public static function undefined_label($content = 'N/A', $class_add = '', $title = ''){

        return self::label_default($content);
    }


    /**
     * Generates link
     * used in verification-statistics/by-day
     * for link to corresponding verification by admin
     *
     * @param $date string
     * @return  string
     */
    public static function verificationByDayStatLink($date){

        return  Html::a($date,
            ['/verification-statistics/by-admin?VerificationStatistics%5BdateFrom%5D=' . $date .
                '&VerificationStatistics%5BdateTo%5D=' . $date],
            ['target' => '_blank',
                'title' => 'link to verification stat by admin for this date'
            ]);

    }

    /**
     * @param $text
     * @return string
     */
    public static function font10Tag($text){

        return Html::tag('span',$text,['class' => 'font10']);
    }

    /**
     * @param $text
     * @return string
     */
    public static function font25Tag($text){

        return Html::tag('span',$text,['class' => 'font25']);
    }

    /**
     * @param Task[] $tasks
     * @return array of links (<a>)
     */
    public static function tasksLinks($tasks) {
        return array_map(function ($task) {
            /** @var \app\models\Task $task */
            return Html::a('#' . $task->id, ['/task/list', 'TaskSearch[id]' => $task->id],
                ['target' => '_blank','class' => 'card_view_existed_task_link']);
        }, $tasks);
    }

    /**
     * @param string $name
     * @param string $title
     * @return string
     */
    public static function gliphicon(string $name, string $title = ''){

        $options = [];

        if($title !== ''){

            $options['title'] = $title;
        }

      return Bootstap::icon($name, $options);
    }
}
