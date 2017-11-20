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
        //$foo = Foo::findOrfail(1);
        $this->dispatch(new SendReminderEmail());
        return 1;
    }

	
}
