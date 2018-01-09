<?php
// +----------------------------------------------------------------------
// | BaseTest.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 limingxinleo All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <715557344@qq.com> <https://github.com/limingxinleo>
// +----------------------------------------------------------------------
namespace Tests\EagerLoad;

use Tests\App\Models\User;
use Tests\App\SqlCount;
use Tests\TestCase;

class EagerLoadTest extends TestCase
{
    public function testFindFirst()
    {
        $user = User::findFirst(1);
        $this->assertEquals('limx', $user->name);
        $this->assertEquals(1, SqlCount::getInstance()->count);
    }
}
