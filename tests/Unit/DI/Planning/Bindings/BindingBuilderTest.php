<?php
/**
 * This file is part of YAM.
 *
 * @author     Domenic Muskulus <domenic@muskulus.eu>
 * @copyright  2013 Domenic Muskulus
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

namespace YAM\UnitTest\DI\Planning\Bindings;


use YAM\DI\Activation\Context;
use YAM\DI\Activation\Providers\CallbackProvider;
use YAM\DI\Activation\Providers\ConstantProvider;
use YAM\DI\Activation\Providers\Provider;
use YAM\DI\Planning\Bindings\Binding;
use YAM\DI\Planning\Bindings\BindingBuilder;
use YAM\DI\Planning\Bindings\BindingTarget;
use YAM\DI\Scope;

class BindingBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testBindInterfaceToImplementation()
    {
        $binding = new Binding('stdClass');
        $bindingBuilder = new BindingBuilder($binding);
        $bindingBuilder->to('YAM\TestClass');

        $this->assertEquals('stdClass', $binding->getService());
        $this->assertEquals('YAM\TestClass', $binding->getImplementationType());
        $this->assertEquals(BindingTarget::TYPE(), $binding->getTarget());
    }

    public function testBindImplementationToSelf()
    {
        $binding = new Binding('stdClass');
        $bindingBuilder = new BindingBuilder($binding);
        $bindingBuilder->toSelf();

        $this->assertEquals('stdClass', $binding->getService());
        $this->assertEquals('stdClass', $binding->getImplementationType());
        $this->assertEquals(BindingTarget::SELF(), $binding->getTarget());
    }

    public function testBindImplementationToProvider()
    {
        $binding = new Binding('stdClass');
        $bindingBuilder = new BindingBuilder($binding);
        $bindingBuilder->toProvider($this->getMock(Provider::class));

        $this->assertEquals('stdClass', $binding->getService());
        $this->assertEquals('stdClass', $binding->getImplementationType());
        $this->assertEquals(BindingTarget::PROVIDER(), $binding->getTarget());
        $this->assertInstanceOf(get_class($this->getMock(Provider::class)), $binding->getProvider($this->getContextMock()));
    }

    public function testBindImplementationToMethod()
    {
        $binding = new Binding('stdClass');
        $bindingBuilder = new BindingBuilder($binding);
        $bindingBuilder->toMethod(function() {});

        $this->assertEquals('stdClass', $binding->getService());
        $this->assertEquals('stdClass', $binding->getImplementationType());
        $this->assertEquals(BindingTarget::METHOD(), $binding->getTarget());
        $this->assertInstanceOf(CallbackProvider::class, $binding->getProvider($this->getContextMock()));
    }

    public function testBindImplementationToConstant()
    {
        $binding = new Binding('stdClass');
        $bindingBuilder = new BindingBuilder($binding);
        $bindingBuilder->toConstant(10);

        $this->assertEquals('stdClass', $binding->getService());
        $this->assertEquals('stdClass', $binding->getImplementationType());
        $this->assertEquals(BindingTarget::CONSTANT(), $binding->getTarget());
        $this->assertEquals(Scope::SINGLETON(), $binding->getScope());
        $this->assertInstanceOf(ConstantProvider::class, $binding->getProvider($this->getContextMock()));
        $this->assertEquals(10, $binding->getProvider($this->getContextMock())->create($this->getContextMock()));
    }

    public function testNamed()
    {
        $binding = new Binding('stdClass');
        $bindingBuilder = new BindingBuilder($binding);
        $bindingBuilder->named('TEST');

        $this->assertEquals('TEST', $binding->getName());
    }

    public function testWithConstructorArgument()
    {
        $binding = new Binding('stdClass');
        $bindingBuilder = new BindingBuilder($binding);
        $bindingBuilder->withConstructorArgument('arg1', 1)->withConstructorArgument('arg2', 2);

        $this->assertCount(2, $binding->getConstructorArguments());
        $this->assertEquals(['arg1' => 1, 'arg2' => 2], $binding->getConstructorArguments());

        $bindingBuilder->withConstructorArgument('arg2', 3);

        $this->assertCount(2, $binding->getConstructorArguments());
        $this->assertEquals(['arg1' => 1, 'arg2' => 3], $binding->getConstructorArguments());
    }

    public function testInSingletonScope()
    {
        $binding = new Binding('stdClass');
        $bindingBuilder = new BindingBuilder($binding);
        $bindingBuilder->inSingletonScope();

        $this->assertEquals(Scope::SINGLETON(), $binding->getScope());
    }

    public function testScopeDefinition()
    {
        $binding = new Binding('stdClass');
        $bindingBuilder = new BindingBuilder($binding);
        $bindingBuilder->to('YAM\TestClass')->in(Scope::SINGLETON());

        $this->assertEquals(Scope::SINGLETON(), $binding->getScope());
        $this->assertEquals(BindingTarget::TYPE(), $binding->getTarget());
    }

    public function testNoScopeDefinition()
    {
        $binding = new Binding('stdClass');
        $bindingBuilder = new BindingBuilder($binding);
        $bindingBuilder->to('YAM\TestClass');

        $this->assertNull($binding->getScope());
        $this->assertEquals(BindingTarget::TYPE(), $binding->getTarget());
    }

    private function getContextMock()
    {
        return $this->getMock(Context::class, [], [], '', false);
    }
}
 