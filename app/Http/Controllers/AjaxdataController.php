<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AjaxdataController extends Controller
{
    function index(){
      return view('employee.ajaxdata');
    }
}
