<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 10.06.19
 * Time: 12:45
 */

namespace app\tests\unit\statistics;


use app\models\VerificationStatistics;
use app\tests\fixtures\AdminFixture;
use app\tests\fixtures\BankBooksLogFixture;
use app\tests\fixtures\CustomerStatusChangesFixture;
use app\tests\fixtures\PaymentChangesFixture;
use app\tests\fixtures\PaymentFixture;
use tests\unit\BaseUnit;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * Class VerificationStatisticsTest
 * @package app\tests\unit\statistics
 */
class VerificationStatisticsTest extends BaseUnit
{


    /** @inheritdoc */
    public function fixtures(): array
    {
        return [
            'usc'        => CustomerStatusChangesFixture::class,
            'admin'        => AdminFixture::class,
            'payment'        => PaymentFixture::class,
            'payment_changes'        => PaymentChangesFixture::class,
            'books' => BankBooksLogFixture::class,
        ];
    }
    /**
     *
     */
    public function testBaseQueryByAdmin(){

        $model = new VerificationStatistics(['scenario' => VerificationStatistics::SCENARIO_VERIFICATION_BY_DAY]);

        $query = $model->baseQueryByAdmin();

        $this->assertInstanceOf(ActiveQuery::class, $query);

        $data = $query->asArray()->indexBy('admin_id')->all();

        $this->assertTrue(count($data) > 0);

        $this->assertArrayHasKey(1, $data);

        $this->assertArrayHasKey('total_accounts_count', $data[1]);
        $this->assertArrayHasKey('unique_customers_count', $data[1]);

    }

    /**
     * проверяет, что пр заданных фильтрах по дате выдаются ожидаемые данные
     */
    public function testGetAccountStat(){

        $model = new VerificationStatistics(['scenario' => VerificationStatistics::SCENARIO_VERIFICATION_BY_DAY]);

        $model->dateFrom = '2019-06-10';
        $model->dateTo = '2019-06-10';

        $stat = $model->getAccountStat();

        $this->assertArrayHasKey(0, $stat);

        $this->assertArrayHasKey('admin_id', $stat[0]);
        $this->assertArrayHasKey('total_accounts_count', $stat[0]);

        $this->assertEquals(2, $stat[0]['admin_id']);

        // при фильтре по админу, ожидаем, что нет данных в задном ранее диапазоне дат
        $model->adminId = 1;

        $this->assertEquals([], $model->getAccountStat());
    }

    /**
     *
     */
    public function testGetPaymentStat(){

        $model = new VerificationStatistics(['scenario' => VerificationStatistics::SCENARIO_VERIFICATION_BY_DAY]);

        $date = '2018-05-04';  // ожидаемая дата изменения

        $model->dateFrom = $date;
        $model->dateTo = $date;

        $stat = $model->getPaymentStat();

        $this->assertArrayHasKey(0, $stat);
        $this->assertArrayHasKey('date', $stat[0]);
        $this->assertArrayHasKey('total', $stat[0]);
        $this->assertArrayHasKey('approved', $stat[0]);
        $this->assertArrayHasKey('declined', $stat[0]);
        $this->assertArrayHasKey('total_customers', $stat[0]);
        $this->assertArrayHasKey('approved_customers', $stat[0]);
        $this->assertArrayHasKey('declined_customers', $stat[0]);
        $this->assertArrayHasKey('error_customers', $stat[0]);
        $this->assertArrayHasKey('error', $stat[0]);
        $this->assertArrayHasKey('admin_id', $stat[0]);
        $this->assertArrayHasKey('customers', $stat[0]);

        $this->assertEquals($date, $stat[0]['date']);
    }

    public function testGetDetailedPaymentsStat(){

        $searchModel = new VerificationStatistics(['scenario' => VerificationStatistics::SCENARIO_VERIFICATION_BY_DAY]);

        $searchModel->adminId = 1;
        $searchModel->setDateTime('2018-05-04');

        $searchModel->validate();

        $data = $searchModel->getDetailedPaymentStat();

        $this->assertArrayHasKey(0, $data);
        $this->assertArrayHasKey(1, $data);

        $payment = $data[0];

        $this->assertArrayHasKey('type', $payment);
        $this->assertArrayHasKey('customer_id', $payment);
        $this->assertArrayHasKey('payment_id', $payment);

        $this->assertEquals('payment',$payment['type']);

        $this->assertEquals(102, $payment['customer_id']);
        $this->assertEquals(22, $payment['payment_id']);

        $payment = $data[1];

        $this->assertEquals(103,$payment['customer_id']);
        $this->assertEquals(24, $payment['payment_id']);
    }

