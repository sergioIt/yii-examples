<?php


namespace app\components;


use Fluent\Logger\FluentLogger;

/**
 * Class FluentdTarget
 * @package app\components
 */
class FluentdTarget extends \bilberrry\log\FluentdTarget
{
    private $_logger;

    /**
     * здесь устанавливается кастомный json packer,
     * для того чтобы модифицировать стандартный сопособ обёртки даныных в json перед отправкой во fluentd
     */
    public function init()
    {
        parent::init();

        $this->_logger = FluentLogger::open($this->host, $this->port, $this->options);

        $this->_logger->setPacker(new JsonPacker());
    }

}
