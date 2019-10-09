<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

class SimpleTest extends TestCase{

    public function testAddition(){
        return $this->assertEquals(5, 2 + 3 , ' 2 + 3 = 5');
    }
}