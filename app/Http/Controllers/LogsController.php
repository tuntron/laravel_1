<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Logs;
class LogsController extends Controller
{
    //
	public function index(){
		
		return Logs::findOrfail(1);
	}
}
