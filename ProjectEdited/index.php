<?php

/**************************
*
* Database Connections
*
***************************/
$link = new mysqli("localhost","root","","user_db");


if ($link->connect_errno) {
    printf("Connect failed: %s\n", $link->connect_error);
    exit();
}
/**************************
*
* Database interactions
*
***************************/
$loggedin = false;
if(isset($_COOKIE["AppName"]))
{
	$name = $_COOKIE["AppName"];
	$cryptedCookie = $_COOKIE[$name];
	$cryptedName = crypt($name,"ilovetacos");
	if($cryptedCookie == $cryptedName)
		$loggedin = true;
}
else
	$action = "none";

if(isset($_REQUEST["action"]))
	$action = $_REQUEST["action"];
else
	$action = "none";

$message = "";

if($action == "add_user")
{
	$name = $_POST["name"];
	$password = $_POST["password"];
	$passwordc = $_POST["passwordc"];
	
	$name = htmlentities($link->real_escape_string($name));
	$password = htmlentities($link->real_escape_string($password));
	$passwordc = htmlentities($link->real_escape_string($passwordc));
	if($password == $passwordc){
		$password = crypt ($password,"ilovetacos");
		$result = $link->query("INSERT INTO users (name, password) VALUES ('$name', '$password')");
		if(!$result)
			die ('Can\'t query users because: ' . $link->error);
		else
			$message = "User Added";
		$message = "User $name Logged in!";
		$cookieValue = crypt($name,"ilovetacos");
		setcookie("AppName", $name, time()+3600);  /* expire in 1 hour */
		setcookie($name, $cookieValue, time()+3600);  /* expire in 1 hour */
		$loggedin = true;
		header('Location: coverpage/index.php');
	}
	else{
		$message = "Passwords do not match.";
	}
}
elseif ($action == "delete_user") {
	$id = $_POST["id"];
	$name = $_POST["name"];
	$id = htmlentities($link->real_escape_string($id));
	$result = $link->query("DELETE FROM users WHERE id='" . $id . "'");
	if(!$result)
		die ('Can\'t query users because: ' . $link->error);
	else
		$message = "User $name Deleted";
}
elseif ($action == "edit_user") {
	$id = $_POST["id"];
	$id = htmlentities($link->real_escape_string($id));
	$name = $_POST["name"];
	$name = htmlentities($link->real_escape_string($name));
	$result = $link->query("UPDATE users SET name='$name' WHERE id='" . $id . "'");
	if(!$result)
		die ('Can\'t query users because: ' . $link->error);
	else
		$message = "User $name Updated";
}
elseif ($action == "login") {
	$name = $_POST["name"];
	$password = $_POST["password"];
	
	$name = htmlentities($link->real_escape_string($name));
	$password = htmlentities($link->real_escape_string($password));
	
	$password = crypt ($password,"ilovetacos");
	
	$result = $link->query("SELECT * FROM users WHERE name='$name'");
	if(!$result)
		die ('Can\'t query users because: ' . $link->error);

	$num_rows = mysqli_num_rows($result);
	if ($num_rows > 0) 
	{
	  $row = $result->fetch_assoc();
	  if($row["password"] == $password)
	  {
		$message = "User $name Logged in!";
		$cookieValue = crypt($name,"ilovetacos");
		setcookie("AppName", $name, time()+3600);  /* expire in 1 hour */
		setcookie($name, $cookieValue, time()+3600);  /* expire in 1 hour */
		$loggedin = true;
		header('Location: coverpage/index.php');
	  }
	  else
		$message = "Password for user $name incorrect!";
	}
	else {
	  // do something else
	  $message = "No user $name found!";
	}
}
?>


<html lang="en" class="no-js">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<!-- Mobile Specific Metas  ================================================== -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<!-- CORE CONCRETE  ================================================== -->
<script type="text/javascript">var BOOTSTRAP_VERSION ="bootstrap";
	var BOOTSTRAP_JS_HEAD =1;
	var BOOTSTRAP_CDN_ENABLE =0; var BOOTSTRAP_NAVBAR_TYPE =0; var BOOTSTRAP_LOGO_OPTION =1; var BOOTSTRAP_NAVBAR =1; var BootstrapInputFix =true;var BootstrapNavbarLineHeightFix =true;var BOOTSTRAP_EDT= 0; </script>

<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<title>ACU Times</title>
<meta name="description" content="" />
<meta name="generator" content="concrete5 - 5.6.3.4" />
<script type="text/javascript">
var CCM_DISPATCHER_FILENAME = '/index.php';
var CCM_CID = 1775;
var CCM_EDIT_MODE = false;
var CCM_ARRANGE_MODE = false;
var CCM_IMAGE_PATH = "/concrete/images";
var CCM_TOOLS_PATH = "/index.php/tools/required";
var CCM_BASE_URL = "http://bootstraptheme.com";
var CCM_REL = "";

</script>
<script>
			function validate()
			{
				var name = document.getElementById("add_name").value;
				if(name == "")
					alert("Please enter a name");
				else
				{
					var found = false;
					for(var i=0; i<name.length; i++)
					{
						if(name[i] == "@")
							found = true;
					}
					if(!found)
						alert("Please enter an email!");
					else
						document.forms["add_user"].submit();
				}
				
				return;
			}
			function confirm_delete(i)
			{
				var r = confirm("Are you sure you want to delete this user?");
				if(r)
				{
					document.forms["delete_user"+i].submit();
				}
				
				return;
			}
			function check_pass()
			{
				var pass1 = document.getElementById("pass1").value;
				var pass2 = document.getElementById("pass2").value;
				if(pass1==pass2)
				{
					document.getElementById("pass_same").innerHTML = "Match";
					document.getElementById("pass_same").style.background = "Green";
					document.getElementById("pass_same").style.color = "White";
				}
				else
				{
					document.getElementById("pass_same").innerHTML = "No Match";
					document.getElementById("pass_same").style.background = "Red";
					document.getElementById("pass_same").style.color = "White";
				}
			}
		</script>
		
