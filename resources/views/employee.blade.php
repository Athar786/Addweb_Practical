<html>
 <head>
 <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Practical Employee</title>
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
     <h3 align="center">Ajax Employee</h3>
     <br />
     <div align="right">
      <button type="button" name="create_record" id="create_record" class="btn btn-success btn-sm">Create Employee</button>
     </div>
     <br />
   <div class="table-responsive">
   <a href="home" name = "back" class="btn btn-success">Back Home</a>
    <table class="table table-bordered table-striped" id="user_table">
           <thead>
            <tr>
                <th width="10%">Image</th>
                <th width="30%">Name</th>
                <th width="35%">Address</th>
                <th width="35%">Contact NO</th>
                <th width="30%">Action</th>
            </tr>
           </thead>
       </table>
   </div>
   <br />
   <br />
  </div>
 </body>
</html>

<div id="formModal" class="modal fade" role="dialog">
 <div class="modal-dialog">
  <div class="modal-content">
   <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add New Employee</h4>
        </div>
        <div class="modal-body">
         <span id="form_result"></span>
         <form method="post" id="sample_form" class="form-horizontal" enctype="multipart/form-data">
          @csrf
          <div class="form-group">
            <label class="control-label col-md-4" > Name : </label>
            <div class="col-md-8">
             <input type="text" name="name" id="name" class="form-control" />
            </div>
           </div>
           <div class="form-group">
            <label class="control-label col-md-4">Address: </label>
            <div class="col-md-8">
             <input type="text" name="address" id="address" class="form-control" />
            </div>
           </div>

           <div class="form-group">
            <label class="control-label col-md-4">Contact: </label>
            <div class="col-md-8">
             <input type="text" name="contact" id="contact" class="form-control" />
            </div>                                  
           </div>


           <div class="form-group">
            <label class="control-label col-md-4">Image : </label>
            <div class="col-md-8">
             <input type="file" name="image" id="image" />
             <span id="store_image"></span>
            </div>
           </div>
           <br />
           <div class="form-group" align="center">
            <input type="hidden" name="action" id="action" />
            <input type="hidden" name="hidden_id" id="hidden_id" />
            <input type="submit" name="form_data" id="form_data" class="btn btn-warning" value="Add" />
           </div>
         </form>
        </div>
     </div>
    </div>
</div>

<script>
$(document).ready(function(){
    $('#user_table').DataTable({
        processing: true,
        serverSide: true,
        ajax:{
            url:"{{ route('employee.index')}}",
        },
        columns:[
            {
                data: 'image',
                name: 'image',
                render: function(data, type, full, meta){
                return "<img src={{ URL::to('/') }}/images/" + data + " width='70' class='img-thumbnail' />";
            },  
            orderable: false
            },
            {
                data:'name',
                name:'name'
            },
            {
                data:'address',
                name:'address'
            },
            {
                data:'contact',
                name:'contact',
            },
            {
                data:'action',
                name:'action',
                orderable: false
            }
        ]
    });

    $('#create_record').click(function(){
        $('#modal-title').text("Add New Record");
        $('#form_data').val("Add");
        $('#action').val("Add");
        $('#formModal').modal('show');
    });
    $('#sample_form').on('submit',function(event){
        event.preventDefault();
        if($('#action').val()=='Add')
        {
            $.ajax({
                url:"{{ route('employee.store')}}",
                method:"POST",
                data:new FormData(this),
                contentType: false,
                cache:false,
                processData: false,
                dataType:"json",
                success:function(data)
                {
                    var html = '';
                    if(data.errors)
                    {
                        html = '<div class="alert alert-danger">';
                        for(var count=0;count<data.errors.lengh;count++)
                        {
                            html += '<p>'+data.errors['count']+'</p>';
                        }
                        html +='<div>';
                    }
                    if(data.success)
                    {
                        html = '<div class="alert alert-success">' + data.success + '</div>';
                        $('#sample_form')[0].reset();
                        $('#user_table').DataTable().ajax.reload();
                    }
                    $('#form_result').html(html);
                }
            })
        }
        if($('#action').val() == "Edit")
        {
            $.ajax({
                url:"{{ route('employee.update')}}",
                method:"POST",
                data:new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType:"json",
                success:function(data)
                {
                    var html = '';
                    if(data.errors)
                    {
                        html = '<div class="alert alert-danger">';
                        for(var count = 0; count < data.errors.length; count++)
                        {
                        html += '<p>' + data.errors[count] + '</p>';
                        }
                        html += '</div>';
                    }
                    if(data.success)
                    {
                        html = '<div class="alert alert-success">' + data.success + '</div>';
                        $('#sample_form')[0].reset();
                        $('#store_image').html('');
                        $('#user_table').DataTable().ajax.reload();
                    }
                        $('#form_result').html(html);
                    
                }
            });
        }

    });

    $(document).on('click','.edit',function(){
        var id = $(this).attr('id');
        $('#form_result').html('');
        $.ajax({
            url:"/employee/"+id+"/edit",
            dataType:"json",
            success:function(html){
                $('#name').val(html.data.name);
                $('#address').val(html.data.address);
                $('#contact').val(html.data.contact);
                $('#store_image').html("<img src={{ URL::to('/') }}/images/" + html.data.image + " width='70' class='img-thumbnail' />");
                $('#store_image').append("<input type='hidden' name='hidden_image' value='"+html.data.image+"' />");
                $('#hidden_id').val(html.data.id);
                $('.modal-title').text("Edit New Record");
                $('#form_data').val("Edit");
                $('#action').val("Edit");
                $('#formModal').modal('show');
            }
        })
    });
});
</script>
