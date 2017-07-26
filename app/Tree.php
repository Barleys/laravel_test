<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Baum\Node as Nodes;

class Tree extends Nodes
{
    protected $table = "m_o_d_e_l_s";

    public function test()
    {
        $root = Tree::create(['name' => 'Root', 'parent_id' => 0]);

        return $root;
    }

    public function tq()
    {
        $node = Tree::where('name', '=', 'Root')->first();

        return $node->getDescendantsAndSelf();
    }
}
