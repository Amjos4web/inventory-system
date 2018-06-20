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

// select products
if (isset($_GET['product_id'])){
	$product_id = $_GET['product_id'];
	
	$sqlpro = "SELECT * FROM products WHERE product_id='".$product_id."' LIMIT 1";
	$resultpro = $conn->query($sqlpro);
	if ($resultpro->num_rows > 0){
		while ($row = $resultpro->fetch_assoc()){
			$id = $row['product_id'];
			$category = $row['category'];
			$manu = $row['manufacturer'];
			$brand = $row['brand'];
			$pname = $row['name'];
			$date_added = $row['date_added'];
			$pd = $row['date_purchase'];
			$manu = $row['manufacturer'];
			$category = $row['category'];
			$pp = $row['purchase_place'];
			$up = $row['unit_price'];
			$brand = $row['brand'];
			$sp = $row['selling_price'];
			$da = $row['date_added'];
			$ed = $row['expiring_date'];
			$qty = $row['quantity'];
			
		}
	} else {
		$errormessage = "No product found with that ID in the database";
	}
}

// parse products into the database
if(isset($_POST['update'])){
	$pname = htmlspecialchars($_POST['pname']);
	$brand = htmlspecialchars($_POST['brand']);
	$manu = htmlspecialchars($_POST['manu']);
	$category = htmlspecialchars($_POST['category']);
	$purchase_date = htmlspecialchars($_POST['pd']);
	$purchase_place = htmlspecialchars($_POST['pp']);
	$unit_price = htmlspecialchars($_POST['uc']);
	$expiry_date = htmlspecialchars($_POST['ed']);
	$selling_price = htmlspecialchars($_POST['sp']);
	$qty = htmlspecialchars($_POST['qty']);
	$date_added = date('Y-m-d');
	$server_date = date('Y-m-d');
	
	if (empty($pname && $unit_price && $selling_price) == false){
		if ((preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $purchase_date)) && (preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $expiry_date)) && (preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $date_added))){
			$insert = "UPDATE products SET `name`='$pname', `manufacturer`='$manu', `purchase_place`='$purchase_place', `category`='$category', `brand`='$brand', `unit_price`='$unit_price', `date_purchase`='$purchase_date', `expiring_date`='$expiry_date', `selling_price`='$selling_price', `quantity`='$qty', `date_added`='$date_added', `server_date`='$server_date' WHERE product_id='".$product_id."' LIMIT 1";
			$result = $conn->query($insert);
			if ($result === TRUE){
				$successmessage = "<strong>Success!</strong> $pname has been updated Successfully";
			} else {
				$errormessage = "Error: " . $sql . "<br>" . $conn->error;
			}
		} else {
			$errormessage = "Error: Date Format Should Be YYYY-MM-DD";
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
				<li class="viewmenu2"><a href="stockValuation.php">» Stock Valuation</a></li>
				<li class="viewmenu2"><a href="addClient.php">» Add Client</a></li>
			</ul>
		  </div>
		  <div class="article_section">
		     <center><label class="avail">Edit <?php echo $pname; ?></label></center>
			 <?php if ((isset($_POST['update'])) && ($result === TRUE)){
				 echo '<div class="alert alert-success alert-dismissable fade in">'.$successmessage.'<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a></div>';
			 }
			 if ((isset($_POST['update'])) && ($result !== TRUE)){
				 echo '<div class="alert alert-danger alert-dismissable fade in">'.$errormessage.'<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a></div>';
			 }
			 ?>
			 <form action="" method="POST">
				<div class="input-group">
					<span class="input-group-addon">Product Name</span>
					<input id="fname" type="text" class="form-control" onkeyup="showHint(this.value)" name="pname" placeholder="" value="<?=@$pname?>">
				</div>
				<label class="ajx"><span id="txtHint"></span></label>
				<br>
				<div class="input-group">
					<span class="input-group-addon">Brand</span>
					<input id="lname" type="text" class="form-control" onkeyup="showHint2(this.value)" name="brand" placeholder="" value="<?=@$brand?>">
				</div>
				<label class="ajx"><span id="txtHint2"></span></label>
				<br>
				<div class="input-group">
					<span class="input-group-addon">Manufacturer</span>
					<input id="manu" type="text" class="form-control" name="manu" placeholder="" value="<?=@$manu?>">
				</div><br>
				<div class="input-group">
					<span class="input-group-addon">Category</span>
					  <select class="form-control" id="member" name="category">
					     <option value="<?php echo $category; ?>"><?php echo $category; ?></option>
						 <option value="Food Stuff">Food Stuff</option>
						 <option value="Toothpaste">ToothPaste</option>
						 <option value="Beverages">Beverages</option>
						 <option value="Vegetable Oil">Vegetable Oil</option>
						 <option value="Noodles">Noodles</option>
						 <option value="Spaghetti">Spaghetti</option>
						 <option value="Seasoning">Seasoning</option>
						 <option value="Soap">Soap</option>
                          <option value="Powder">Powder</option>
					   </select>
				</div><br>
				<div class="input-group">
					<span class="input-group-addon">Purchase Date</span>
					<input id="pdate" type="text" class="form-control" name="pd" placeholder="YYYY-MM-DD" value="<?=@$pd?>">
				</div><br>
				<div class="input-group">
					<span class="input-group-addon">Purchase Place</span>
					<input id="pp" type="text" class="form-control" name="pp" placeholder="" value="<?=@$pp?>">
				</div><br>
				<div class="input-group">
					<span class="input-group-addon">Expiry Date</span>
					<input id="pdate" type="text" class="form-control" name="ed" placeholder="YYYY-MM-DD" value="<?=@$ed?>">
				</div><br>
				<div class="input-group">
					<span class="input-group-addon">Unit Cost</span>
					<input id="nc" type="text" class="form-control" name="uc" placeholder="" value="<?=@$up?>">
				</div><br>
				<div class="input-group">
					<span class="input-group-addon">Selling Price</span>
					<input id="sp" type="text" class="form-control" name="sp" placeholder="" value="<?=@$sp?>">
				</div><br>
				<div class="input-group">
					<span class="input-group-addon">Quantity</span>
					<input id="q" type="text" class="form-control" name="qty" placeholder="" value="<?=@$qty?>">
				</div><br>
				<button type="submit" class="btn btn-primary pull-right" onclick="showhide()" name="update">Update Product</button>
			</form><br><br><br><br>
		  </div>
		</div>
		<?php include_once "includes/footer.php"; ?>
	  </div>
</body>
</html>
</body>