<!-- ========================= These are all Bootstrap custom CSS and JS ======================= -->
	<link rel="shortcut icon" href="/files/2513/7825/5705/favicon.ico" type="image/x-icon" />
	<link rel="icon" href="/files/2513/7825/5705/favicon.ico" type="image/x-icon" />
	<link rel="stylesheet" type="text/css" href="http://cdn.bootstraptheme.com/concrete/css/ccm.base.css" />
	<script type="text/javascript" src="http://cdn.bootstraptheme.com/concrete/js/jquery.js"></script>
	<script type="text/javascript" src="http://cdn.bootstraptheme.com/concrete/js/ccm.base.js"></script>
	<link rel="stylesheet" type="text/css" href="http://cdn.bootstraptheme.com/packages/bootstrap/css/bootstrap/bootstrap.css" />
	<link rel="stylesheet" type="text/css" href="http://cdn.bootstraptheme.com/packages/bootstrap/css/bootstrap/docs.css" /> 
	<link rel="stylesheet" type="text/css" href="http://cdn.bootstraptheme.com/packages/bootstrap/css/bootstrap/bootstrap-overwrites.css" />
	<link rel="stylesheet" type="text/css" href="http://cdn.bootstraptheme.com/packages/bootstrap/css/members.css" />
	<script type="text/javascript" src="http://cdn.bootstraptheme.com/packages/bootstrap/js/common/prettify.js"></script>
	<script type="text/javascript" src="http://cdn.bootstraptheme.com/packages/bootstrap/js/common/jquery.easing.1.3.js"></script>
	<script type="text/javascript" src="http://cdn.bootstraptheme.com/packages/bootstrap/js/common/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="http://cdn.bootstraptheme.com/packages/pro_image/blocks/pro_image/templates/background_img/view.css" />
	<link rel="stylesheet" type="text/css" href="http://cdn.bootstraptheme.com/packages/pro_image/blocks/pro_image/templates/background_img/css/view.css" />
	<script type="text/javascript" src="http://cdn.bootstraptheme.com/packages/pro_image/blocks/pro_image/templates/background_img/js/jquery.backstretch.min.js">

</script>

<!-- ========================= This is my custom CSS and JS ======================= -->
    <link rel="stylesheet" href="custom.css" type="text/css" />

<style>
	html, body {			 
		height: 100%;
	}
</style>
</head>


<!-- =================== This is the start of the body of the site ========================== -->
<body class="bs-cover">
	<div class="site-wrapper">
		<div class="site-wrapper-inner">
			<div class="cover-container">
				<div class="container">
				<div class="jumbotron col-lg-12">
						<div class="inner cover">
							<h1 style="text-align: center;">ACU Times</h1>
							<p class="lead">Welcome to ACU Times where you can get your latest news and updates with everything ACU. 
							To get started, go ahead and sign up below. It's easy and free. Sign up with your email and create a password. 
							We're waiting for you inside!</p>
							<!-- ===== This is how I set the background image ======= -->
							<script>
								$.backstretch("stadium.jpg");
							</script>
						</div>
						<!-- ======= This is the php side of setting the message ====== -->
						<?php
							if($loggedin)
								print "Welcome, ". $_COOKIE["AppName"];
							else
								print "Not logged in.";
								
							if($message != "")
								print $message . "<br/><br/>";
						?>
						<!-- Nav tabs -->
						<div class="nav-center">
							<ul class="nav nav-tabs" role="tablist">
								<li role="presentation" class="active"><a href="#signup" aria-controls="signup" role="tab" data-toggle="tab">Sign Up</a></li>
								<li role="presentation"><a href="#login" aria-controls="login" role="tab" data-toggle="tab">Login</a></li>
							</ul>
							<!-- Tab panes -->
							<div class="tab-content">
								<div role="tabpanel" class="tab-pane active" id="signup">
									<form class="form-horizontal col-sm-offset-4 col-sm-4" method="post" action="index.php" name="add_user">
										<div class="form-group">
											<input type="email" class="form-control" name="name" placeholder="Email">
										</div>
										<div class="form-group">
											<input type="text" class="form-control" name="password" placeholder="Password">
										</div>
										<div class="form-group">
											<input type="text" class="form-control" name="passwordc" placeholder="Confirm Password">
										</div>
										<div>
											<input type="hidden" name="action" value="add_user" />
											<button type="submit" class="btn btn-default">Sign Up</button>
										</div>
									</form>
								</div>
								<div role="tabpanel" class="tab-pane" id="login">
									<form class="form-horizontal col-sm-offset-4 col-sm-4" method="post" action="index.php" name="login">
										<div class="form-group">
											<input type="email" class="form-control" name="name" placeholder="Email">
										</div>
										<div class="form-group">
											<input type="text" class="form-control" name="password" placeholder="Password">
										</div>
										<div>
										  <input type="hidden" name="action" value="login" />
										  <button type="submit" class="btn btn-default">Login</button>
										</div>
									</form>
								</div>
							</div>
						</div>  
					</div>
				</div>
				<div class="inner">
					<p>ACU Times created and modified by Alex Gabriele and Chance Woodie</p>
				</div>
			</div>
		</div>
	<script type="text/javascript" src="http://cdn.bootstraptheme.com/packages/bootstrap/js/common/app.js"></script>
</body>
</html>