    public function testGetDetailedBankBookStat(){

        $searchModel = new VerificationStatistics(['scenario' => VerificationStatistics::SCENARIO_VERIFICATION_BY_DAY]);

        $searchModel->adminId = 5;
        $searchModel->setDateTime('2018-09-10');

        $searchModel->validate();

        $data = $searchModel->getDetailedBankBookStat();

        $this->assertArrayHasKey(0, $data);

        $this->assertArrayHasKey('type', $data[0]);
        $this->assertArrayHasKey('date', $data[0]);
        $this->assertArrayHasKey('status', $data[0]);
        $this->assertEquals('bank_book', $data[0]['type']);
    }


    public function testGetBankBooksStat(){

        $model = new VerificationStatistics(['scenario' => VerificationStatistics::SCENARIO_VERIFICATION_BY_DAY]);

        $model->dateFrom = '2018-09-10';
        $model->dateTo = '2018-09-10';

        $stat = $model->getBankBookStat();

        $this->assertArrayHasKey(0, $stat);
        $this->assertArrayHasKey('date', $stat[0]);
        $this->assertArrayHasKey('total', $stat[0]);
        $this->assertArrayHasKey('total_customers', $stat[0]);
        $this->assertArrayHasKey('customers', $stat[0]);
    }

    public function testComposeStat(){

        $model = new VerificationStatistics(['scenario' => VerificationStatistics::SCENARIO_VERIFICATION_BY_ADMIN]);

        $composed = $model->composeStat($this->accountStatData(), $this->paymentStatData(), $this->bankBooksStatData());

        $this->assertArrayHasKey('2019-06-17', $composed);
        $this->assertArrayHasKey('2019-06-16', $composed);

        $dayData = $composed['2019-06-17'];

        // проверка данных за день для выбранног юзера
        $this->assertArrayHasKey(116, $dayData);

        $adminData = $dayData[116];

        $this->assertArrayHasKey('total_accounts_count', $adminData);
        $this->assertArrayHasKey('unique_customers_count', $adminData);
        $this->assertArrayHasKey('total_payments_count', $adminData);
        $this->assertArrayHasKey('total_bank_books_count', $adminData);
        $this->assertArrayHasKey('payments_unique_customers', $adminData);
        $this->assertArrayHasKey('bank_books_unique_customers', $adminData);
        $this->assertArrayHasKey('total_count', $adminData);
        $this->assertArrayHasKey('customers', $adminData);

        $this->assertEquals(348, $adminData['total_accounts_count']);
        $this->assertEquals(328, $adminData['unique_customers_count']);
        $this->assertEquals(141, $adminData['total_payments_count']);
        $this->assertEquals(102, $adminData['payments_unique_customers']);
        $this->assertEquals(17, $adminData['total_bank_books_count']);

        $this->assertEquals(13, $adminData['bank_books_unique_customers']);
        $this->assertEquals(506, $adminData['total_count']);

        $this->assertEquals(422,  count(array_unique($adminData['customers'])));

        $summary = $model->summary;

        // проверка summary по всем юзерам
        $this->assertArrayHasKey('total_accounts_count',$summary);
        $this->assertArrayHasKey('total_payments_count', $summary);
        $this->assertArrayHasKey('total_bank_books_count', $summary);
        $this->assertArrayHasKey('total_count', $summary);
        $this->assertArrayHasKey('by_admin', $summary);

        $this->assertEquals(1270, $summary['total_accounts_count']);
        $this->assertEquals(1111,  count(array_unique($model->summary['accounts_customers'])));

        $this->assertEquals(296, $summary['total_payments_count']);
        $this->assertEquals(201,  count(array_unique($model->summary['payments_customers'])));

        $this->assertEquals(45, $summary['total_bank_books_count']);
        $this->assertEquals(30,  count(array_unique($model->summary['bank_books_customers'])));

        $this->assertEquals(1611, $summary['total_count']);
        $this->assertEquals(1243,  count(array_unique(ArrayHelper::merge($summary['accounts_customers'],
            $summary['payments_customers'],
            $summary['bank_books_customers']))));

        // проверка summary по отдельному юзеру
        $this->assertEquals(348, $summary['by_admin'][116]['total_accounts_count']);
        $this->assertEquals(328,count(array_unique( $summary['by_admin'][116]['accounts_customers'])));

        $this->assertEquals(179, $summary['by_admin'][116]['total_payments_count']);
        $this->assertEquals(126,count(array_unique( $summary['by_admin'][116]['payments_customers'])));

        $this->assertEquals(17, $summary['by_admin'][116]['total_bank_books_count']);
        $this->assertEquals(13,count(array_unique( $summary['by_admin'][116]['bank_books_customers'])));

        $this->assertEquals(544, $summary['by_admin'][116]['total_count']);
        $this->assertEquals(444,count(array_unique( $summary['by_admin'][116]['total_customers'])));

    }

