<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Rbac\Traversal;

use ArrayIterator;
use Rbac\Role\HierarchicalRoleInterface;
use Rbac\Role\RoleInterface;
use RecursiveIterator;

class RecursiveRoleIterator extends ArrayIterator implements RecursiveIterator
{
    /**
     * @return bool
     */
    public function valid()
    {
        return $this->current() instanceof RoleInterface;
    }

    /**
     * @return bool
     */
    public function hasChildren()
    {
        $current = $this->current();

        if (!$current instanceof HierarchicalRoleInterface) {
            return false;
        }

        if (empty($current->getChildren())) {
            return false;
        }

        return true;
    }

    /**
     * @return RecursiveRoleIterator
     */
    public function getChildren()
    {
        return new RecursiveRoleIterator($this->current()->getChildren());
    }
}