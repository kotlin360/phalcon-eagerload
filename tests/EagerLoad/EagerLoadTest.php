<?php
// +----------------------------------------------------------------------
// | BaseTest.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace Tests\EagerLoad;

use Phalcon\Mvc\Model\Resultset;
use Tests\App\Models\User;
use Tests\App\SqlCount;
use Tests\TestCase;
use Xin\Phalcon\Mvc\Model\EagerLoad;

class EagerLoadTest extends TestCase
{
    public function testRelation()
    {
        $user = User::findFirst(1);
        $res = $user->book->toArray();
        $this->assertEquals(2, count($res));
        $this->assertEquals(2, SqlCount::getInstance()->count);
    }

    public function testEagerLoad()
    {
        $users = User::find();
        $users = EagerLoad::load($users, 'book');

        foreach ($users as $user) {
            $res = $user->book;
        }

        $this->assertEquals(2, SqlCount::getInstance()->count);
    }
}
