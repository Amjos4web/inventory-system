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


// delete item question for admin 
if (isset($_GET['deleteid'])){
	echo  '<center><h2 style="border: 1px solid #D8D8D8; padding: 10px; border-radius: 5px; font-family: Arial; font-size: 14px; text-transform: uppercase; background-color: rgb(255, 249, 242); color: rgb(211, 0, 0); width: 500px; margin-top: 250px">Do you really want to delete the Product with ID of'." ".$_GET['deleteid'].'? <a href="admin_dashboard.php?yesdelete='.$_GET['deleteid'].'" class="btn btn-danger" style="padding: 3px 6px 3px 6px">Yes</a>|<a href="admin_dashboard.php" class="btn btn-primary" style="padding: 3px 6px 3px 6px">No</a></h2></center>';
	exit();
}

// delete product item by admin
if (isset($_GET['yesdelete'])){
	$item_to_delete = $_GET['yesdelete'];
	$deletequery = "DELETE  FROM products WHERE product_id='$item_to_delete' LIMIT 1";
	$deletecheck = $conn->query($deletequery);
	if ($deletecheck === TRUE){
		header ('location: admin_dashboard.php');
		exit();
	} else {
		$errormessage = "Error has occured";
	}
	
}
// select products
$products = "";
$successmessage = "";
$noproduct = '';
$sqlpro = "SELECT * FROM products ORDER BY date_added DESC";
$resultpro = $conn->query($sqlpro);
if ($resultpro->num_rows > 0){
	while ($row = $resultpro->fetch_assoc()){
		$id = $row['product_id'];
		$category = $row['category'];
		$manufacturer = $row['manufacturer'];
		$qty = $row['quantity'];
		$pname = $row['name'];
		$date_added = $row['date_added'];
		$sp = $row['selling_price'];
		
		
		$products .= "<tbody>";
		$products .= "<tr>";
		$products .= "<th scope='row'>".$id."</th>";
		$products .= "<td>".$pname."</td>";
		$products .= "<td>&#8358;".$sp."</td>";
		$products .= "<td>".$qty."</td>";
		$products .= "<td>".$date_added."</td>";
		$products .= "<td><a href='edit.php?act=edit&product_id=$id' class='btn btn-primary' style='padding: 3px 6px 3px 6px'>Edit</a></td>";
		$products .= "<td><a href='admin_dashboard.php?act=delete&deleteid=$id' class='btn btn-danger' style='padding: 3px 6px 3px 6px'>Delete</a></td>";
		$products .= "</tr>";
		$products .= "</tbody>";
		
	}
} else {
	$noproduct = "No product yet in the store";
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
			 <form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='GET'>
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
		     <center><label class="avail">Manage Products</label></center>
			 <?php if ((isset($_GET['yesdelete'])) && ($deletecheck !== TRUE)){
				 echo '<div class="alert alert-danger alert-dismissable fade in">'.$errormessage.'<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a></div>';
			 }
			 ?>
			 <div class="search">
			 <form class="navbar-form navbar-left" role="search" action="admin_search.php" method="POST">
				<div class="form-group">
				<input type="text" class="form-control" name='search' placeholder="Search Products">
			</div>
				<button type="submit" class="btn btn-default" name="searchbtn"><span class="glyphicon glyphicon-search"></span></button>
			</form>
			</div>
			<?php if ($resultpro->num_rows < 1){
				 echo '<div class="alert alert-danger alert-dismissable fade in">'.$noproduct.'<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a></div>';
			 }
			 ?>
			<div class="table" style="max-height: 400px; overflow-y: auto;">
		     <table class="table table-striped">
			   <thead>
			     <tr>
				   <th>Product ID</th>
				   <th>Name</th>
				   <th>Selling Price</th>
				   <th>Quantity Avail.</th>
				   <th>Date Added</th>
				   <th>Edit</th>
				   <th>Delete</th>
				 </tr>
			   </thead>
			   <?php echo $products; ?>
			 </table>
			 </div>
			 <a href='printProducts.php?query=products' class='btn btn-default center-block' target="_blank">Print</a><br><br><br>
		  </div>
		</div>
		<?php include_once "includes/footer.php"; ?>
	  </div>
</body>
</html>
</body>