<?php
/**
 * @file   : DateHelperTest.php
 * @time   : 10:54
 * @date   : 2025/4/28
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: Less is more.Simple is best!
 */

namespace Wanren\Test\Basic;

use PHPUnit\Framework\TestCase;
use Wanren\LogicLayer\GeneralLogic;
use Wanren\Data\RandHelper;

// +--------------------------------------------------------------------------
// |:TITLE:::::::| 使用说明
// ---------------------------------------------------------------------------
// |:DESCRIPTION:| 需要先配置数据库表，表结构为：`tests/.SQLs/m_abstract_logic_testing.sql`
// +--------------------------------------------------------------------------


class AbstractLogicTest extends TestCase
{
    private GeneralLogic $logicIsolated;
    private static string $targetTable = "abstract_logic_testing";

    /**
     * @param GeneralLogic $logic
     * @return void
     */
    public function _testUpdate(GeneralLogic $logic): void
    {
        $mobile = RandHelper::generateRandomString(11);
        $entity = ["name" => "test1", "mobile" => $mobile, "create_date" => date("Y-m-d H:i:s")];

        $id = $logic->addEntity($entity);
        //$gotten = $logic->getEntity(["id" => $id]);
        $gotten = $logic->getEntity(["mobile" => $mobile]);

        $gotten["name"]  = "山东解大劦";
        $gotten["grade"] = 3;

        print_r(PHP_EOL . "──gotten Start───────────────────────────────────" . PHP_EOL);
        print_r($gotten);
        print_r(PHP_EOL . "──gotten End───────────────────────────────────" . PHP_EOL);

        $updated = $logic->updateEntity($gotten);

        print_r("──Updated 数据结果───────────────────────────────────" . PHP_EOL);
        print_r($updated);
        print_r(PHP_EOL . "──分隔符───────────────────────────────────" . PHP_EOL);

        $result = $logic->getEntity(["mobile" => $mobile]);


        $expect = "山东解大劦";
        $actual = $result["name"];
        self::assertEquals($expect, $actual);

        $expect = 3;
        $actual = $result["grade"];
        self::assertEquals($expect, $actual);
    }

    /**
     * @param GeneralLogic $logic
     * @return array
     */
    public function _testUpdateMulti(GeneralLogic $logic): array
    {
        $mobile = RandHelper::generateRandomString(11);

        $entity1 = ["name" => "testA", "mobile" => $mobile, "create_date" => date("Y-m-d H:i:s")];
        $entity2 = ["name" => "testB", "mobile" => $mobile, "create_date" => date("Y-m-d H:i:s")];

        $logic->addEntity($entity1);
        $logic->addEntity($entity2);
        $listGotten = $logic->getEntities(["mobile" => $mobile]);

        if ($listGotten && count($listGotten) >= 1) {
            $first          = $listGotten[0];
            $first["name"]  = "山东解大劦1";
            $first["grade"] = 3;
            $updated        = $logic->updateEntity($first);

            print_r("──Updated 数据结果───" . PHP_EOL);
            print_r($updated);
            print_r(PHP_EOL . "──分隔符───" . PHP_EOL);

            $second          = $listGotten[1];
            $second["name"]  = "山东解大劦2";
            $second["grade"] = 4;
            $updated         = $logic->updateEntity($second);

            print_r("──Updated 数据结果───" . PHP_EOL);
            print_r($updated);
            print_r(PHP_EOL . "──分隔符───" . PHP_EOL);
        }

        return $logic->getEntities(["mobile" => $mobile]);
    }

    /**
     * @param GeneralLogic $logic
     * @return array
     */
    public function _testGetMulti(GeneralLogic $logic): array
    {
        $mobile1 = RandHelper::generateRandomString(11);
        $mobile2 = RandHelper::generateRandomString(11);

        $entity1 = ["name" => "test1", "mobile" => $mobile1, "create_date" => date("Y-m-d H:i:s")];
        $entity2 = ["name" => "test1", "mobile" => $mobile2, "create_date" => date("Y-m-d H:i:s")];
        $logic->addEntity($entity1);
        $logic->addEntity($entity2);
        $result1 = $logic->getEntity(["mobile" => $mobile1]);

        // 非隔离模式下，本次的where条件会叠加前次的where条件，导致结果不准确。
        //此处返回结果为null，因为没有符合条件的记录。
        $result2 = $logic->getEntity(["mobile" => $mobile2]);


        print_r("──Gotten 数据结果:───────────────────────────────────" . PHP_EOL);
        print_r($result1);
        print_r(PHP_EOL . "──分隔符───────────────────────────────────" . PHP_EOL);
        print_r($result2);
        print_r(PHP_EOL . "──分隔符───────────────────────────────────" . PHP_EOL);
        return array($mobile1, $result1, $mobile2, $result2);
    }

