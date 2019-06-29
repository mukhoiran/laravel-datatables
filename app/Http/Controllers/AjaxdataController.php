<?php

namespace App\Http\Controllers;

use Validator;
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

    function postdata(Request $request){
      $validation = Validator::make($request->all(),[
        'first_name' => 'required',
        'last_name' => 'required'
      ]);

      $error_array = array();
      $success_output = '';
      if($validation->fails()){
        foreach ($validation->messages()->getMessages() as $field_name => $messages) {
          $error_array[] = $messages;
        }
      }else{
        if($request->get('button_action') == "insert"){
          $employee = new Employee([
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name')
          ]);
          $employee->save();
          $success_output = '<div class="alert alert-success">Data Inserted <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
        }
      }
      $output = array(
        'error' => $error_array,
        'success' => $success_output
      );
      echo json_encode($output);
    }
}
