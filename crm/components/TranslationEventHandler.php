<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 20.09.17
 * Time: 16:53
 */

namespace app\components;
use app\models\AppLog;
use yii\i18n\MissingTranslationEvent;

class TranslationEventHandler
{
    public static function handleMissingTranslation(MissingTranslationEvent $event)
    {
//        $event->translatedMessage = "@MISSING: {$event->category}.{$event->message} FOR LANGUAGE {$event->language} @ ";
        $msg = "@MISSING: {$event->category}.{$event->message} FOR LANGUAGE {$event->language} @ ";

        \Yii::info($msg,AppLog::MISSED_TRANSLATION_CATEGORY);
    }
}