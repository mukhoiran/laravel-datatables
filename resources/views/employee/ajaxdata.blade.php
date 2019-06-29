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
      <button type="button" name="add" id="add_data" class="btn btn-success btn-sm"><i class="glyphicon glyphicon-plus"></i> Add Data</button>
    </div>
    <br>
    <table id="employee_table" class="table table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Action</th>
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
            <input type="hidden" name="employee_id" id="employee_id" value="">
            <input type="hidden" name="button_action" id="button_action" value="insert">
            <input type="submit" name="submit" id="action" value="Save" class="btn btn-info">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </form>
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
        { "data": "last_name"},
        { "data": "action", orderable:false, searchable:false }
      ]
    });

    $('#add_data').click(function(){
      $('#employeeModal').modal('show');
      $('#employee_form')[0].reset();
      $('#form_output').html('');
      $('#button_action').val('insert');
      $('#action').val('Save');
    })

    $('#employee_form').on('submit', function(event){
      event.preventDefault();
      var form_data = $(this).serialize();
      $.ajax({
        url: "{{ route('ajaxdata.postdata') }}",
        method: "POST",
        data: form_data,
        dataType: "JSON",
        success: function(data){
          if(data.error.length > 0){
            var error_html = '';
            for(var count = 0; count < data.error.length; count++){
              error_html += '<div class="alert alert-danger">'+data.error[count]+'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>';
            }
            $('#form_output').html(error_html);
          }else{
            $('#form_output').html(data.success);
            if($('#button_action').val() == "insert"){
              $('#employee_form')[0].reset();
            }
            $('#action').val('Save');
            $('.modal-title').text('Add Data');
            $('#button_action').val('insert');
            $('#employee_table').DataTable().ajax.reload();
          }
        }
      })
    })

    $(document).on('click', '.edit', function(){
      var id = $(this).attr("id");
      $.ajax({
        url: "{{ route('ajaxdata.fetchdata') }}",
        method:"GET",
        data:{id:id},
        dataType:"JSON",
        success:function(data){
          $('#form_output').text('');
          $('#first_name').val(data.first_name);
          $('#last_name').val(data.last_name);
          $('#employee_id').val(data.id);
          $('#employeeModal').modal('show');
          $('#action').val('Update');
          $('.modal-title').text('Edit Data');
          $('#button_action').val('update');
        }
      })
    })

    $(document).on('click', '.delete', function(){
      var id = $(this).attr("id");
      if(confirm("Are you sure want to Delete this data")){
        $.ajax({
          url:"{{ route('ajaxdata.removedata') }}",
          method: "GET",
          data:{id:id},
          success:function(data){
            alert(data);
            $('#employee_table').DataTable().ajax.reload();
          }
        })
      }else{
        return false;
      }
    })
  })
</script>
</body>
</html>