    /**
     * @param GeneralLogic $logic
     * @return array
     */
    public function _testAddMulti(GeneralLogic $logic): array
    {
        $oldCount = $logic->getEntityCount();
        $entity1  = ["name" => "test1", "mobile" => "28345678901", "create_date" => date("Y-m-d H:i:s")];
        $result1  = $logic->addEntity($entity1);

        $entity2  = ["mobile" => "38345678901", "create_date" => date("Y-m-d H:i:s")];
        $result2  = $logic->addEntity($entity2);
        $newCount = $logic->getEntityCount();


        print_r("──新增数据结果:───────────────────────────────────" . PHP_EOL);
        print_r($result1);
        print_r(PHP_EOL . "──分隔符───────────────────────────────────" . PHP_EOL);
        print_r($result2);
        print_r(PHP_EOL . "──分隔符───────────────────────────────────" . PHP_EOL);
        return array($oldCount, $newCount);
    }

    /**
     * @return int
     */
    public function _addEntities(): int
    {
        $create_date = date("Y-m-d H:i:s");

        $entity1 = ["name" => "test1", "mobile" => "12334567890", "grade" => 2, "create_date" => $create_date];
        $entity2 = ["name" => "test2", "mobile" => "12334567890", "grade" => 3, "create_date" => $create_date];
        $entity3 = ["name" => "test3", "mobile" => "12334567891", "grade" => 2, "create_date" => $create_date];
        $entity4 = ["name" => "test4", "mobile" => "12334567891", "grade" => 2, "create_date" => $create_date];

        return $this->logicIsolated->addEntities($entity1, $entity2, $entity3, $entity4);
    }


    protected function setUp(): void
    {
        DbAssert::initDb();
        $this->logicIsolated = new GeneralLogic(self::$targetTable, true);
    }

    public function testAdd(): void
    {
        $oldCount = $this->logicIsolated->getEntityCount();
        $entity   = ["name" => "test1", "mobile" => "18345678901", "create_date" => date("Y-m-d H:i:s")];
        $result   = $this->logicIsolated->addEntity($entity);
        $newCount = $this->logicIsolated->getEntityCount();
        $expect   = $oldCount + 1;
        $actual   = $newCount;

        print_r("──新增数据结果───────────────────────────────────" . PHP_EOL);
        print_r($result);
        print_r(PHP_EOL . "──分隔符───────────────────────────────────" . PHP_EOL);

        self::assertEquals($expect, $actual);
    }

    public function testAddMultiIsolate(): void
    {
        [$oldCount, $newCount] = $this->_testAddMulti($this->logicIsolated);

        $expect = $oldCount + 2;
        $actual = $newCount;
        self::assertEquals($expect, $actual);
    }

    public function testAddMultiNormal(): void
    {
        $logic = new GeneralLogic(self::$targetTable);

        [$oldCount, $newCount] = $this->_testAddMulti($logic);

        $expect = $oldCount + 2;
        $actual = $newCount;
        self::assertEquals($expect, $actual);
    }

    public function testAddEntities(): void
    {
        $oldCount = $this->logicIsolated->getEntityCount();

        $result   = $this->_addEntities();
        $newCount = $this->logicIsolated->getEntityCount();

        $expect = $oldCount + 4;
        $actual = $newCount;

        print_r("──Gotten 数据结果───────────────────────────────────" . PHP_EOL);
        print_r($result);
        print_r(PHP_EOL . "──分隔符───────────────────────────────────" . PHP_EOL);

        self::assertEquals($expect, $actual);
    }

    public function testUpdate(): void
    {
        $logic = new GeneralLogic(self::$targetTable, true);

        $this->_testUpdate($logic);
    }

