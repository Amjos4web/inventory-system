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
$successmessage = "";
$totalamount = "";
$totaldiscount = "";
// see if the posted search query field is set and has a value
if ((isset($_GET['from'])) AND (isset($_GET['to']))){
	$from = $_GET['from'];
	$to = $_GET['to'];
	
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
 <center><label style="font-size: 16px; font-family: monospace">Income Data from <?php echo $from; ?> to <?php echo $to; ?></label></center>
 <center><label style="font-family: Calibri (Body); font-weight: bold; font-size: 11px;"><?php echo date("l jS \of F Y"). "," . " " . date('H:i:s'); ?></label></center>
	  <center><table class="table table-striped" border="1">
		<thead>
		  <tr>
		    <th>ID</th>
			<th>Customer Name</th>
			<th>Transactions ID</th>
			<th>Discount</th>
			<th>Total</th>
		 </tr>
	    </thead></center>
		<?php echo $products; ?>
	  </table>
	  <center><label><b>Total Amount:</b>&nbsp;<?php echo "&#8358;".$totalamount; ?>&nbsp;&nbsp;&nbsp;<b>Discount Total:</b>&nbsp;<?php echo "&#8358;".$totaldiscount; ?> </label></center>
	  <center><label>Powered By Amjos + Aksamtech Concepts</label></center>
  </div>
</body>
</html>