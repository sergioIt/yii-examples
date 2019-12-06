<?php
/**
 * Created by Valerii Tikhoirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 27.07.2018, 15:39
 */

namespace app\components;

use app\helpers\param\EnvParam;
use index0h\log\ElasticsearchTarget;
use yii\helpers\ArrayHelper;

/**
 * Class ElasticSearchLogTarget
 * @package app\components
 */
class ElasticSearchLogTarget extends ElasticsearchTarget
{
    /** @inheritdoc */
    public function init()
    {
        $this->componentName    = 'elastic-log'; // name of Elasticsearch Yii2 component
        $this->index            = $this->getIndex();
        $this->logVars          = [];
        $this->emergencyLogFile = '@logsDir/elastic-log-emergency.log';

        parent::init();
    }

    /**
     * @return string
     */
    public function getIndex(): string
    {
        return EnvParam::elasticIndex() . '-' . date('Y.m.d');
    }

    /**
     * Convert's any type of log message to array.
     *
     * @param mixed $text Input log message.
     *
     * @return array
     */
    protected function parseText($text): array
    {
        if ($text instanceof \Throwable) {
            return [
                '@message' => $text->getMessage(),
                'stack'    => $text->getTraceAsString(),
                'file'     => $text->getFile(),
                'line'     => $text->getLine(),
            ];
        }

        return parent::parseText($text);
    }

    /**
     * @param array $data
     */
    protected function emergencyPrepareMessages($data)
    {
        foreach ($this->messages as &$message) {
            $message[0] = ArrayHelper::merge((array)$message[0], ['emergency' => $data]);
        }
    }
}