    public function testGetRawAccountStatQuery(){

        $model = new VerificationStatistics(['scenario' => VerificationStatistics::SCENARIO_VERIFICATION_BY_ADMIN]);

        $query = $model->getDetailedAccountStatQuery();

        $this->assertInstanceOf(ActiveQuery::class, $query);

        $data = $query->asArray()->all();

       $this->assertTrue(count($data) > 0);
    }

    public function testGetDetailedAccountStat(){

        $model = new VerificationStatistics(['scenario' => VerificationStatistics::SCENARIO_VERIFICATION_BY_ADMIN]);

        $model->adminId = 2;

        $date = '2019-06-10';

        $model->setDateTime($date);

        $model->validate();

        $data = $model->getDetailedAccountStat();

        $this->assertArrayHasKey(0,$data);
        $this->assertArrayHasKey('type', $data[0]);
        $this->assertArrayHasKey('date', $data[0]);
        $this->assertArrayHasKey('status', $data[0]);
        $this->assertArrayHasKey('comment', $data[0]);
        $this->assertEquals('account', $data[0]['type']);

    }

    /**
     * @return array
     */
    protected function accountStatData(){

     return array (
         0 =>
             array (
                 'date' => '2019-06-17',
                 'admin_id' => 1,
                 'total_accounts_count' => 1,
                 'unique_customers_count' => 1,
                 'customers' => '2127655',
             ),
         1 =>
             array (
                 'date' => '2019-06-17',
                 'admin_id' => 82,
                 'total_accounts_count' => 2,
                 'unique_customers_count' => 1,
                 'customers' => '2013480,2013480',
             ),
         2 =>
             array (
                 'date' => '2019-06-17',
                 'admin_id' => 116,
                 'total_accounts_count' => 348,
                 'unique_customers_count' => 328,
                 'customers' => '2210178,2208768,2136597,2215816,2215582,2083446,2159520,2187912,2215209,1804633,2207776,2208128,2184045,2209275,2209292,2210374,2211784,2102587,2212262,2151037,2139646,2213903,2207772,2212848,2187076,2209299,1878790,2213762,2213133,2150340,2181955,2213864,2197023,2214336,2094890,2202210,2208281,2209119,2212331,2208727,2215584,2213924,2213381,2213203,2210817,2144669,2208784,2103688,2210119,2104617,1224637,2211307,2213808,2174866,2096304,2137986,2126992,2200065,2211738,2151593,1533229,2208315,2208146,2208229,1834376,2209606,2209722,2209827,2210097,2211478,2214336,2214343,2096860,2212879,1062529,888951,2175221,2209641,2209848,2103688,2174866,1698437,2088132,2213012,2104028,479697,2134070,2140522,2209151,2210363,2069398,2210743,2182488,2199012,2214187,2212865,2210564,1584781,2209241,2209389,2211025,2215824,2063322,2112397,2046138,2159277,2205563,2206095,2197009,2042620,2083651,2094654,2191465,2209204,2081143,2210476,2134174,1309973,2213373,2213977,2068214,2111641,1534634,2213286,2204106,2205156,2208964,2209732,2210261,2211122,2210480,2209822,2211519,2211524,1812960,2211233,2211292,1794531,2036835,2211564,2206414,2199184,2210642,2213396,2208725,2211138,1778244,2211055,2212161,2201568,2168135,2206921,2209852,2214078,1918055,2136597,2206819,2210309,2070555,2211079,1834376,1168575,2213872,2213463,2213758,2207913,2212446,2210227,2174176,2206927,2209151,2211935,2213929,2209864,2108016,2192574,2209895,2214077,2212739,2202685,2211152,2209121,1347460,2213871,2109632,2194155,2205637,1794531,2213200,2211419,1889548,2187966,2200841,2202487,2204173,1777005,2088877,2211236,1930360,2212775,2215781,2206397,2210065,2208657,2204149,2206095,2211186,2211536,2207573,1776078,2176828,2196747,2206612,1588242,2191969,1901357,2207329,2215247,2190129,2211283,2213077,2211836,2207539,2181859,2186928,2211153,1958492,2010829,2208052,2036835,2172609,2211738,2091891,2212877,1569015,2200196,1843265,2207010,2175686,2211305,2071712,2213588,2207426,2214024,2190277,2211649,2146433,2204106,2185768,2211872,2208435,2207988,2208850,2210810,2173878,2200155,2213208,2214477,2119351,2206485,2214651,2099287,1801093,2213133,2107739,2213477,2213140,2004299,2151128,2213446,2213446,2215131,2211794,2084586,2214366,2212530,2198387,2210456,2212135,2210810,2214591,2214078,2104028,2206578,1938000,2211781,2123058,2213489,2214154,2190164,2084585,2026670,2178033,2213872,2208710,1978948,2103688,2207816,2211813,2119310,2181453,2214403,2086889,2183220,2212112,2212599,2211738,1225260,2199604,2207759,2213100,1866796,2212960,2204741,2209191,2017362,2211322,2211710,2079266,2113961,2191730,1388709,2043620,1879141,2155803,2061096,2121145,2208099,2180782,2211872,2195121,2215619,2202384,2207519,1776762,1798519,2206979,2180254,2095728,1722027,1656164,1867566,2213979,888951,2208701,2208782,2209335,1999949,2155829',
             ),
         3 =>
             array (
                 'date' => '2019-06-17',
                 'admin_id' => 299,
                 'total_accounts_count' => 327,
                 'unique_customers_count' => 305,
                 'customers' => '2207179,2206834,1775835,2162456,2082760,2210116,2208533,2208304,2211646,2205985,2206913,2212741,1776762,1987052,2194155,2214603,2140534,2207566,2211541,2215028,1596547,2175686,2210116,2210872,2212172,2202985,2211664,2214477,2115769,2210270,2115990,1832739,2215635,2215645,2055692,2215287,2210270,2190164,2201236,2210967,2198100,888951,2128076,2207929,1912778,1834376,2133102,2201666,2209144,1395919,2206333,2209749,2209827,2212070,2212900,2208022,2208409,2026785,1127831,2211895,1833283,2207180,2212704,2207509,1843265,2212973,2197023,2189299,1439939,2208966,2212913,2209334,1289917,2070538,2206721,2207912,1855814,2173426,1501199,2084585,2210274,2149602,2211094,2206321,2070538,2188785,1996149,2211605,2208472,2202384,2183044,2208510,2075637,2208644,2208640,2196938,2191969,1907349,2208976,1506453,2173771,2210270,1395962,2202210,2209164,1973440,2207948,2208541,888951,1907769,2144669,1430132,2061096,786506,1971391,2208441,2215822,2215545,2214805,2207520,1853174,2155656,2188505,2210525,2208994,2212454,2197263,2129044,2211423,2078847,2067956,2214822,2212930,2185802,2207948,2214575,2202512,2010374,2206096,2209091,2210855,2209413,2207948,2208850,2209662,2208651,1777358,2180291,2206411,2211563,2211845,2211580,2010374,2212790,2182488,2210052,2169239,1407878,2212296,2210141,1973440,2202755,1814753,2209196,2212963,1497638,1235566,2190318,2181308,2207211,2105862,2209791,2212642,2209888,1721978,2210236,1168551,2211893,2208542,2208065,1777005,2174866,1457671,2119215,2122264,2140534,2191969,2205914,1128292,1869917,786506,2012188,2014438,2199768,1735323,2062137,2213918,2188406,2040102,1409928,2211522,2155021,2180782,2210107,2027557,2210880,2041474,2210245,2210458,2210479,2210986,2206485,2207956,2210212,2211556,2215188,2027717,2197321,2053938,2213918,2207607,2131694,2029992,2202210,2040289,2211365,2207948,2171515,2204538,2211841,2207825,2210296,2205329,2170250,2210932,2183530,2214584,2208953,2210236,2210613,2211629,2215568,2215368,1821085,2210236,2039340,2211337,2213643,1834376,1713025,2196548,2153550,1589953,2200065,1834186,2207090,1777525,2133256,1994636,2205438,2202248,2212530,2207755,2211872,2206392,2211926,2209623,2210926,2208337,2210525,1749017,1731501,1514350,2149998,2187143,2212419,2210148,2210280,2028255,2210377,2179048,2202210,2212778,1568339,1939970,2203092,2204511,1538409,2208784,1959775,2166953,940955,2021744,2186802,2196853,2204777,1024159,2207402,2210564,2211738,2208166,2210806,2206285,2208277,2214471,2209930,1651509,1932013,2210270,2179146,1822654,1289917,2211160,2191474,2110678,2198732,1388709,2158206,2147456,2205087,2151264,2202586,2208044,2211032,1964543,2214749,2215170',
             ),
         4 =>
             array (
                 'date' => '2019-06-16',
                 'admin_id' => 23,
                 'total_accounts_count' => 237,
                 'unique_customers_count' => 217,
                 'customers' => '2195368,1876490,1970204,2152778,1777358,2038847,2141574,2149819,1752178,2174866,2203731,2140046,2202920,2073590,2200642,2190720,1561554,2157703,2201684,2200530,2149998,2155014,2200642,1978905,2198876,2058234,2155586,2150631,620198,2183525,2053645,2190112,2195368,1910521,2202989,2201153,2073197,2152041,2200536,2091230,1435526,2204334,2055432,1455807,2178542,2191924,2102033,2134145,2203174,2202472,2203998,2191403,2181374,2184241,2205228,2203674,2075885,1813185,2152041,1585872,2167921,2203056,2188586,2201401,2205228,2054043,2159448,2167640,2171094,1435526,2201887,2202195,2199180,2003450,2067812,2070538,2188892,2134834,2063601,1962377,2192959,2200157,2204087,2201872,2200977,2188418,2201602,2191469,1695287,2203092,2203106,2180782,2194104,2201128,2199165,2199379,2204833,2204311,2192316,2204687,2193792,2201445,2203266,2203346,2203396,2199097,2200398,2203660,2191403,2200606,2201090,2195788,2200324,1668014,2093012,2194573,2199395,2204833,2174866,2201325,2174744,1690932,2205084,2119627,2199718,2192005,1936804,2199324,2197581,2200525,2201362,2200598,1889759,2137189,2196460,2203728,2171503,2200922,2200696,1569345,1690932,2113587,2006264,2200955,2203679,2205228,2169995,2201196,2140623,2200449,1868750,2201580,1805948,2039340,2180782,2201540,1718229,2204681,2196341,1777358,2108978,1879918,758364,758364,2202616,2200530,2205228,2201648,2068957,2202802,2203699,2204665,2180610,2203350,2201963,2185082,2162564,2203882,2158206,1867095,2148587,2196616,2198358,2202454,2093012,1494387,2204265,2204618,2168526,2201703,2202237,2199937,1528422,1944655,2189365,1207730,2205272,2207016,1805948,2203994,2194155,2200282,2173705,2199337,2203207,1858343,2002945,2203449,2204072,2202794,1777005,2078711,2121319,2198358,1477113,2203877,2207022,1936722,1891096,2145509,1778900,1585872,2205387,1583693,1872524,2205228,2202702,2205182,2140503,1821464,2172211,2205253,2205647,2204682,1518594,1630667,2205711',
             ),
         5 =>
             array (
                 'date' => '2019-06-16',
                 'admin_id' => 81,
                 'total_accounts_count' => 354,
                 'unique_customers_count' => 326,
                 'customers' => '2201901,2203312,2205335,2202780,2202641,2202228,2203917,2205664,2200530,2203810,2199678,1989353,2203354,2201131,1570311,2198921,2201890,1984112,2201020,2201306,2204860,2206264,1650946,2206766,2207021,2202655,2200072,2201825,2160510,2197291,2203355,2202652,2186294,2205718,2205708,2202085,1987952,2188707,2207632,2198801,2206039,1971882,1604749,2202191,2205715,2197807,2207733,2205408,2199893,2200134,2200447,2127655,2199562,2192967,2204308,2201974,2204915,2070966,2127786,2171503,2191996,2189827,2206582,1965521,2201677,1061536,2204156,2196747,1668665,2205054,2124347,2200967,1713025,2206565,2200516,2206281,1461285,2202589,2187068,2202582,2201666,2202507,2202481,2202186,2207678,2205515,2206493,2192967,2205073,2200072,1983345,2190720,2191128,2196523,2200636,2203275,1880441,2207818,2202392,2204776,1313437,2207153,1853946,2207503,2087541,2201942,2203445,2202191,2197283,2202345,1802034,2159049,2199249,2192967,2200063,1526212,2207409,2204692,2204938,2204981,2205323,2201939,2039340,1360524,2205084,2201365,2193284,2201334,2185605,2202748,2201626,2100319,2189092,2079210,2199674,2200939,2201270,2201901,1292340,2200121,1526212,2197900,2207578,1822036,1919798,2148749,2188707,2194104,2203809,2201586,2201130,2202458,2202822,2105515,2200530,2196401,2082738,1975572,2200039,2201967,2201569,1135229,2207502,2202193,2106371,1965228,2091847,2196460,2174664,2202810,2205003,1974764,1927958,2203671,2200630,1715278,1976153,2203017,2198810,2202949,2203993,2206640,2008106,2193811,2200116,2200568,2206877,2158206,2206658,2168526,2195278,2200354,2204606,2084586,2204906,2204308,976971,2207211,2199155,2207427,2205045,2205279,1742310,2200000,2200134,2200251,2203933,2200402,964165,2201939,2202573,2204843,2205066,2203922,2206155,2093012,2193996,2198987,2203933,2201257,2207425,2070538,1978905,2205942,2203719,2203242,2206042,2203954,2203250,2203763,2206565,2206028,2205228,2204033,2197009,2198469,2203946,2199589,2201131,2205173,2194464,2205346,2200280,2197695,1506453,2204194,2203410,2203104,2206422,2205923,2206128,1707761,2205929,2202785,2200656,2200630,2150630,2200670,2206074,1483482,2203427,2196791,2202711,2204041,2203023,2202573,2203763,2203477,2200816,2187068,2204039,2207653,2204852,2002945,2200111,2205513,2194930,2196401,2205076,2202458,2206224,2203255,2202787,2202784,2144805,2204513,2206264,2182657,2202695,2196791,2202029,2139454,2206039,2205792,2205893,2205868,1254262,2202485,2189940,2205812,2203671,2191403,2203852,2203772,2202487,2202925,2202393,2205228,2195081,2198194,2206349,2206373,2183530,2089966,2053645,2203542,2203143,2202322,2006031,2202296,2183936,2185664,2188050,2201355,2202988,2201069,2202810,2205006,1861622,2202134,1942116,2205767,2205423,1513691,2202892,2195681,2198732,2205256,2207555,2204173,2191035,2207120,2205539,2202656,2131420,2207234,2207220,1989384,2201411,2207188,1923586,2205878,2205076,2201355',
             ),
         6 =>
             array (
                 'date' => '2019-06-16',
                 'admin_id' => 82,
                 'total_accounts_count' => 1,
                 'unique_customers_count' => 1,
                 'customers' => '2013480',
             ),
     );

    }