    public function testUpdateMultiNormal(): void
    {
        $logic = new GeneralLogic(self::$targetTable);

        $listGotten = $this->_testUpdateMulti($logic);

        /**
         * 如果使用非隔离模式，则会导致结果不准确。
         */
        if ($listGotten && count($listGotten) >= 1) {
            $first  = $listGotten[0];
            $expect = "testA";
            $actual = $first["name"];
            self::assertEquals($expect, $actual);
            $actual = $first["grade"];
            self::assertNull($actual);

            $second = $listGotten[1];
            $expect = "testB";
            $actual = $second["name"];
            self::assertEquals($expect, $actual);
            $actual = $second["grade"];
            self::assertNull($actual);
        }
    }

    public function testUpdateMultiIsolate(): void
    {
        $logic = $this->logicIsolated;

        $listGotten = $this->_testUpdateMulti($logic);

        if ($listGotten && count($listGotten) >= 1) {
            $first  = $listGotten[0];
            $expect = "山东解大劦1";
            $actual = $first["name"];
            self::assertEquals($expect, $actual);
            $expect = 3;
            $actual = $first["grade"];
            self::assertEquals($expect, $actual);

            $second = $listGotten[1];
            $expect = "山东解大劦2";
            $actual = $second["name"];
            self::assertEquals($expect, $actual);
            $expect = 4;
            $actual = $second["grade"];
            self::assertEquals($expect, $actual);
        }
    }

    public function testSave(): void
    {
        $logic = $this->logicIsolated;

        $mobile1 = RandHelper::generateRandomString(11);
        $mobile2 = RandHelper::generateRandomString(11);
        $entity1 = ["name" => "test1", "mobile" => $mobile1, "grade" => 2, "create_date" => date("Y-m-d H:i:s")];
        $entity2 = ["name" => "test2", "mobile" => $mobile2, "grade" => 3, "create_date" => date("Y-m-d H:i:s")];

        $listGotten = $logic->addEntities($entity1, $entity2);
        $expect     = 2;
        $actual     = $listGotten;
        self::assertEquals($expect, $actual);

        $first = $logic->getEntity(["mobile" => $mobile1]);

        $first["name"]  = "山东解大劦A";
        $first["grade"] = 5;

        $updated = $logic->saveEntity($first);

        $expect = 1;
        $actual = $updated;
        self::assertEquals($expect, $actual);

        $gotten = $logic->getEntity(["mobile" => $mobile1]);

        $expect = "山东解大劦A";
        $actual = $gotten["name"];
        self::assertEquals($expect, $actual);

        $mobile3 = RandHelper::generateRandomString(11);
        $entity3 = ["name" => "testChina", "mobile" => $mobile3, "grade" => 2, "create_date" => date("Y-m-d H:i:s")];

        $saved  = $logic->saveEntity($entity3);
        $expect = 1;
        if ($saved) {
            $actual = 1;
        }
        self::assertEquals($expect, $actual);
    }

    public function testGet(): void
    {
        $mobile = RandHelper::generateRandomString(11);

        $entity = ["name" => "test1", "mobile" => $mobile, "create_date" => date("Y-m-d H:i:s")];
        $this->logicIsolated->addEntity($entity);
        $result = $this->logicIsolated->getEntity(["mobile" => $mobile]);

        $expect = $mobile;
        $actual = $result["mobile"];

        print_r("──Gotten 数据结果───────────────────────────────────" . PHP_EOL);
        print_r($result);
        print_r(PHP_EOL . "──分隔符───────────────────────────────────" . PHP_EOL);

        self::assertEquals($expect, $actual);
    }

    public function testGetMultiIsolate(): void
    {
        [$mobile1, $result1, $mobile2, $result2] = $this->_testGetMulti($this->logicIsolated);

        $expect = $mobile1;
        $actual = $result1["mobile"];
        self::assertEquals($expect, $actual);

        $expect = $mobile2;
        $actual = $result2["mobile"];
        self::assertEquals($expect, $actual);
    }

    public function testGetMultiNormal(): void
    {
        $logic = new GeneralLogic(self::$targetTable);

        [$mobile1, $result1, $mobile2, $result2] = $this->_testGetMulti($logic);

        /** @noinspection all */
        $nonUsing = $mobile2;

        $expect = $mobile1;
        $actual = $result1["mobile"];
        self::assertEquals($expect, $actual);

        $expect = null;
        $actual = $result2;
        self::assertEquals($expect, $actual);
    }

