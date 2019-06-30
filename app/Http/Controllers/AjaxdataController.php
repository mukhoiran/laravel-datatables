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
      $employees = Employee::select('id','first_name','last_name');
      return Datatables::of($employees)
      ->addColumn('action', function($employee){
        return '
          <a href="#" class="btn btn-xs btn-primary edit" id="'.$employee->id.'"><i class="glyphicon glyphicon-edit"> Edit</i></a>
          <a href="#" class="btn btn-xs btn-danger delete" id="'.$employee->id.'"><i class="glyphicon glyphicon-remove"> Delete</i></a>
        ';
      })
      ->addColumn('checkbox', '<input type="checkbox" name="employee_checkbox[]" class="employee_checkbox" value="{{$id}}" />')
      ->rawColumns(['checkbox','action'])
      ->make(true);
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

        if($request->get('button_action') == "update"){
          $employee = Employee::find($request->get('employee_id'));
          $employee->first_name = $request->get('first_name');
          $employee->last_name = $request->get('last_name');
          $employee->save();
          $success_output = '<div class="alert alert-success">Data Updated <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
        }
      }
      $output = array(
        'error' => $error_array,
        'success' => $success_output
      );
      echo json_encode($output);
    }

    function fetchdata(Request $request){
      $id = $request->input('id');
      $employee = Employee::find($id);
      $output = array(
        'id' => $employee->id,
        'first_name' => $employee->first_name,
        'last_name' => $employee->last_name
      );
      echo json_encode($output);
    }

    function removedata(Request $request){
      $employee = Employee::find($request->input('id'));
      if($employee->delete()){
        echo 'Data Deleted';
      }
    }

    function massremove(Request $request){
      $employee_id_array = $request->input('id');
      $employee = Employee::whereIn('id', $employee_id_array);
      if($employee->delete()){
        echo 'Data Deleted';
      }
    }
}
