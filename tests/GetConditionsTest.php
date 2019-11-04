<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Library\Lib;

class GetConditionsTest extends TestCase
{
    /**
     *@group ignore
     */
    public function test_取得設定檔()
    {
        $data = Lib::get_conditions();

        $this->assertIsArray($data);
        $this->assertArrayHasKey("cat", $data);
    }
}
