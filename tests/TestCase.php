<?php
// +----------------------------------------------------------------------
// | TestCase.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace Tests;

use PHPUnit\Framework\TestCase as UnitTestCase;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\DI\FactoryDefault;
use Phalcon\Events\Manager as EventsManager;
use PDO;
use Tests\App\SqlCount;
use Phalcon\Mvc\Model\Metadata\Files as MetadataFiles;

class TestCase extends UnitTestCase
{
    public $di;

    public function setUp()
    {
        $di = new FactoryDefault();

        $di->setShared('db', function () {
            $db = new DbAdapter(
                [
                    'host' => '127.0.0.1',
                    'port' => '3306',
                    'username' => 'root',
                    'password' => '910123',
                    'dbname' => 'eagerload',
                    'charset' => 'utf8',
                    'options' => [
                        PDO::ATTR_CASE => PDO::CASE_NATURAL,
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_ORACLE_NULLS => PDO::NULL_NATURAL,
                        PDO::ATTR_STRINGIFY_FETCHES => false,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    ],
                ]
            );

            $eventsManager = new EventsManager();
            // 侦听全部数据库事件
            $eventsManager->attach(
                "db:afterQuery",
                function () {
                    SqlCount::getInstance()->add();
                }
            );
            $db->setEventsManager($eventsManager);

            return $db;
        });

        $di->setShared('modelsMetadata', function () {

            $modelsMetadata = new MetadataFiles(
                [
                    'metaDataDir' => __DIR__ . '/../meta/',
                ]
            );

            return $modelsMetadata;
        });
    }

    protected function tearDown()
    {
        SqlCount::getInstance()->flush();
    }
}
