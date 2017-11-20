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
        for ($i=0;$i<1000;$i++){
            $this->dispatch(new SendReminderEmail());
        }
        return 1;
    }

	
}
