<!DOCTYPE html>
<html>
    <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LOgin </title>
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
				<a class="navbar-brand" style="color:#fff" href="http://localhost/crud-demo/">Home</a>
			</div>
			<div id="navbar" class="collapse navbar-collapse">
				<ul class="nav navbar-nav ">
					<li><a href="http://eleonsolar.com"></a></li>
				</ul>
				<div class="nav navbar-nav navbar-right">
					<li><a href="#" onclick="signin()" >Sign In</a></li>
				</div>
			</div><!--/.nav-collapse -->
		</div>
	</nav>
<br><br><br><br>
	<div class="container">
	    <div class="row">
	        <div class="col-md-4 col-md-offset-4">
	            <div class="login-panel panel panel-default">
	                <div class="panel-heading">
	                    <h3 class="panel-title"> Please Sign In</h3>
	                </div>

	                <div class="panel-body">
	                	<small id="login-empty-input" class="error">email or password cannot be empty <br>&nbsp;</small>
	                	<?php if($alert): ?>
	                		<small id="login-invalid-input" class="error">invalid email or password<br>&nbsp;</small>
	                	<?php endif; ?>

	                    <form role="form" method="post" onsubmit="return checkEmptyInput();" action="<?=base_url()?>index.php/authentication/login/">
	                        <fieldset>
	                            <div class="form-group">
	                                <input class="form-control" id="email" placeholder="E-mail" name="email" type="email" autofocus>
	                            </div>
	                            <div class="form-group">
	                                <input class="form-control" id="password" placeholder="Password" name="password" type="password" value="">
	                            </div>
	                            <div class="form-group">
	                                <small><a href="#" onclick="alert('Please contact the administrator to reset your password!')">Forgot Password?</a></small>
	                            </div>
	                            <input id="login-submit" type="submit" value="Login" class="btn btn-lg btn-success btn-block">
	                        </fieldset>
	                    </form>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>



    <!-- jQuery -->
    <script src="<?=base_url()?>assets/js/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="<?=base_url()?>assets/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="<?=base_url()?>assets/js/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="<?=base_url()?>assets/js/sb-admin-2.js"></script>

    <script>
    	window.onload = hideLoginErrors();
    	function hideLoginErrors(){
    		$("#login-empty-input").hide();
    	}
		function checkEmptyInput(){
			hideLoginErrors();
			$("#login-invalid-input").hide();
			if( $("#email").val() == '' || $("#password").val() == '' ){
				$("#login-empty-input").show();
				return false;
			}
		}
	</script>

</body>

</html>
