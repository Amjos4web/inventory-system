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

// select products
$products = "";
$successmessage = "";
$totalamount = "";
$totaldiscount = "";
if (isset($_POST['process'])){
	$from = $_POST['from'];
	$to = $_POST['to'];
	if (empty($from && $to) == false){
		if ((preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $from)) && (preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $to))){
			$sqlpro = "SELECT * FROM transactions WHERE `date` >= '$from' AND `date` <= '$to'";
			$resultpro = $conn->query($sqlpro);
			if ($resultpro->num_rows > 0){
				while ($row = $resultpro->fetch_assoc()){
					$id = $row['id'];
					$total = $row['amount_total'];
					$cname = $row['cname'];
					$discount = $row['discount'];
					$ticketid = $row['ticket_id'];
					if ($discount == ''){
						$discount = '0';
					}
					if ($cname == ''){
						$cname = 'Customer';
					}
					
					$products .= "<tbody>";
					$products .= "<tr>";
					$products .= "<th scope='row'>".$id."</th>";
					$products .= "<td>".$cname."</td>";
					$products .= "<td>".$ticketid."</td>";
					$products .= "<td>&#8358;".$discount."</td>";
					$products .= "<td>&#8358;".$total."</td>";
					$products .= "</tr>";
					$products .= "</tbody>";
					
				}
			} else {
				$errormessage = "No product yet in the database";
			}
		} else {
			$errormessage = "Date Format Should be YYYY-MM-DD";
		}
	} else {
		$errormessage = "Please enter dates";
	}
	
	$sum = "SELECT  SUM(`amount_total`) AS total_sum FROM `transactions` WHERE `date` >= '$from' AND `date` <= '$to'";
	$sum2 = $conn->query($sum);
	if ($sum3 = $sum2->fetch_array()){
		$totalamount = $sum3['total_sum'];
	
	}
	$sumdis = "SELECT  SUM(`discount`) AS total_discount FROM `transactions` WHERE `date` >= '$from' AND `date` <= '$to'";
	$sumdis2 = $conn->query($sumdis);
	if ($sumdis3 = $sumdis2->fetch_array()){
		$totaldiscount = $sumdis3['total_discount'];
	
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
				<li class="viewmenu2"><a href="admin_dashboard.php">» Manage Products</a></li>
				<li class="viewmenu2"><a href="addNew.php">» Add New Products</a></li>
				<li class="viewmenu2"><a href="view.php">» View Products</a></li>
				<li class="viewmenu2"><a href="stockValuation.php">» Stock Valuation</a></li>
				<li class="viewmenu2"><a href="addClient.php">» Add Client</a></li>
			</ul>
		  </div>
		  <div class="article_section">
		     <center><label class="avail">Stock Income Summary</label></center>
			<?php if ((isset($_POST['process'])) && (empty($from && $to) == TRUE) && (!(preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $from)) && !(preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $to)))){
				 echo '<div class="alert alert-danger alert-dismissable fade in">'.$errormessage.'<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a></div>';
			 }
			 ?>
			 <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
				<div class="input-group">
				   <span class="input-group-addon">From</span>
				   <input id="from" type="text" class="form-control" name="from" value="<?=@$from?>" placeholder="YYYY-MM-DD">
					</div></td><br>
				   <div class="input-group">
						<span class="input-group-addon">To</span>
						<input id="to" type="text" class="form-control" name="to"  value="<?=@$to?>" placeholder="YYYY-MM-DD">
					</div><br>
				<button type="submit" class="btn btn-danger pull-right" name="process">Process</button>
			 </form><br>
			 <hr>
			 <div class="table" style="max-height: 200px; overflow-y: auto;">
		     <table class="table table-striped">
			   <thead>
			     <tr>
				   <th>ID</th>
				   <th>Customer Name</th>
				   <th>Transactions ID</th>
				   <th>Discount</th>
				   <th>Total</th>
				 </tr>
			   </thead>
			   <?php echo $products; ?>
			 </table>
			 </div>
			 <center><label style="font-family: arial black; font-size: 16px;">Income Total: &nbsp; <?php echo "&#8358;".$totalamount; ?></label></center>
			 <center><label style="font-family: arial black; font-size: 16px;">Discount Total: &nbsp; <?php echo "&#8358;".$totaldiscount; ?></label></center>
			  <form action="printIncome.php?from=$from&to=$to" method="GET" target="_blank" id="jsform">
			  <input type="hidden" id="from" name="from" value="<?php echo $from; ?>">
				<input type="hidden" id="to" name="to" value="<?php echo $to; ?>">
			    <input type="button" onclick="document.getElementById('jsform').submit();" class="btn btn-default center-block" value="Print">
		    </form><br><br><br><br>
		  </div>
		</div>
		<?php include_once "includes/footer.php"; ?>
	  </div>
</body>
</html>
</body>