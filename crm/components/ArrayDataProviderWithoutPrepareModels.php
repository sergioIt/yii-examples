<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 05.09.2018, 16:55
 */

namespace app\components;

use yii\data\ArrayDataProvider;

/**
 * Class ArrayDataProviderWithoutPrepareModels
 * @package app\components
 */
class ArrayDataProviderWithoutPrepareModels extends ArrayDataProvider
{
    /**
     * Это нужно, если к результату в allModels не нужно применять пагинацию
     *  - когда мы заранее получаем из источника уже нужные нам данные, и поставляем в
     *  ArrayDataProvider
     *
     * @return array
     */
    protected function prepareModels(): array
    {
        if (($models = $this->allModels) === null) {
            return [];
        }

        if (($sort = $this->getSort()) !== false) {
            $models = $this->sortModels($models, $sort);
        }

        return $models;
    }
}
