<?php
	
	include "db.php";
    include "functions.php";

	$params = $columns = $totalRecords = $data = array();

	$params = $_REQUEST;

	$columns = $params["columns"];
	

	$where_condition = $sqlTot = $sqlRec = "";

	if( !empty($params['search']['value']) ) {
		$where_condition .=	" WHERE ";
		$where_condition .= " ( BillNo LIKE '%".$params['search']['value']."%' ";    
		$where_condition .= " OR Partyname LIKE '%".$params['search']['value']."%' ";
		$where_condition .= " OR BillAmount LIKE '%".$params['search']['value']."%' )";
	}
	$start_date = $params["start_date"];
	$end_date = $params["end_date"];
	if ($start_date) {
		$start_date = date("Y-m-d H:i:s",strtotime($start_date));
		$where_condition = $where_condition ? $where_condition." AND InvDate >='".$start_date."'" : "WHERE InvDate >='".$start_date."'";
	}
	if ($end_date) {
		$end_date = date("Y-m-d H:i:s",strtotime($end_date));
		$where_condition = $where_condition ? $where_condition." AND InvDate <='".$end_date."'" : "WHERE InvDate >='".$end_date."'";
	}

	$sql_query = " SELECT * FROM tblpos ";
	$sqlTot .= $sql_query;
	$sqlRec .= $sql_query;
	
	if(isset($where_condition) && $where_condition != '') {

		$sqlTot .= $where_condition;
		$sqlRec .= $where_condition;
	}

 	$sqlRec .=  " ORDER BY ". $columns[$params['order'][0]['column']]["data"]."   ".$params['order'][0]['dir'];
	$queryTot = mysqli_query($conn, $sqlTot) or die("Database Error:". mysqli_error($conn));

	$totalRecords = mysqli_num_rows($queryTot);
	$queryRecords = mysqli_query($conn, $sqlRec) or die("Error to Get the Post details.");

	while( $row = mysqli_fetch_assoc($queryRecords) ) { 
		$sql_item_query = "SELECT *,( select retail_price from products WHERE code = ItemCode) as dealer_price FROM tblposdetails WHERE postblid = ".$row["posid"];
		$queryItems = mysqli_query($conn, $sql_item_query);
		$row["items"] = mysqli_fetch_all($queryItems,MYSQLI_ASSOC);
		$data[] = $row;
		// $data["items"] = [];
	}	

	$json_data = array(
		"draw"            => intval( $params['draw'] ),   
		"recordsTotal"    => intval( $totalRecords ),  
		"recordsFiltered" => intval($totalRecords),
		"data"            => $data
	);

	echo json_encode($json_data);
?>