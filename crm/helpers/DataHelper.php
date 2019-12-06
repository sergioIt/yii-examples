<?php


namespace app\helpers;


use yii\data\ArrayDataProvider;
use yii\data\Pagination;

class DataHelper
{

    /**
     * Создаёт датапровайдер по ответу из private-api
     *
     * ипользуется везде, где нужна пагинация, которая реализована в private-api по-умолчанию
     *
     * @param array $response
     * @return ArrayDataProvider
     */
    public static function getProviderFromApiResponse(array $response){

        $pagination = new Pagination([
            'totalCount' => $response['_meta']['total'],
            'pageSizeParam' => 'pageSize',
            'pageSize' => $response['_meta']['per_page'],
        ]);

        $dataProvider = new ArrayDataProvider();
        $dataProvider->pagination = $pagination;
        $dataProvider->totalCount = $pagination->totalCount;
        $dataProvider->setModels($response['data']);

        return $dataProvider;
    }
}
