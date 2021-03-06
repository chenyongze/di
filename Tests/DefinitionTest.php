<?php
namespace Slince\Di\Tests;

use Slince\Di\Definition;
use Slince\Di\Tests\TestClass\Director;

class DefinitionTest extends \PHPUnit_Framework_TestCase
{

    protected function createDefinition($class, $arguments = [], $methodCalls = [])
    {
        return new Definition($class, $arguments, $methodCalls);
    }
    
    public function testSetAndGetArgument()
    {
        $definition = $this->createDefinition(Director::class);
        $definition->setArgument(0, 'LiAn');
        $this->assertEquals('LiAn', $definition->getArgument(0));
    }

    public function testSetAndGetArguments()
    {
        $definition = $this->createDefinition(Director::class);
        $arguments = ['Jumi', 12];
        $definition->setArguments($arguments);
        $this->assertEquals($arguments, $definition->getArguments());
    }

    public function testSetAndGetMethodCall()
    {
        $definition = $this->createDefinition(Director::class);
        $definition->setMethodCall('setName', ['LiAn']);
        $this->assertEquals(['LiAn'], $definition->getMethodCall('setName'));
    }

    public function testSetAndGetMethodCalls()
    {
        $definition = $this->createDefinition(Director::class);
        $methodCalls = [
            'setName' => ['LiAn'],
            'setAge' => [18],
        ];
        $definition->setMethodCalls($methodCalls);
        $this->assertEquals($methodCalls, $definition->getMethodCalls());
    }
}
