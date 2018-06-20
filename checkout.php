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

// generate ticket_id for this each transaction
for ($index = 0; $index < 1; $index++)
{
	$rand = mt_rand(1000000000, (int)9999999999);
	$ticket_id = 'TMS-'.$rand;
	$sql = "SELECT ticket_id FROM transactions WHERE ticket_id='$ticket_id'";
	$check = $conn->query($sql);
	if ($check->num_rows > 0){
		$index -= 1;
	} 
} 
// insert into transactions table
$cname = $_POST['cname'];
$discount = $_POST['discount'];
$payment_date = date('Y-m-d');
$cartTotal = $_SESSION['cartTotal'];
$totaltopay = $cartTotal - $discount;
$amount_received = htmlspecialchars($_POST['amount']);

if (empty($amount_received) == false){
	$insert = "INSERT INTO transactions (`cname`, `date`, `amount_received`, `discount`, `amount_total`, `ticket_id`) VALUES ('$cname', '$payment_date', '$amount_received', '$discount', '$totaltopay', '$ticket_id')";
	$check = $conn->query($insert);
} else {
	?><script type="text/javascript">
	alert ('Please enter amount received');
	window.location = 'cart.php';
	</script><?php
	exit();
}

// list for payer
$sql8="SELECT * FROM `transactions`";
$check8 = $conn->query($sql8);
if($check8->num_rows > 0){
	while($row=$check8->fetch_assoc()){
	$id=$row["id"];
	$cname=$row["cname"];
	$amount_received=$row["amount_received"];
	$discount = $row['discount'];
	$total = $row['amount_total'];
	$ticketid = $row['ticket_id'];
	}
}
// list for receipt

$cartOutput = "";
$cartTotal = "";

	if(!isset($_SESSION['CartArray']) || count($_SESSION['CartArray']) < 1){
		$cartOutput = "<p style='color: red; text-align: center; font-weight: bold; font-size: 20px'>Your Billing Cart is empty</p>";
		} else {
			foreach ($_SESSION['CartArray'] as $each_item){
				$item_id = $each_item['item_id'];
				$sql2 = "SELECT * FROM products WHERE product_id='$item_id' LIMIT 1";
				$check2 = $conn->query($sql2);
				while ($row2=$check2->fetch_assoc()){
					$product_name = $row2['name'];
					$sp = $row2['selling_price'];
					$quantity_available = $row2['quantity'];
					
					
					$priceTotal = $sp * $each_item['quantity'];
					$cartTotal = $priceTotal + $cartTotal;
					$change = $amount_received - $total;
					// dynamic table row assembly
	
					$cartOutput .= "<tr>";
					$cartOutput .= "<td style='font-family: New Times Roman; font-size: 13px; text-align: center; border: 1px dashed #000000'>" . $product_name . "</td>";
					$cartOutput .= "<td style='font-family: New Times Roman; font-size: 13px; text-align: center; border: 1px dashed #000000'>N" . $priceTotal . "</td>";
					
					$cartOutput .= "</tr>";
				
					$cartTotal = $cartTotal;
					
					$sql4 = "SELECT * FROM products WHERE `product_id`='$item_id' LIMIT 1";
					$check4 = $conn->query($sql4);
					while ($row2=$check4->fetch_assoc()){
						$quantity = $row2['quantity'];
					}
					$queryLan = "SELECT * FROM products WHERE `product_id`='$item_id' & `quantity`='$quantity'";
					$Check = $conn->query($queryLan);
					$defaultQuanitity = $each_item['quantity'];
					$newQuantity = $quantity_available - $each_item['quantity'];
					$upDate = "UPDATE products SET `quantity`='$newQuantity' WHERE `product_id`='$item_id'"; 
					$Check2 = $conn->query($upDate);
					
					$queryLan2 = "SELECT * FROM products WHERE `product_id`='$item_id' & `quantity`='$quantity'";
					$Check = $conn->query($queryLan2);
					$emptyQuantity = "0";
					if ($newQuantity < $emptyQuantity){
						$null = "-";
						$upDate = "UPDATE products SET `quantity`='$null' WHERE `product_id`='$item_id'";
						$Check3 = $conn->query($upDate);
						$upDate5 = "DELETE FROM products WHERE `quantity`='$null' && `product_id`='$item_id'";
						$Check5 = $conn->query($upDate5);
						
					}
				}
			}
		}
 
 
  unset($_SESSION['CartArray']);
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<link rel="shortcut icon" href="/favicon.ico" >
<title>Receipt</title>
</head>
<body>
<div id="receipt_wrapper" style="width: 272px; height: 756px; margin-left: auto; margin-right: auto">
  <center><label style="font-family: arial black; font-size: 13px">Temi Multipurpose Store</label></center>
  <center><label style="font-family: monospace; font-weight: bold; font-size: 14px">Product Charge Slip</label></center>
  <label style="font-family: Calibri (Body); font-weight: bold; font-size: 13px"><?php echo "Payer ID:" . " " .$cname; ?></label>
  <center><label style="font-family: Calibri (Body); font-weight: bold; font-size: 13px"><?php echo date("l jS \of F Y"). "," . " " . date('H:i:s'); ?></label></center>
	 <table width="272px"  style="margin-left: auto; margin-right: auto; min-height: 150px" cellspacing="0" cellpadding="0"  border="0" >
	   <tr>
		<td width="50%" style='font-family: arial; text-align: center; font-size: 13px; font-weight: bold; border: 1px dashed #000000'>Products</b></td>
		<td width="50%" style='font-family: arial; text-align: center; font-size: 13px; font-weight: bold; border: 1px dashed #000000'>Total</b></td>
		</tr>
		<?php echo $cartOutput; ?>
		<!--tr>
		 <td>&nbsp;</td>
		 <td>&nbsp;</td>
		</tr-->
	  </table>
	  <?php echo '<h2 style="font-family: arial black;text-align: center; font-weight: bold; font-size: 15px">Total: &#8358;'.$cartTotal.'</h2>' ?>
	  <center><label style="font-family: arial"><?php echo "Change: " . " " . "&#8358;".$change; ?> -----</label>
	  <label style="font-family: arial"><?php echo "Discount: " . " " . "&#8358;".$discount; ?></label></center>
	  <center><label style='font-family: Bradley Hand ITC; font-size: 24px'>signed</label></center>
	  <center><img src="barcode.php?text=<?php echo $ticketid; ?>&print=true" alt="ticket_id"></center>
	  <center><label style="font-family: Calibri (Body); font-weight: normal; font-size: 14px">Powered By Amjos + Aksamtech Concepts</label></center>
	 </div>
	  
</body>
</html>