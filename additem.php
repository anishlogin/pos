<?php
include "db.php";
include "functions.php";
trylogin();
if (isset($_POST["submit"])) {

	
	
	try {
		
		if($_POST["id"]){
			$sql = "UPDATE products SET name='".$_POST["name"]."',code='".$_POST["code"]."',mrp=".$_POST["mrp"].",retail_price=".$_POST["retail_price"].",unit='".$_POST["unit"]."',qty=".$_POST["qty"].",rate=".$_POST["rate"]." WHERE id=".$_POST['id'];
			$result = mysqli_query($conn, $sql);
			header('Location: itemlist.php');
			die();
		}
		else{
			$sql = "select * from products where code = '{$_POST['code']}'";
			$result = mysqli_query($conn,$sql);
			if($result->num_rows != 0){
				echo '<b style="text-align:center">This Code is already available.Please change it</b>';
				die();
			}

			$sql = "INSERT INTO products (name,code,mrp,retail_price,unit,qty,rate) VALUES ('".$_POST['name']."','".$_POST['code']."',".$_POST['mrp'].",".$_POST['retail_price'].",'".$_POST['unit']."',".$_POST['qty'].",".$_POST['rate'].")";
			$result = mysqli_query($conn, $sql);
			header('Location: itemlist.php');
		}
	} catch (Exception $e) {
		echo '<b style="text-align:center">'.$e->getMessage().'</b>';
		echo '<a href="'.$_SERVER['HTTP_REFERER'].'">Go Back</a>';
	}

}