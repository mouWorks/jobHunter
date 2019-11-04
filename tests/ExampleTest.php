<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    /**
     * @group ignore
     */
    public function testBasicExample()
    {
        $this->visit('/')
             ->see('首頁');
    }
}
