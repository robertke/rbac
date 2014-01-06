<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace RbacTest\Traversal;

use PHPUnit_Framework_TestCase as TestCase;
use Rbac\Role\HierarchicalRole;
use Rbac\Role\Role;
use Rbac\Traversal\RecursiveRoleIterator;
use stdClass;

/**
 * @covers Rbac\Traversal\RecursiveRoleIterator
 * @group  Coverage
 */
class RecursiveRoleIteratorTest extends TestCase
{
    /**
     * @covers Rbac\Traversal\RecursiveRoleIterator::valid
     */
    public function testValidateRoleInterface()
    {
        $foo      = new Role('Foo');
        $roles    = [$foo, new stdClass];
        $iterator = new RecursiveRoleIterator($roles);

        $this->assertSame($iterator->current(), $foo);
        $this->assertTrue($iterator->valid());

        $iterator->next();

        $this->assertFalse($iterator->valid());
    }

    /**
     * @covers Rbac\Traversal\RecursiveRoleIterator::hasChildren
     */
    public function testHasChildrenReturnsFalseIfRoleIsNotHierarchical()
    {
        $foo      = new Role('Foo');
        $roles    = [$foo];
        $iterator = new RecursiveRoleIterator($roles);

        $this->assertFalse($iterator->hasChildren());
    }

    /**
     * @covers Rbac\Traversal\RecursiveRoleIterator::hasChildren
     */
    public function testHasChildrenReturnsFalseIfRoleChildrenIsEmpty()
    {
        $bar      = new HierarchicalRole('Bar');
        $roles    = [$bar];
        $iterator = new RecursiveRoleIterator($roles);

        $this->assertFalse($iterator->hasChildren());
    }

    /**
     * @covers Rbac\Traversal\RecursiveRoleIterator::hasChildren
     */
    public function testHasChildrenReturnsTrueIfRoleHasChildren()
    {
        $baz = new HierarchicalRole('Baz');
        $baz->addChild(new Role('Foo'));

        $roles    = [$baz];
        $iterator = new RecursiveRoleIterator($roles);

        $this->assertTrue($iterator->hasChildren());
    }

    /**
     * @covers Rbac\Traversal\RecursiveRoleIterator::getChildren
     */
    public function testGetChildrenReturnsAnRecursiveRoleIteratorOfRoleChildren()
    {
        $baz = new HierarchicalRole('Baz');
        $baz->addChild(new Role('Foo'));
        $baz->addChild(new Role('Bar'));

        $roles    = [$baz];
        $iterator = new RecursiveRoleIterator($roles);

        $this->assertEquals(
            $iterator->getChildren(),
            new RecursiveRoleIterator($baz->getChildren())
        );
    }
}