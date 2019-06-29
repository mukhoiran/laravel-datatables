<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;
use DataTables;

class AjaxdataController extends Controller
{
    function index(){
      return view('employee.ajaxdata');
    }

    function getdata(){
      $employees = Employee::select('first_name','last_name');
      return Datatables::of($employees)->make(true);
    }
}
