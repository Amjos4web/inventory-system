<?php
include_once "includes/dbconnect.php";
session_start();
ob_start();
if (isset($_SESSION['email'])){
	$email = $_SESSION['email'];
} else {
	header ('location: index.php');
}
$errormessage = "";
$successmessage = "";
// select from database
$sql = "SELECT * FROM admin WHERE eMail='".$email."' LIMIT 1";
$result = $conn->query($sql);
if ($result->num_rows > 0){
	while ($row = $result->fetch_assoc()){
		$name = $row['name'];
		$last_log_date = $row['last_log_date'];
	}
} else {
	$errormessage = "No information yet in the database";
}
// parse products into the database
if(isset($_POST['add'])){
	$fullname = htmlspecialchars($_POST['fullname']);
	$emailadd = htmlspecialchars($_POST['emailadd']);
	$password = htmlspecialchars($_POST['password']);
	
	if (empty($fullname && $emailadd && $password) == false){

		$insert = "INSERT INTO client (`name`, `eMail`, `passWord`) VALUES ('$fullname', '$emailadd', '$password')";
		$result = $conn->query($insert);
		if ($result === TRUE){
			$successmessage = "<strong>Success!</strong> $fullname has been added successfully";
			header ('refresh:4; url=addClient.php');
		} else {
			$errormessage = "Error: " . $sql . "<br>" . $conn->error;
		}
	
	} else {
		$errormessage = "Error: All fields are required";
	}
}
// logout user
if (isset($_GET['logout'])){
	unset($_SESION['email']);
	header ('location: index.php');
	exit();
}
?>
<?php include_once "includes/header.php"; ?>
	  <div id="container" style="width: 70%">
	    <header id="header">
		  <h3>Temi Multipurpose Store</h3>
		</header>
		<div class="menu">
		  <div class="menu_list">
		     <label>Welcome&nbsp;<?php echo $name; ?></label>
			 <img src="images/avata2.png" alt="<?php echo $name; ?>" class="avatar"><br>
			 <form action='' method='GET'>
			   <center><button type='submit' class='btn btn-primary' style='padding: 3px 6px 3px 6px;' name='logout'>Logout</button></center>
			 </form>
			 <ul class="exect_nav">
				<!--<li class="viewmenu"><a href="#">↓ View Menu</a></li>-->
				<li class="viewmenu2"><a href="admin_dashboard.php">» Manage Products</a></li>
				<li class="viewmenu2"><a href="addNew.php">» Add New Products</a></li>
				<li class="viewmenu2"><a href="view.php">» View Products</a></li>
				<li class="viewmenu2"><a href="#">» Stock Valuation</a></li>
				<li class="viewmenu2"><a href="addClient.php">» Add Client</a></li>
			</ul>
		  </div>
		  <div class="article_section">
		     <center><label class="avail">Add New Client</label></center>
			 <?php if ((isset($_POST['add'])) && ($result === TRUE)){
				 echo '<div class="alert alert-success alert-dismissable fade in">'.$successmessage.'<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a></div>';
			 }
			 if ((isset($_POST['add'])) && ($result !== TRUE)){
				 echo '<div class="alert alert-danger alert-dismissable fade in">'.$errormessage.'<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a></div>';
			 }
			 ?>
			 <form action="" method="POST">
				<div class="input-group">
					<span class="input-group-addon">Name</span>
					<input id="fullname" type="text" class="form-control" onkeyup="showHint(this.value)" name="fullname" placeholder="" value="<?=@$fullname?>">
				</div>
				<label class="ajx"><span id="txtHint"></span></label>
				<br>
				<div class="input-group">
					<span class="input-group-addon">Email</span>
					<input id="email" type="text" class="form-control" onkeyup="showHint2(this.value)" name="emailadd" placeholder="" value="<?=@$emailadd?>">
				</div>
				<label class="ajx"><span id="txtHint2"></span></label>
				<br>
				<div class="input-group">
					<span class="input-group-addon">Password</span>
					<input id="pass" type="password" class="form-control" name="password" placeholder="" value="">
				</div><br>
				<button type="submit" class="btn btn-primary pull-right" onclick="showhide()" name="add">Add Client</button>
			</form><br><br><br><br>
		  </div>
		</div>
		<?php include_once "includes/footer.php"; ?>
	  </div>
</body>
</html>
</body>