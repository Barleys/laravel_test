<?php

namespace App\Http\Controllers;

use App\Tag;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Tree;

class MenuController extends Controller
{
    public function test()
    {
        $root = Tag::create(['name' => 'Root']);


    }

}
