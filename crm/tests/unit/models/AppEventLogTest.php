<?php
/**
 * Created by Valerii Tikhomirov
 * E-mail: <v.tikhomirov.dev@gmail.com>
 * Date: 16.05.2018, 13:23
 */

namespace tests\models;

use app\models\AppEventsLog;
use app\models\AppEventsType;
use tests\unit\BaseUnit;
use tests\fixtures\AppEventsTypeFixture;

/**
 * Class AppEventLogTest
 * @package tests\models
 */
class AppEventLogTest extends BaseUnit
{
    /** @inheritdoc */
    public function fixtures(): array
    {
        return [
            'app_events_type' => AppEventsTypeFixture::class,
        ];
    }

    /**
     * Test write log
     */
    public function testWriteLog()
    {
        $writeLog = AppEventsLog::log(
            AppEventsType::TYPE_AFFECTED_CUSTOMER_RESET_AFFECTED_DATE,
            1,
            'Log message'
        );

        $this->assertTrue($writeLog);

        $writeLog = AppEventsLog::log(
            999,
            1,
            'Log message'
        );

        $this->assertFalse($writeLog);
    }
}
