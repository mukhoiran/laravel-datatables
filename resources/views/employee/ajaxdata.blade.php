<!DOCTYPE html>
<html>
<head>
    <title>Datatables Server Side Processing in Laravel</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
    <br />
    <h3 align="center">Datatables Server Side Processing in Laravel</h3>
    <br />
    <div align="right">
      <button type="button" name="add" id="add_data" class="btn btn-success btn-sm"> Add Data</button>
    </div>
    <br>
    <table id="employee_table" class="table table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
            </tr>
        </thead>
    </table>
</div>

<div id="employeeModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="employee_form" method="post">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Data</h4>
        </div>
      </form>
      <div class="modal-body">
        {{ csrf_field() }}
        <span id="form_output"></span>
        <div class="form-group">
          <label for="first_name">Enter First Name</label>
          <input type="text" name="first_name" id="first_name" class="form-control" value="">
        </div>
        <div class="form-group">
          <label for="last_name">Enter Last Name</label>
          <input type="text" name="last_name" id="last_name" class="form-control" value="">
        </div>
        <div class="modal-footer">
          <input type="hidden" name="button_action" id="button_action" value="insert">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <input type="submit" name="submit" id="action" value="Save" class="btn btn-info">
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function(){

    $('#employee_table').DataTable({
      "Processing": true,
      "serverSide": true,
      "ajax": "{{ route('ajaxdata.getdata')}}",
      "columns": [
        { "data": "first_name"},
        { "data": "last_name"}
      ]
    });

    $('#add_data').click(function(){
      $('#employeeModal').modal('show');
      $('#employee_form')[0].reset();
      $('#form_output').html('');
      $('#button_action').val('insert');
      $('#action').val('Save');
    })

    
  })
</script>
</body>
</html>
