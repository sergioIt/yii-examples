<?php


namespace app\components;


use Fluent\Logger\Entity;

/**
 * Class JsonPacker
 * @package app\components
 */
class JsonPacker extends \Fluent\Logger\JsonPacker
{
    /**
     *  Обёртывает даныне лога в json перед отправкой во fluentd
     *
     * отличие от стандартной обёртки в индексированном масиве, который превращается в json-объект
     * и в том, что в context сделан тоже в виде объекта
     *
     * @param Entity $entity
     * @return false|string
     */
    public function pack(Entity $entity)
    {
        $record = $entity->getData();

        $json = json_encode([
            'tag' => $entity->getTag(),
//            'tag' => 'special',
            'time' => strval(time()),
            'message' => $record[0],
//            'message' => 'pixelen',
            'context' =>  [
                'data' => $record[1],
            ],
            'level' => 1,
            'level_idx' => 'error',
            'log_name' => 'app',
        ]);

        return $json;
    }
}
