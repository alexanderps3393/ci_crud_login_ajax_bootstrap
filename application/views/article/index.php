<!DOCTYPE html>
<html>
    <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Artcle Apps</title>
    <link rel="icon" href="http://localhost/crud-demo/images/favicon.png">
    <link href="http://localhost/crud-demo/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="http://localhost/crud-demo/assets/datatables/css/dataTables.bootstrap.css" rel="stylesheet">
    </head>
<body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" style="color:#fff" href="http://localhost/crud-demo/article">Home</a>
					<?php if($role == 'admin') {
					 echo '<a class="navbar-brand" style="color:#fff" href="http://localhost/crud-demo/users">Users</a>';
				 	}
					?>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav ">
            <li><a href="http://eleonsolar.com"></a></li>
          </ul>
          <div class="nav navbar-nav navbar-right">
            <li><a href="<?=base_url()?>index.php/authentication/logout" >Logout</a></li>
          </div>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container" style="margin-top:60px">
        <h2>Welcome, <?= $name; ?> </h2>
        <h3 style="text-decoration:underline">List Article</h3>
        <button class="btn btn-success" onclick="addArticle()"><i class="glyphicon glyphicon-plus"></i>Add Article</button>
        <button class="btn btn-default" onclick="reloadTable()"><i class="glyphicon glyphicon-refresh"></i>Reload</button>
        <button id="deleteList" class="btn btn-danger" style="display: none;" onclick="deleteList()"><i class="glyphicon glyphicon-trash"></i>Delete list</button>
        <br />
        <br />
        <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th><input type="checkbox" id="check-all"></th>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Author</th>
                    <th style="width:150px;">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
<script src="http://localhost/crud-demo/assets/jquery/jquery.js"></script>
<script src="http://localhost/crud-demo/assets/bootstrap/js/bootstrap.min.js"></script>
<script src="http://localhost/crud-demo/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="http://localhost/crud-demo/assets/datatables/js/dataTables.bootstrap.js"></script>
<script type="text/javascript">

var save_method; //for save method string
var table;

$(document).ready(function() {
    //datatables
    table = $('#table').DataTable({

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "article/ajax_list",
            "type": "POST"
        },

        //Set column definition initialisation properties.
        "columnDefs": [
            {
                "targets": [ 0 ], //first column
                "orderable": false, //set not orderable
            },
            {
                "targets": [ -1 ], //last column
                "orderable": false, //set not orderable
            },

        ],


    });
    //set input/textarea/select event when change value, remove class error and remove text help block
    $("input").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("textarea").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("select").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });

    //check all
    $("#check-all").click(function () {
        $(".data-check").prop('checked', $(this).prop('checked'));
        showBottomDelete();
    });
});

function showBottomDelete()
{
  var total = 0;

  $('.data-check').each(function()
  {
     total+= $(this).prop('checked');
  });

  if (total > 0)
      $('#deleteList').show();
  else
      $('#deleteList').hide();
}

function addArticle()
{
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add Article'); // Set Title to Bootstrap modal title
}

function signin()
{
    save_method = 'signin';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form_login').modal('show'); // show bootstrap modal
    $('.modal-title').text('Sign In'); // Set Title to Bootstrap modal title
}

function editArticle(id)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url : "article/ajax_edit/"+id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

            $('[name="id_article"]').val(data.id_article);
            $('[name="title"]').val(data.title);
            $('[name="content"]').val(data.content);
            $('[name="author_id"]').val(data.author_id);
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit'); // Set title to Bootstrap modal title

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error getting data from ajax');
        }
    });
}

function reloadTable()
{
    table.ajax.reload(); //reload datatable ajax
    $('#deleteList').hide();
}

function save()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable
    var url;

    if(save_method == 'add') {
        url = "article/ajax_add";
    } else {
        url = "article/ajax_update";
    }
    // ajax adding data to database
		console.log($('#form').serialize())
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal_form').modal('hide');
                reloadTable();
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++)
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                }
            }
            $('#btnSave').text('Save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable


        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnSave').text('Save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable

        }
    });
}

function deleteArticle(id)
{
    if(confirm('Are you sure to remove the article?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "article/ajax_delete/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                //if success reload ajax table
                $('#modal_form').modal('hide');
                reloadTable();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }
        });

    }
}

function deleteList()
{
    var list_id = [];
    $(".data-check:checked").each(function() {
            list_id.push(this.value);
    });
    if(list_id.length > 0)
    {
        if(confirm('Are you sure delete this '+list_id.length+' data?'))
        {
            $.ajax({
                type: "POST",
                data: {id:list_id},
                url: "article/ajax_list_delete",
                dataType: "JSON",
                success: function(data)
                {
                    if(data.status)
                    {
                        reloadTable();
                    }
                    else
                    {
                        alert('Failed.');
                    }

                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error deleting data');
                }
            });
        }
    }
    else
    {
        alert('no data selected');
    }
}

</script>

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Form Article</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id"/>
                    <div class="form-body">
                      <div class="form-group">
                          <div class="col-md-9">
                              <input name="id_article" class="form-control" type="hidden">
                              <span class="help-block"></span>
                          </div>
                      </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Title</label>
                            <div class="col-md-9">
                                <input name="title" placeholder="Title" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Content</label>
                            <div class="col-md-9">
																<textarea class="form-control" name="content" placeholder="Content" rows="5" id="comment"></textarea>

                                <span class="help-block"></span>
                            </div>
                        </div>
												<input type="hidden" value="<?= $author_id; ?>" name="author_id"/>

                        <!-- <div class="form-group">
                            <label class="control-label col-md-3">Author</label>
                            <div class="col-md-9">
                                <select name="author_id" class="form-control">
                                    <option value="">--Select author--</option>
                                    <?php
                                    foreach($list as $value){
                                      echo '<option value="'.$value->id.'">'.$value->name.'</option>';
                                    }
                                    ?>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div> -->
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->

<!-- Login modal -->
<div class="modal fade" id="modal_form_login" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Form Signin</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form_signin" class="form-horizontal">
                    <div class="form-group">
                        <label class="control-label col-md-3">Username</label>
                        <div class="col-md-9">
                            <input name="username" placeholder="Username" class="form-control" type="text">
                            <span class="help-block"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">Password</label>
                        <div class="col-md-9">
                            <input name="password" placeholder="Password" class="form-control" type="text">
                            <span class="help-block"></span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSignin" onclick="signin()" class="btn btn-primary">Sign In</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
</body>
</html>
