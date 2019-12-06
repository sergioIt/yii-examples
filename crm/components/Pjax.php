<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 17.04.2018, 18:50
 */

namespace app\components;


use yii\bootstrap\Html;
use yii\helpers\Json;
use yii\widgets\PjaxAsset;

/**
 * Class Pjax
 * @package app\components
 */
class Pjax extends \yii\widgets\Pjax
{
    /**
     * JS callback function on send
     * @var string
     */
    public $onSend;

    /**
     * JS callback function on complete
     * @var string
     */
    public $onComplete;

    /**
     * Loader class
     * @var string
     */
    public $loaderClass = 'pjax-loader';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->getView()->registerJs($this->onSend());
        $this->getView()->registerJs($this->onComplete());

        # loader
        $this->getView()->registerCss('.' . $this->loaderClass . ' {display: none;}');
        echo Html::tag('div', 'Loading ... ' . Html::img('@images/loader-sm.gif'), ['class' => $this->loaderClass]);
    }

    /**
     * @return string
     */
    public function onSend(): string
    {
        return '$(document).on(\'pjax:send\', function() { ' . ($this->onSend ?? '$(\'.' . $this->loaderClass . '\').show();') . ' });';
    }

    /**
     * @return string
     */
    public function onComplete(): string
    {
        return '$(document).on(\'pjax:complete\', function() { ' . ($this->onComplete ?? '$(\'.' . $this->loaderClass . '\').hide();') . ' });';
    }

    /** {@inheritdoc} */
    public function registerClientScript()
    {
        $id = $this->options['id'];
        $this->clientOptions['push'] = $this->enablePushState;
        $this->clientOptions['replace'] = $this->enableReplaceState;
        $this->clientOptions['timeout'] = $this->timeout;
        $this->clientOptions['scrollTo'] = $this->scrollTo;
        if (!isset($this->clientOptions['container'])) {
            $this->clientOptions['container'] = "#$id";
        }
        $options = Json::htmlEncode($this->clientOptions);
        $js = '';
        if ($this->linkSelector !== false) {
            $linkSelector = Json::htmlEncode($this->linkSelector !== null ? $this->linkSelector : '#' . $id . ' a');

            // need to re-handle events for correct work with modal ajax load
            $js .= "\njQuery(document).off('click', $linkSelector);";
            $js .= "\njQuery(document).on('click', $linkSelector, function (event) {jQuery.pjax.click(event, $options);});";
        }
        if ($this->formSelector !== false) {
            $formSelector = Json::htmlEncode($this->formSelector !== null ? $this->formSelector : '#' . $id . ' form[data-pjax]');
            $submitEvent = Json::htmlEncode($this->submitEvent);
            $js .= "\njQuery(document).on($submitEvent, $formSelector, function (event) {jQuery.pjax.submit(event, $options);});";
        }



        $view = $this->getView();
        PjaxAsset::register($view);

        if ($js !== '') {
            $view->registerJs($js);
        }
    }
}
