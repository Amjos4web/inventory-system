<?php 
include_once "includes/dbconnect.php";
session_start();
ob_start();
error_reporting(E_ALL & ~E_NOTICE);
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
?>
<?php
// check if the product id is set
if (isset($_POST['id'])){
	$product_id = $_POST['id'];
	$wasFound = false;
	$i = 0;
	// if the cert session is not set or cart array is empty
    if (!isset($_SESSION['CartArray']) || count($_SESSION['CartArray']) < 1){
		// run script if the cart is empty or not set
		$_SESSION['CartArray'] = array(0 => array('item_id' => $product_id, 'quantity' => 1));
	} else {
		// run the script if the cart has at least one in it
		foreach ($_SESSION['CartArray'] as $each_item){
			$i++;
			while (list($key, $value) = each($each_item)){
				if ($key == 'item_id' && $value == $product_id){
					// That item is in cart already, so let's adjust the quantity using array_splice()
					array_splice($_SESSION['CartArray'],$i-1,1, array(array('item_id' => $product_id, 'quantity' => $each_item['quantity'] + 1)));
					$wasFound = true;
				} // close the if condition
			} // close the while loop
		} // close the for each loop
	if ($wasFound == false){
		array_push($_SESSION['CartArray'], array('item_id' => $product_id, 'quantity' => 1));
	}
  }
  header("location: cart.php");
  exit();
}
?>
<?php
// if the user chooses to empty the cart
if (isset($_GET['cmd']) && $_GET['cmd'] == 'emptycart'){
	unset($_SESSION['CartArray']);
}
?>
<?php
// if the user chooses adjust quantity
if (isset($_POST['item_to_adjust']) && $_POST['item_to_adjust'] != ""){
	// run some code
	 $item_to_adjust = $_POST['item_to_adjust'];
	 $quantity = $_POST['quantity'];
	 $quantity = preg_replace("#[^0-9]#i", "",$quantity); // filter everything but numbers
	 if($quantity < 1){$quantity = 1;}
	 $i = 0;
	 foreach ($_SESSION['CartArray'] as $each_item){
		$i++;
		while (list($key, $value) = each($each_item)){
			if ($key == 'item_id' && $value == $item_to_adjust){
				// That item is in cart already, so let's adjust the quantity using array_splice()
				array_splice($_SESSION['CartArray'],$i-1,1, array(array('item_id' => $item_to_adjust, 'quantity' => $quantity)));
			} // close the if condition
		} // close the while loop
	} // close the for each loop
}
?>
<?php
// if the user want to remove an item from the list
if (isset($_POST['index_to_remove']) && $_POST['index_to_remove'] != ""){
	// if the user want to remove item form cart
	$key_to_remove = $_POST['index_to_remove'];
	if (count($_SESSION['CartArray']) <= 1){
		unset($_SESSION['CartArray']);
	} else {
		unset($_SESSION['CartArray'][$key_to_remove]);
		sort($_SESSION['CartArray']);
	}
	
}
?>
<?php
// render the cart for user to viewing
$cartOutput = "";
$cartTotal = "";
if(!isset($_SESSION['CartArray']) || count($_SESSION['CartArray']) < 1){
	$cartOutput = "<p style='color: #D8000C; background-color: #FFBABA; border-radius:.5em; width: 300px; border: 1px solid #D8D8D8; padding: 5px; border-radius: 5px; margin-left: auto; margin-right: auto; font-family: Arial; font-size: 11px; text-transform: uppercase; text-align: center; text-transform: uppercase'>Billing Cart Is Empty</p>";
} else {
	$i = 0;
	foreach ($_SESSION['CartArray'] as $each_item){
		$item_id = $each_item['item_id'];
		$sql2 = "SELECT * FROM `products` WHERE `product_id`='$item_id' LIMIT 1";
		$check2 = $conn->query($sql2);
		while ($row=$check2->fetch_assoc()){
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
			
			$priceTotal = $sp * $each_item['quantity'];
			$cartTotal = $priceTotal + $cartTotal;
			// dynamic table row assembly
			$cartOutput .= "<tbody>";
			$cartOutput .= "<tr>";
			$cartOutput .= "<th scope='row'>" . $item_id . "</th>";
			$cartOutput .= "<td>".$pname."</td>";
			$cartOutput .= "<td>".$qty."</td>";
			$cartOutput .= "<td>&#8358;". $sp . "</td>";
			$cartOutput .= '<td><form action="cart.php" method="post"><input type="text" name="quantity" value="'.$each_item['quantity'].'" size="1" maxlength="2"><input type="submit" name="adjustBtn' . $item_id . '" value="Change" id="adjustBtn"><input name="item_to_adjust" type="hidden" value= "'.$item_id.'"></form></td>';
			$cartOutput .= "<td>&#8358;" . $priceTotal . "</td>";
			$cartOutput .= '<td><form action="cart.php" method="post"><input type="submit" name="deleteBtn' . $item_id . '" value="X" id="deleteBtn" style="background-color: #880000; color: #fff"><input name="index_to_remove" type="hidden" value= "'.$i.'"></form></td>';
			$cartOutput .= "</tr>";
			$cartOutput .= "</tbody>";
			$i++;
		
            $cartTotal = $cartTotal;
			$_SESSION['cartTotal'] = $cartTotal;
		}
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
			 <form action='<?php echo $_SERVER['PHP_SELF']; ?>' method='GET'>
			   <center><button type='submit' class='btn btn-primary' style='padding: 3px 6px 3px 6px;' name='logout'>Logout</button></center>
			 </form>
			 <ul class="exect_nav">
				<!--<li class="viewmenu"><a href="#">↓ View Menu</a></li>-->
				<li class="viewmenu2"><a href="client_dashboard.php">» Available Products</a></li>
				<li class="viewmenu2"><a href="cart.php">» View Cart</a></li>
			</ul>
		  </div>
		  <div class="article_section">
		     <center><label class="avail">Your Cart</label></center>
			 <form action="checkout.php" method="POST" id="jsForm" target="_blank">
			   <div class="input-group">
					<span class="input-group-addon">Customer Name</span>
					<input id="cname" type="text" class="form-control" name="cname" placeholder="Mr. Customer">
				</div><br>
				<div class="input-group">
					<span class="input-group-addon">Amount Received</span>
					<input id="amount" type="text" class="form-control" name="amount" placeholder="&#8358;">
				</div><br>
				<div class="input-group">
					<span class="input-group-addon">Discount</span>
					<input id="discount" type="text" class="form-control" name="discount" placeholder="&#8358;">
				</div>
			 </form><br>
		     <table class="table table-striped">
			   <thead>
			     <tr>
				   <th>ID</th>
				   <th>Name</th>
				   <th>Qty Avail.</th>
				   <th>Selling Price</th>
				   <th>Quantity</th>
				   <th>Total</th>
				   <th>Remove</th>
				 </tr>
			   </thead>
			   <?php echo $cartOutput; ?>
			 </table>
			 <center><label style="font-family: arial black; font-size: 16px;">Cart Total: &nbsp; <?php echo "&#8358;".$cartTotal; ?></label></center>
			  <input type="button" onclick="document.getElementById('jsForm').submit();" class="btn btn-danger"  value="Proceed To Checkout">
			   <a href='client_dashboard.php' class='btn btn-primary pull-right'>Add More Products</a><br><br>
			   <a href='cart.php?cmd=emptycart' class='btn btn-danger'>Empty Cart</a>
		  </div>
		</div>
		<?php include_once "includes/footer.php"; ?>
	  </div>
</body>
</html>
</body>