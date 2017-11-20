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
        for ($i=0;$<100;$i++){
            $this->dispatch(new SendReminderEmail());
        }
        return 1;
    }

	
}
