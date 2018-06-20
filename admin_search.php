<?php
include_once "includes/dbconnect.php";
session_start();
ob_start();
if (isset($_SESSION['email'])){
	$email = $_SESSION['email'];
} else {
	header ('location: index.php');
}
// select from database
$errormessage = "";
$successmessage = "";
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
$products = "";
if (isset($_POST['searchbtn'])){
	if (isset($_POST['search']) && $_POST['search'] != ""){
		$search = $_POST['search'];
		$search = preg_replace("#[^a-z 0-9?!]#i", "", $_POST['search']);
		
		$sqlpro = "SELECT `product_id`, `name`, `category`, `quantity`, `date_added`, `selling_price`, `manufacturer` AS proname FROM `products` WHERE `name` LIKE '%$search%' OR `category` LIKE '%$search%'";
		$resultpro = $conn->query($sqlpro);
		if ($resultpro->num_rows > 0){
			while ($row = $resultpro->fetch_assoc()){
				$id = $row['product_id'];
				$category = $row['category'];
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
			    //$successmessage = "<p style='color: green; font-size: 18px; margin-left: 5px'>$resultpro result found for <b>$search</b></p>";
				
			}
		} else {
			$errormessage = "<p style='color: #880000; font-size: 18px; margin-left: 5px'>No result found for <b>$search</b></p>";
		}
		
	} else {
		$errormessage = "<p style='color: #880000; font-size: 18px; margin-left: 5px'>No result found for <b>empty search</b></p>";
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
		     <center><label class="avail">Available Products</label></center>
			<div class="search">
			 <form class="navbar-form navbar-left" role="search" action="admin_search.php" method="GET">
				<div class="form-group">
				<input type="text" class="form-control" name='search' placeholder="Search Products">
			</div>
				<button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span></button>
			</form>
			</div>
			<hr>
			<?php echo $successmessage; ?>
			<?php echo $errormessage; ?>
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
		</div>
		<?php include_once "includes/footer.php"; ?>
	  </div>
</body>
</html>
</body>