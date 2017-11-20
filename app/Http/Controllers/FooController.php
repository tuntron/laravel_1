<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Foo;
use App\Jobs\SendReminderEmail;

class FooController extends Controller
{
    //
    public function index()
    {
        SendReminderEmail::dispatch();
        return Foo::findOrfail(1);
    }

	
}
