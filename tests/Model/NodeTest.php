<?php

namespace Cototal\CmsParser\Tests\Model;

use \PHPUnit\Framework\TestCase;
use Cototal\CmsParser\Model\Node;

class NodeTest extends TestCase
{
    public function testHasSetName()
    {
        $node = new Node();
        $node->setAttributes([
            "name" => "test",
            "id" => 1
        ]);
        $this->assertEquals("test", $node->getName());
    }

    public function testIsNamelessIfNoName()
    {
        $node = new Node();
        $this->assertEquals("unnamed", $node->getName());
    }
}
