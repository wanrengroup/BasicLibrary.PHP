<?php
/**
 * @file   : DateHelperTest.php
 * @time   : 10:54
 * @date   : 2025/4/28
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: Less is more.Simple is best!
 */

namespace WanRen\Test\Data;

use DateTime;
use PHPUnit\Framework\TestCase;
use WanRen\Data\DateHelper;


class DateHelperTest extends TestCase
{
    public function testGetDateTime1(): void
    {
        $actual = DateHelper::getDateTime("2025/04/10");
        $expect = new DateTime("2025-04-10");
        self::assertEquals($expect, $actual);
    }

    public function testGetDateTime2(): void
    {
        $actual = DateHelper::getDateTime(1742777190);
        $expect = new DateTime("2025-03-24T00:46:30.000000+0000");
        self::assertEquals($expect, $actual);
    }

    public function testGetDateTime3(): void
    {
        $original = new DateTime("2025-04-10");
        $actual = DateHelper::getDateTime($original);
        $expect = new DateTime("2025-04-10T00:00:00.000000+0000");
        self::assertEquals($expect, $actual);
    }

    public function testGetDateTime4(): void
    {
        $original = new DateTime("2025-04-10");
        $actual = DateHelper::getDateTime($original);

        $modified = $actual->modify("-1 day");
        self::assertNotEquals($original, $actual);
    }

    public function testAddDays(): void
    {
        $actual = DateHelper::addDays(new DateTime("2025-04-10"), 1);
        $expect = new DateTime("2025-04-11");
        self::assertEquals($expect, $actual);
    }

    public function testAddMonths1(): void
    {
        $actual = DateHelper::addMonths(new DateTime("2025-04-10"), 1);
        $expect = new DateTime("2025-05-10");
        self::assertEquals($expect, $actual);
    }

    public function testAddMonths2(): void
    {
        $actual = DateHelper::addMonths(new DateTime("2025-1-30"), 1);
        $expect = new DateTime("2025-03-02");
        self::assertEquals($expect, $actual);
    }

    public function testAddYears1(): void
    {
        $actual = DateHelper::addYears(new DateTime("2024-02-29"), 1);
        $expect = new DateTime("2025-03-01");
        self::assertEquals($expect, $actual);
    }

    public function testAddYears2(): void
    {
        $actual = DateHelper::addYears(new DateTime("2025-02-28"), 1);
        $expect = new DateTime("2026-02-28");
        self::assertEquals($expect, $actual);
    }

}