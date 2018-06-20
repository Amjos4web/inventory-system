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
$sql = "SELECT * FROM client WHERE eMail='".$email."' LIMIT 1";
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
				<li class="viewmenu2"><a href="client_dashboard.php">» Available Products</a></li>
				<li class="viewmenu2"><a href="cart.php">» View Cart</a></li>
			</ul>
		  </div>
		  <div class="article_section">
		     <center><label class="avail"><?php echo $pname; ?></label></center>
			 <?php
			 if ((!isset($_GET['product_id'])) && ($_GET['product_id']) == ""){
				 echo '<div class="alert alert-danger alert-dismissable fade in">'.$errormessage.'<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a></div>';
			 }
			 ?>
			  <table class="table table-striped">
			     <tr>
				   <td><b>Product ID<b><td>
				   <td><?php echo $product_id; ?><td>
				 </tr>
				 <tr>
				   <td><b>Product Name</b><td>
				   <td><?php echo $pname; ?><td>
				 </tr>
				 <tr>
				   <td><b>Brand</b><td>
				   <td><?php echo $brand; ?><td>
				 </tr>
				 <tr>
				   <td><b>Category</b><td>
				   <td><?php echo $category; ?><td>
				 </tr>
				 <tr>
				   <td><b>Manufacturer</b><td>
				   <td><?php echo $manu; ?><td>
				 </tr>
				 <tr>
				   <td><b>Purchase Place</b><td>
				   <td><?php echo $pp; ?><td>
				 </tr>
				 <tr>
				   <td><b>Purchase Date</b><td>
				   <td><?php echo $pd; ?><td>
				 </tr>
				 <tr>
				   <td><b>Expiry Date</b><td>
				   <td><?php echo $ed; ?><td>
				 </tr>
				 <tr>
				   <td><b>Unit Price</b><td>
				   <td><?php echo "&#8358;".$up; ?><td>
				 </tr>
				 <tr>
				   <td><b>Selling Price</b><td>
				   <td><?php echo "&#8358;".$sp; ?><td>
				 </tr>
				 <tr>
				   <td><b>Quantity Available</b><td>
				   <td><?php echo $qty; ?><td>
				 </tr>
				 <tr>
				   <td><b>Date Added</b><td>
				   <td><?php echo $da; ?><td>
				 </tr>
			 </table>
			 <form action="cart.php" method="post" name="Cart">
				<input type="hidden" id="product_id" name="id" value="<?php echo $product_id; ?>">
			    <button type="submit" class="btn btn-danger pull-right" onclick="showhide()" name="addToCart"><span class="glyphicon glyphicon-shopping-cart"></span>&nbsp;Add To Cart</button>
			 </form><br><br><br><br>
			</div>
		</div>
		<?php include_once "includes/footer.php"; ?>
	  </div>
</body>
</html>
</body>