<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Baum\Node as Nodes;

class Tree extends Nodes
{
    protected $table = "m_o_d_e_l_s";

    protected $parentColumn = 'parent_id';

    protected $leftColumn = 'lft';

    protected $rightColumn = 'rgt';

    protected $depthColumn = 'depth';

    public function test()
    {
        $root = Tree::create(['name' => 'Root']);

        return $root;
    }

    /**
     * 插入节点
     */
    public function tq()
    {
        $root = Tree::where(['name' => 'Root'])->first();

        $child1 = Tree::create(['name' => 'child']);

        $ret = $child1->makeChildOf($root);

        return $ret;
    }

    public function level()
    {
        $node = Tree::where(['name' => 'Root'])->first();

        $level = $node->getLevel();

        return [
            'level' => $level,
        ];
    }
}



























