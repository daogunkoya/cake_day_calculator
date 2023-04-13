<?php
namespace TwoTogether;

use PHPUnit\Framework\TestCase;
use TwoTogether\CakeItem;

class CakeItemTest extends TestCase
{
    public function testSetSmall()
    {
        $cakeItem = new CakeItem();
        $cakeItem->setSmall();

        $this->assertTrue($cakeItem->toArray()['small']);
        $this->assertFalse($cakeItem->toArray()['large']);
    }

    public function testSetLarge()
    {
        $cakeItem = new CakeItem();
        $cakeItem->setLarge();

        $this->assertTrue($cakeItem->toArray()['large']);
        $this->assertFalse($cakeItem->toArray()['small']);
    }

    public function testAddName()
    {
        $cakeItem = new CakeItem();
        $cakeItem->addName('John');

        $this->assertEquals(['John'], $cakeItem->toArray()['names']);

        $cakeItem->addName('Mary');
        $cakeItem->addName('John');

        $listName1 = $cakeItem->toArray();
        $this->assertEquals(['John', 'Mary'], $listName1['names'] );

        
    }
}