    /**
     * @return array
     */
    protected function paymentStatData(){

     return array (
         0 =>
             array (
                 'date' => '2019-06-17',
                 'total' => 141,
                 'total_customers' => 102,
                 'approved' => 90,
                 'approved_customers' => 80,
                 'error' => 0,
                 'error_customers' => 0,
                 'declined' => 51,
                 'declined_customers' => 29,
                 'admin_id' => 116,
                 'customers' => '2214599,1918133,2214340,2211307,2213203,2214340,1889018,2210363,2213926,2210346,2215857,1813429,1813429,2213477,2208651,621207,2197746,1994998,2209825,2197746,2052493,1528065,2210160,2151817,2210160,1965288,430381,2197291,1467160,2210160,2210160,2210160,2209164,2209164,2209164,2210309,2141941,1281873,2021055,2105797,2212161,2209787,2209787,2213657,2209787,2212161,698979,2213203,2210346,2187143,2213077,2210810,2021055,2209787,2211556,2185604,2212840,2209787,1965175,2007662,2211872,2212476,2012188,2012188,1944393,2021055,2188785,2191969,2212070,1694439,2019576,2211360,2206411,1328128,2207120,2116988,2186563,2019576,1809004,2211541,2202685,2019576,2205329,2019576,2211555,2211227,2211555,2211555,2211423,2207220,2205515,2177395,1906976,1101738,2177395,2178033,2019576,2019576,2210967,2207090,2210806,2215751,2210806,2210967,2179409,2205929,2137035,1907769,2210841,2178962,1853845,2210810,2210160,2210236,2210642,982831,2215490,2211794,2133102,781358,2215646,2210405,1965175,2215247,2212963,2178774,1045263,1880655,2215318,2213477,2069772,1813429,2179409,1926694,2021055,2214927,2209787,2194155,1861155,2213477,1813429',
             ),
         1 =>
             array (
                 'date' => '2019-06-16',
                 'total' => 38,
                 'total_customers' => 28,
                 'approved' => 14,
                 'approved_customers' => 14,
                 'error' => 0,
                 'error_customers' => 0,
                 'declined' => 24,
                 'declined_customers' => 16,
                 'admin_id' => 116,
                 'customers' => '1360524,2202288,2206039,1528065,1926694,2207725,2207826,2207793,2019576,2019576,2019576,544330,1949078,2011931,1949078,2208326,2208326,2201145,2088877,2208326,2180269,1552058,2201355,2208640,2011931,2208651,1824131,1430604,2019576,2208651,2209196,2119215,2208651,1885110,2209436,2011931,2150117,2209739',
             ),
         2 =>
             array (
                 'date' => '2019-06-16',
                 'total' => 116,
                 'total_customers' => 87,
                 'approved' => 86,
                 'approved_customers' => 67,
                 'error' => 0,
                 'error_customers' => 0,
                 'declined' => 30,
                 'declined_customers' => 25,
                 'admin_id' => 81,
                 'customers' => '2092687,2007465,544330,2207180,2197291,976971,2082738,2178962,2206039,2196877,2021055,2206877,2202573,2207180,2207085,2199678,2199678,544330,2205929,2197291,2206493,2206038,2200280,2178962,1140839,544330,2206028,2206300,2205929,1360524,2206028,1707761,2205942,2206039,2068960,1419751,1880655,2205319,2205084,2192649,2199678,2192649,2205664,2166483,2196877,2205623,2196877,2205539,2196877,2169874,2205408,2205003,1986251,2203449,1983345,2173402,2200280,1528065,2196388,2204687,2205084,2196877,2151390,2192982,2202573,2204610,2150630,1768247,2191474,2204087,2204094,2196388,2058234,2102033,2196877,982831,2194138,2203917,2002945,2203216,2199678,2203449,2203216,2170519,1506416,2203017,1203549,2196877,2203350,2203017,1853798,2198533,1203549,1742152,2203017,2201355,2199253,2202880,2202979,2203017,2198430,2202957,2161467,2197746,2106371,2106371,2201130,2202345,2201967,1657205,2202392,2165603,1456271,2176651,1965175,1412584',
             ),
         3 =>
             array (
                 'date' => '2019-06-16',
                 'total' => 1,
                 'total_customers' => 1,
                 'approved' => 0,
                 'approved_customers' => 0,
                 'error' => 0,
                 'error_customers' => 0,
                 'declined' => 1,
                 'declined_customers' => 1,
                 'admin_id' => 1,
                 'customers' => '2127655',
             ),
     );


    }

    /**
     * @return array
     */
    protected function bankBooksStatData(){

       return array (
           0 =>
               array (
                   'date' => '2019-06-17',
                   'admin_id' => 116,
                   'total' => 17,
                   'total_customers' => 13,
                   'customers' => '1461285,2207022,2201355,1907769,1907769,2199184,1870852,1870852,2210967,2212070,2212070,1906976,2156192,2160573,1419751,1557084,1557084',
               ),
           1 =>
               array (
                   'date' => '2019-06-16',
                   'admin_id' => 23,
                   'total' => 28,
                   'total_customers' => 20,
                   'customers' => '2197011,2202979,2060790,2060790,2177023,2177023,1140839,1140839,1051571,2206766,2202345,2207022,1896218,2196071,2196071,2156192,2202989,2143049,2143049,1978905,1906976,2203017,2203017,1880441,2199253,2205084,2205084,2197011',
               ),
       );

    }
}