    public function testCountIsolate(): void
    {
        $logic = $this->logicIsolated;

        $where[] = ["id", ">", 0];
        $logic->deleteEntities($where);
        $this->_addEntities();

        $actual = $logic->getEntityCount();
        $expect = 4;
        self::assertEquals($expect, $actual);

        $where[] = ["grade", "=", 2];
        //$where[] = ["mobile", "=", "12334567890"];
        $actual = $logic->getEntityCount($where);
        $expect = 3;
        self::assertEquals($expect, $actual);

        $condition[] = ["mobile", "=", "12334567890"];
        $actual      = $logic->getEntityCount($condition);
        $expect      = 2;
        self::assertEquals($expect, $actual);
    }

    public function testCountNormal(): void
    {
        $logic = new GeneralLogic(self::$targetTable);

        $where[] = ["id", ">", 0];
        $logic->deleteEntities($where);
        $this->_addEntities();

        $where[] = ["grade", "=", 2];
        $actual  = $logic->getEntityCount($where);
        $expect  = 3;
        self::assertEquals($expect, $actual);

        $condition[] = ["mobile", "=", "12334567890"];
        // 非隔离模式下，本次的where条件会叠加前次的where条件，导致结果不准确。
        $actual = $logic->getEntityCount($condition);
        $expect = 1;
        self::assertEquals($expect, $actual);
    }

    public function testMax(): void
    {
        $logic = $this->logicIsolated;

        $where[] = ["id", ">", 0];
        $logic->deleteEntities($where);
        $this->_addEntities();

        $actual = $logic->getEntityMax("grade");
        $expect = 3;
        self::assertEquals($expect, $actual);

        $where[] = ["grade", "=", 2];
        $actual  = $logic->getEntityMax("grade", $where);
        $expect  = 2;
        self::assertEquals($expect, $actual);
    }

    public function testMin(): void
    {
        $logic = $this->logicIsolated;

        $where[] = ["id", ">", 0];
        $logic->deleteEntities($where);
        $this->_addEntities();

        $actual = $logic->getEntityMin("grade");
        $expect = 2;
        self::assertEquals($expect, $actual);

        $where[] = ["grade", "=", 3];
        $actual  = $logic->getEntityMin("grade", $where);
        $expect  = 3;
        self::assertEquals($expect, $actual);
    }

    public function testAvg(): void
    {
        $logic = $this->logicIsolated;

        $where[] = ["id", ">", 0];
        $logic->deleteEntities($where);
        $this->_addEntities();

        $actual = $logic->getEntityAvg("grade");
        $expect = 2.25;
        self::assertEquals($expect, $actual);

        $where[] = ["mobile", "=", "12334567890"];
        $actual  = $logic->getEntityAvg("grade", $where);
        $expect  = 2.5;
        self::assertEquals($expect, $actual);
    }

    public function testSum(): void
    {
        $logic = $this->logicIsolated;

        $where[] = ["id", ">", 0];
        $logic->deleteEntities($where);
        $this->_addEntities();

        $actual = $logic->getEntitySum("grade");
        $expect = 9;
        self::assertEquals($expect, $actual);

        $where[] = ["grade", "=", 2];
        $actual  = $logic->getEntitySum("grade", $where);
        $expect  = 6;
        self::assertEquals($expect, $actual);
    }

    public function testDelete(): void
    {
        $logic = $this->logicIsolated;

        $where[] = ["id", ">", 0];
        $logic->deleteEntities($where);

        $actual = $logic->getEntityCount();
        $expect = 0;
        self::assertEquals($expect, $actual);

        $this->_addEntities();
        $where[] = ["grade", "=", 2];
        $where[] = ["mobile", "=", "12334567890"];
        $actual  = $logic->deleteEntities($where);
        $expect  = 1;
        self::assertEquals($expect, $actual);
    }

    /**
     * 测试 WhereOr条件
     * @return void
     */
    //public function testWhereOr(): void
    //{
    //    $logic = $this->logicIsolated;
    //
    //    //1->清除所有数据
    //    $where[] = ["id", ">", 0];
    //    $logic->deleteEntities($where);
    //
    //    //2->添加测试数据
    //    $this->_addEntities();
    //
    //    //3->测试WhereOr条件
    //    $where["OR"] = [
    //        ["mobile", "=", "12334567891"],
    //        ["grade", "=", "3"],
    //    ];
    //    $actual      = $logic->getEntityCount($where);
    //    $expect      = 4;
    //    self::assertEquals($expect, $actual);
    //}


}