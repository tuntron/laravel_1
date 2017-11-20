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
        $foo = Foo::findOrfail(1);
        for ($i=0;$i<1000;$i++){
            $flight = new Foo;

            $flight->id = rand(10,1000000);

            $flight->name = 'chenchen'.rand(10,1000000);

            $flight->save();
            $this->dispatch(new SendReminderEmail());
        }
        return $foo;
    }

	
}
