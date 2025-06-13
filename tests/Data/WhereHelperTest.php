<?php
///**
// * @file   : DateHelperTest.php
// * @time   : 10:54
// * @date   : 2025/4/28
// * @mail   : 9727005@qq.com
// * @creator: ShanDong Xiedali
// * @company: Less is more.Simple is best!
// */
//
//namespace WanRen\Test\Data;
//
//use PHPUnit\Framework\TestCase;
//use WanRen\Data\WhereHelper;
//use WanRen\Test\Basic\DbAssert;
//use WanRen\WorkLayer\GeneralLogic;
//
//// +--------------------------------------------------------------------------
//// |:TITLE:::::::| 使用说明
//// ---------------------------------------------------------------------------
//// |:DESCRIPTION:| 需要先配置数据库表，表结构和数据为：`tests/.SQLs/m_where_helper_testing.sql`
//// +--------------------------------------------------------------------------
//
//
//class WhereHelperTest extends TestCase
//{
//    /**
//     * @param array $where
//     * @param int $expectCount
//     * @return void
//     */
//    private function assertDetails(array $where, int $expectCount = 0): void
//    {
//        $actual = $this->logic->getEntityCount($where);
//        print_r(PHP_EOL . "──分隔符───────────────────────────────────" . PHP_EOL);
//        print_r($this->logic->getLastSql());
//        $expect = $expectCount;
//        self::assertEquals($expect, $actual);
//    }
//
//    protected function setUp(): void
//    {
//        DbAssert::initDb();
//        $this->logic = new GeneralLogic("where_helper_testing", true);
//    }
//
//    public function testEqual(): void
//    {
//        $where = WhereHelper::Equal("grade", 2);
//        $this->assertDetails($where, 4);
//    }
//
//    public function testNotEqual(): void
//    {
//        $where = WhereHelper::NotEqual("grade", 2);
//        $this->assertDetails($where, 5);
//    }
//
//    public function testLike(): void
//    {
//        $where = WhereHelper::Like("alias", "张", "left");
//        $this->assertDetails($where, 2);
//
//        $where = WhereHelper::Like("alias", "张", "all");
//        $this->assertDetails($where, 5);
//
//        $where = WhereHelper::Like("alias", "张", "right");
//        $this->assertDetails($where, 1);
//    }
//
//    public function testNotLike(): void
//    {
//        $where = WhereHelper::NotLike("alias", "张", "left");
//        $this->assertDetails($where, 7);
//
//        $where = WhereHelper::NotLike("alias", "张", "all");
//        $this->assertDetails($where, 4);
//
//        $where = WhereHelper::NotLike("alias", "张", "right");
//        $this->assertDetails($where, 8);
//    }
//
//    public function testLikeAtStringCollection(): void
//    {
//        $where["__or__"] = WhereHelper::LikeAtStringCollection("loves", "橘子", ",");
//        $this->assertDetails($where, 4);
//
//        $where["__or__"] = WhereHelper::LikeAtStringCollection("loves", "篮球", ";");
//        $this->assertDetails($where, 2);
//    }
//
//    public function testBetween(): void
//    {
//        $where = WhereHelper::Between("mobile", "13361108015", "13361108018");
//        $this->assertDetails($where, 4);
//
//        $where = WhereHelper::Between("grade", 3, 4);
//        $this->assertDetails($where, 5);
//    }
//
//    public function testNotBetween(): void
//    {
//        $where = WhereHelper::NotBetween("mobile", "13361108015", "13361108018");
//        $this->assertDetails($where, 5);
//
//        $where = WhereHelper::NotBetween("grade", 3, 4);
//        $this->assertDetails($where, 4);
//    }
//
//    public function testIn(): void
//    {
//        $where = WhereHelper::In("mobile", "13361108015,13361108018");
//        $this->assertDetails($where, 2);
//
//        $where = WhereHelper::In("grade", [3, 2]);
//        $this->assertDetails($where, 7);
//    }
//
//    public function testNotIn(): void
//    {
//        $where = WhereHelper::NotIn("mobile", "13361108015,13361108018");
//        $this->assertDetails($where, 7);
//
//        $where = WhereHelper::NotIn("grade", [3, 2]);
//        $this->assertDetails($where, 2);
//    }
//
//    public function testNull(): void
//    {
//        $where = WhereHelper::Null("create_date");
//        $this->assertDetails($where, 4);
//    }
//
//    public function testNotNull(): void
//    {
//        $where = WhereHelper::NotNull("create_date");
//        $this->assertDetails($where, 5);
//    }
//
//    public function testLessThan(): void
//    {
//        $where = WhereHelper::LessThan("mobile", "13361108014");
//        $this->assertDetails($where, 2);
//
//        $where = WhereHelper::LessThan("grade", 3);
//        $this->assertDetails($where, 4);
//    }
//
//    public function testNotLessThan(): void
//    {
//        $where = WhereHelper::NotLessThan("mobile", "13361108014");
//        $this->assertDetails($where, 7);
//
//        $where = WhereHelper::NotLessThan("grade", 3);
//        $this->assertDetails($where, 5);
//    }
//
//    public function testGreaterThan(): void
//    {
//        $where = WhereHelper::GreaterThan("mobile", "13361108014");
//        $this->assertDetails($where, 6);
//
//        $where = WhereHelper::GreaterThan("grade", 3);
//        $this->assertDetails($where, 2);
//    }
//
//    public function testNotGreaterThan(): void
//    {
//        $where = WhereHelper::NotGreaterThan("mobile", "13361108014");
//        $this->assertDetails($where, 3);
//
//        $where = WhereHelper::NotGreaterThan("grade", 3);
//        $this->assertDetails($where, 7);
//    }
//
//
//}