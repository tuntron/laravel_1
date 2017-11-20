<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Foo;

class FooController extends Controller
{
    //
    public function index()
    {
	
        return Foo::findOrfail(1);
    }

	
}
