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

$products = "";
// see if the posted search query field is set and has a value
if (isset($_GET['query']) && $_GET['query'] == 'products'){
	$sqlpro = "SELECT * FROM products ORDER BY name ASC";
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
			$products .= "<td>".$category."</td>";
			$products .= "<td>&#8358;".$sp."</td>";
			$products .= "<td>".$qty."</td>";
			$products .= "<td>".$date_added."</td>";
			$products .= "</tr>";
			$products .= "</tbody>";
			
		}
	} else {
		$noproduct = "No product yet in the store";
	}
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<link rel="shortcut icon" href="/favicon.ico" >
<title>Stock Income Summary</title>
</head>
<body>
<div id="requisition_receipt" style="width: 800px; min-height: 300px; margin-left: auto; margin-right: auto">
 <h1 style="text-align: center; font-size: 20px; font-family: arial;">TEMI MULTIPURPOSE STORE</h1>
 <center><label style="font-size: 16px; font-family: monospace">Available Products</label></center>
 <center><label style="font-family: Calibri (Body); font-weight: bold; font-size: 11px;"><?php echo date("l jS \of F Y"). "," . " " . date('H:i:s'); ?></label></center>
	  <center><div class="table">
		     <table class="table table-striped" border='1'>
			   <thead>
			     <tr>
				   <th>Product ID</th>
				   <th>Name</th>
                   <th>Category</th>
				   <th>Selling Price</th>
				   <th>Quantity Avail.</th>
				   <th>Date Added</th>
				 </tr>
			   </thead>
			   <?php echo $products; ?>
			 </table>
			 </div></center>
	  <center>
	    <p>
	      <label>Powered By Amjos + Aksamtech Concepts</label>
	    </p>
	  </center>
  </div>
</body>
</html>