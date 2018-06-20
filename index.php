<?php
include_once "includes/dbconnect.php";
$errormessage = "";
$errormessage2 = "";
if (isset($_POST['loginadmin'])){
	$email = htmlspecialchars($_POST['admin']);
	$password = htmlspecialchars($_POST['apass']);
	if (empty ($email && $password) == false){
		$sql = "SELECT eMail, passWord FROM admin WHERE eMail='".$email."' AND passWord='".$password."' LIMIT 1";
		$result = $conn->query($sql);
		if ($result->num_rows > 0){
			session_start();
			$_SESSION['email'] = $email;
			$_SESSION['password'] = $password;
			header ('location: admin_dashboard.php');
		} else {
			$errormessage = "Invalid email or password";
		}
		
	} else {
		$errormessage = "Please enter email and password";
	}
}
// login for client
if (isset($_POST['loginclient'])){
	$email = htmlspecialchars($_POST['client']);
	$password = htmlspecialchars($_POST['apass']);
	if (empty ($email && $password) == false){
		$sql = "SELECT eMail, passWord FROM client WHERE eMail='".$email."' AND passWord='".$password."' LIMIT 1";
		$result = $conn->query($sql);
		if ($result->num_rows > 0){
			session_start();
			$_SESSION['email'] = $email;
			$_SESSION['password'] = $password;
			header ('location: client_dashboard.php');
		} else {
			$errormessage2 = "Invalid email or password";
		}
		
	}else {
		$errormessage2 = "Please enter email and password";
	}
}
?>
<?php include_once "includes/header.php"; ?>
	  <div id="container" style="min-height: 340px;">
	    <header id="header">
		  <h3>Temi Multipurpose Store</h3>
		</header>
		<marquee direction="left" behaviour="scroll" scrollamount="2" class="marquee">TemiStore Login Section for Admin and Client</marquee>
		<div class="login">
		  <div class="admin">
		    <label>Admin</label><br>
			<span class="errormessage"><?php echo $errormessage; ?></span>
			<form action="" method="post">
			   <div class="input-group">
			     <span class="input-group-addon">Email Address</span>
				 <input id="aemail" type="text" class="form-control" name="admin" placeholder="">
			   </div><br>
			   <div class="input-group">
			     <span class="input-group-addon">Password</span>
				 <input id="apass" type="password" class="form-control" name="apass" placeholder="">
			   </div><br>
			   <button type="submit" class="btn btn-primary" name="loginadmin">Login As Admin</button>
			</form>
		  </div>
		  <div class="client">
		    <label>Client</label><br>
			<span class="errormessage"><?php echo $errormessage2; ?></span>
			<form action="" method="post">
			   <div class="input-group">
			     <span class="input-group-addon">Email Address</span>
				 <input id="aclient" type="text" class="form-control" name="client" placeholder="">
			   </div><br>
			   <div class="input-group">
			     <span class="input-group-addon">Password</span>
				 <input id="apass" type="password" class="form-control" name="apass" placeholder="">
			   </div><br>
			   <button type="submit" class="btn btn-primary" name="loginclient">Login As Client</button>
			</form>
		  </div>
		</div>
		<?php include_once "includes/footer.php"; ?>
	  </div>
</body>
</html>
</body>