<?php 
/**
* 
*
*Class For App
*
**/
date_default_timezone_set("Asia/Kolkata");

 class ClassApp 

 {

 	public $db;
 	public $counters;

 	function __construct($db)

 	{

 		$this->db = $db;
 		$this->counters = ["dsafsdfdsf"=>[
 			"counterid"=>1,
 			"counterkey"=>"dsafsdfdsf",
 		]];

 	}
 	public function searchForId($id, $array , $except) {
	   foreach ($array as $key => $val) {
	
	       if ($val->id == $id && $key != $except) {
	           return  array('key' => $key );
	       }
	   }
	   return false;
	}

 	public function getProductByCode($code){
        $item_code = "code";

        $query = $this->db->query("SELECT id,name,code,mrp,unit from products where code = ? ", [$code]);
        
        if (!($query && $query->num_rows)) {
            echo json_encode(['success' => false, 'reason' => 'Item Not Found', 'details' => []]);
            exit();
        }

        $item = $query->fetchArray();

        echo json_encode(['success' => true, 'reason' => 'Details Found', 'details' => $item]);
        exit();


    }

 	public function getProductList($num,$name){

 		$perpage = 30;

 		$start = ($num-1)*$perpage;

 		$where = !empty($name) ? 'WHERE name LIKE "'.$name.'%" OR code LIKE "'.$name.'%"': ' ' ;

 		$sql = "SELECT code, id, name, mrp as price ,unit from products ".$where;
        

 		$query = $this->db->query($sql." limit " . $start . "," . $perpage);

 		if ($query && $query->num_rows) {

	        $item = $query->fetchAll();

	        echo json_encode(['success' => true, 'reason' => 'Details Found', 'details' => $item]);

	    }else{

            echo json_encode(['success' =>false,'reason' => 'Item Not Found', 'details' => [],'query'=>$sql." limit " . $start . "," . $perpage]);

	    }

 	}

 	public function getPendingList($num,$name,$date,$counterno){

 		$perpage = 30;

 		$start = ($num-1)*$perpage;

 		$where = !empty($name) ? "AND CAST(BillNo as CHAR) LIKE '".$name."%'": " " ;

 		$sql = "SELECT * from tblpos WHERE counterno = $counterno AND InvDate = '".$date."' AND isPending = 1 ".$where." limit " . $start . "," . $perpage;

 		$where1 = !empty($name) ? "AND CAST(BillNo as CHAR) LIKE '%".$name."%'": " " ;

 		$sql1 = "SELECT * from tblpos WHERE counterno = $counterno  AND InvDate = '".$date."' AND isPending = 1 ".$where1." limit " . $start . "," . $perpage;


 		$query = $this->db->query($sql);

 		if ($query && $query->num_rows) {

	        $item = $query->fetchAll();

	        echo json_encode(['success' => true, 'reason' => 'Details Found', 'details' => $item]);

	    }else{

            echo json_encode(['success' =>false,'reason' => 'Item Not Found', 'details' => []]);

	    }

 	}

    public function getUnsavedPos($counterno)
 	{
 		$sql = "SELECT * from tblposunsaved WHERE counterno = $counterno AND isPending = 1 ORDER BY posid DESC LIMIT 1";
 		$query = $this->db->query($sql);
 		if ($query && $query->num_rows) {

	        $item = $query->fetchObject();
	        $sql1 = "SELECT *,(SELECT id from products WHERE code = details.ItemCode) as id  from tblposunsaveddetails as details WHERE postblid = $item->posid AND is_deleted = 0";

            $query1 = $this->db->query($sql1);

            if ($query1 && $query1->num_rows) {

	          $details = $query1->fetchAll();

	            echo json_encode(['success' => true, 'reason' => 'Details Found','bill'=>$item , 'details' => $details]);

	        }else{

               echo json_encode(['success' =>false,'reason' => 'Item Not Found' ,'bill'=>$item , 'details' => []]);

	        }

	    }else{

            echo json_encode(['success' =>false,'reason' => 'Item Not Found', 'details' => []]);

	    }
 	}

 	public function getPosDetails($posid){

      $sql = "SELECT *,(SELECT id from products WHERE code = deatails.ItemCode) as id  from tblposdetails as deatails WHERE postblid = $posid ";

      $query = $this->db->query($sql);

      if ($query && $query->num_rows) {

	        $item = $query->fetchAll();	        

	        echo json_encode(['success' => true, 'reason' => 'Details Found', 'details' => $item]);

	    }else{

            echo json_encode(['success' =>false,'reason' => 'Item Not Found', 'details' => []]);

	    }

	    // $this->deletePosDetail($posid);
	    // $this->deletePos($posid);

	    exit();

 	}

 	public function getCounterDiscount($counter){

      $query = $this->db->query("SELECT discountperc from tblcountersettings where counterid = ?", [$counter]);

       if ($query && $query->num_rows) {

	        $item = $query->fetchArray();

	        echo json_encode(['success' => true, 'reason' => 'Details Found', 'details' => $item]);

	    }else{

        echo json_encode(['success' => false, 'reason' => 'Item Not Found', 'details' => []]);

	    }

 	}

 	public function getFreeItems($code){

       $query = $this->db->query("SELECT tblid as id, qty, freeitemcode ,freeqty,freeused FROM tblfreeitems WHERE itemCode = ? AND (totalfreeqty - freeused ) > 0", [$code]);
       if ($query && $query->num_rows) {

	        $item = $query->fetchAllArray();

	        echo json_encode(['success' => true, 'reason' => 'Details Found', 'details' => $item]);

	    }else{

        echo json_encode(['success' => false, 'reason' => 'Item Not Found', 'details' => []]);

	    }

 	}

 	public function addpos($data ,$flag){

 		if ($data['unsavedbill']) {
 		   $this->closeUnsavedpos($data['unsavedbill']);
 		}


 		$today = date("Y-m-d");
 		 
        if (!$data['posid']) {


	 		$sql = "INSERT into tblpos(  counterno , InvDate , BillNo ,Partyname,PartyAddress,PartyEmail,Total,discountperc,TotalDiscount,SGST,CGST,CESS,GrandTotal,RoundOff,BillAmount,PayType,paidamount,changeamount,noofbags,isPending,isdiscountapplied,CreatedBy) VALUES ( ".$data['counter']." , '".$today."' , ".$data['billno']." ,'".$data['partyname']."' , '".$data['phone']."' , '".$data['email']."' ,".$data['subTotal']." , ".$data['discperc']." , ".$data['disc']." , ".$data['sgst']." , ".$data['cgst']." , ".$data['cess']." , ".$data['grandTotal']." , ".$data['roundof']." , ".$data['netTotal']." , '".$data['paytype']."' , ".$data['cashreceived']." , ".$data['balance']." , ".$data['bags']." , ".$flag.",".$data['discountFlag'].",'".$data['operator']."' )";

	      

	       $insert_pos_query = $this->db->query($sql);

	      if ($this->posDetail($insert_pos_query,$data['bill_items'])){

	         echo json_encode(['success'=>'true']);

	      }

	      else{

	         echo json_encode(['success'=>'false']);

	      }
	    }
	    else{
	       $id = (int)$data['posid'];


           $sql = "UPDATE tblpos SET  Partyname = '".$data['partyname']."' ,PartyAddress = '".$data['phone']."' ,PartyEmail = '".$data['email']."' ,Total = ".$data['subTotal']." , discountperc =  ".$data['discperc'].", TotalDiscount = ".$data['disc']." ,SGST= ".$data['sgst'].",CGST= ".$data['cgst'].",CESS= ".$data['cess']." ,GrandTotal= ".$data['grandTotal']." ,RoundOff=  ".$data['roundof'].",BillAmount= ".$data['netTotal']." ,PayType='".$data['paytype']."',paidamount=".$data['cashreceived'].",changeamount= ".$data['balance'].",noofbags=".$data['bags']." ,isPending =  ".$flag.",isdiscountapplied = ".$data['discountFlag'].",CreatedBy = '".$data['operator']."' WHERE posid = $id";

           $update_pos_query = $this->db->query($sql);
          
	      if ($this->deletePosDetail($id)){

	         if ($this->posDetail($id,$data['bill_items'])){

	             echo json_encode(['success'=>'true']);

	         }

	      }
	    }

 	}

 	public function posDetail($posid,$item){

 		$base_query = "INSERT into tblposdetails(postblid , ItemCode , ItemName, MRP, Rate, Qty,freeqty, Unit, Amount, DiscAmt ) VALUES";

 		$values = '';

 		foreach ($item as $key => $value) {

 			$values .= "(".$posid.",'".$value['code']."','".$value['name']."',".$value['price'].",".$value['price'].",".$value['qty'].",NULL,'".$value['unit']."',".$value['total'].",".$value['disc_price']."),";
 			$update_products = "UPDATE products SET qty = qty - ".$value['qty']." WHERE code ='".$value['code']."'";
 			$this->db->query($update_products);
 		}

 		$sql = rtrim($base_query.$values,',');

 		return $this->db->query($sql);

 	}
 	public function deletePosDetail($posid){

 		$sql = 'DELETE FROM `tblposdetails` WHERE postblid = '.$posid;
 		// echo $sql;

 		if ($this->db->query($sql)) {
 			return true;
 		}
 		return false;


 	}
 	public function deletePos($posid){

 		$sql = 'DELETE FROM `tblpos` WHERE posid = '.$posid;
 		// echo $sql;

 		if ($this->db->query($sql)) {
 			return true;
 		}
 		return false;


 	}
 	public function UnsavedPos($data){

 		$today = date("Y-m-d");
 		$data['partyname'] = $data['partyname'] ? $data['partyname'] : NULL;
 		$data['phone'] = $data['phone'] ? $data['phone'] : NULL;
 		$data['email'] = $data['email'] ? $data['email'] : NULL;
        if (!$data['unsavedbill']) {

	 		$sql = "INSERT into tblposunsaved( postblid, counterno , InvDate , BillNo ,Partyname,PartyAddress,PartyEmail,Total,discountperc,TotalDiscount,SGST,CGST,CESS,GrandTotal,RoundOff,BillAmount,PayType,paidamount,changeamount,noofbags,isPending,isdiscountapplied,CreatedBy) VALUES (".$data['posid']." , ".$data['counter']." , '".$today."' , ".$data['billno']." ,'".$data['partyname']."' , '".$data['phone']."' , '".$data['email']."' ,".$data['subTotal']." , ".$data['discperc']." , ".$data['disc']." , ".$data['sgst']." , ".$data['cgst']." , ".$data['cess']." , ".$data['grandTotal']." , ".$data['roundof']." , ".$data['netTotal']." , '".$data['paytype']."' , ".$data['cashreceived']." , ".$data['balance']." , ".$data['bags']." , 1 ,".$data['discountFlag'].",'".$data['operator']."')";

	      

	       $insert_pos_query = $this->db->query($sql);

	      if ($this->UnsavedPosDetail($insert_pos_query,$data['bill_items'])){

	         echo json_encode(['success'=>'true','UnsavedPos'=>$insert_pos_query]);

	      }

	      else{

	         echo json_encode(['success'=>'false']);

	      }
	    }
	    else{
	       $id = (int)$data['unsavedbill'];

           $sql = "UPDATE tblposunsaved SET  Partyname = '".$data['partyname']."' ,PartyAddress = '".$data['phone']."' ,PartyEmail = '".$data['email']."' ,Total = ".$data['subTotal']." , discountperc =  ".$data['discperc'].", TotalDiscount = ".$data['disc']." ,SGST= ".$data['sgst'].",CGST= ".$data['cgst']." ,CESS= ".$data['cess'].",GrandTotal= ".$data['grandTotal']." ,RoundOff=  ".$data['roundof'].",BillAmount= ".$data['netTotal']." ,PayType='".$data['paytype']."',paidamount=".$data['cashreceived'].",changeamount= ".$data['balance'].",noofbags=".$data['bags']." ,isPending = 1 ,isdiscountapplied = ".$data['discountFlag']." ,CreatedBy = '".$data['operator']."' WHERE posid = $id";
           $update_pos_query = $this->db->query($sql);

          
	      if ($this->deleteUnsavedPosDetail($id)){

	         if ($this->UnsavedPosDetail($id,$data['bill_items'])){

	             echo json_encode(['success'=>'true']);

	         }

	      }
	    }

 	}

 	public function UnsavedPosDetail($posid,$item){
 		if (!$item) {
 			return true;
 		}
 		$base_query = "INSERT into tblposunsaveddetails(postblid , ItemCode , ItemName, MRP, Rate, Qty,freeqty, Unit, Amount, DiscAmt ) VALUES";

 		$values = '';

 		foreach ($item as $key => $value) {

 			$values .= "(".$posid.",'".$value['code']."','".$value['name']."',".$value['price'].",".$value['price'].",".$value['qty'].",NULL,'".$value['unit']."',".$value['total'].",".$value['disc_price']."),";

 		}

 		$sql = rtrim($base_query.$values,',');
 		return $this->db->query($sql);

 	}
 	public function deleteUnsavedPosDetail($posid){

 		$sql = 'DELETE FROM `tblposunsaveddetails` WHERE postblid = "'.$posid.'" AND is_deleted = 0';

 		if ($this->db->query($sql)) {
 			return true;
 		}
 		return false;


 	}
 	public function TrackDeletedItems($code,$rate,$bill)
 	{
 		$deletedItem = "UPDATE tblposunsaveddetails SET  is_deleted = 1 WHERE postblid =".$bill." AND ItemCode ='".$code."' AND Rate =".$rate ;
 		if ($this->db->query($deletedItem)) {
 			return true;
 		}
 		return false;

 	}
 	public function closeUnsavedpos($unsavedbill){

 	    $sql = 'UPDATE `tblposunsaved` SET isPending = 0 WHERE posid = '.$unsavedbill;
 	
 		$deletedItem = "DELETE FROM tblposunsaveddetails WHERE  is_deleted = 0 AND postblid =".$unsavedbill ;
        $this->db->query($sql);
        $this->db->query($deletedItem);
 	}

 	public function getBillNo($counter){

 		$today = date("Y-m-d");
        $query = $this->db->query("SELECT max(BillNo) as BillNo from tblpos where counterno = '$counter' AND InvDate = '".$today."'");

        if ($query && $query->num_rows) {

	        $item = $query->fetchArray();
	        $query1 = $this->db->query("SELECT  BillAmount from tblpos where counterno = '$counter' AND BillNo = '".$item['BillNo']."' AND InvDate = '".$today."'");

	        $item1 = $query1->fetchArray();
	        $item['BillAmount'] = isset($item1['BillAmount']) ? $item1['BillAmount'] : null;

	          echo json_encode(['success' => true, 'reason' => 'Details Found', 'details' => $item]);

	    }else{

              echo json_encode(['success' => false, 'reason' => 'Item Not Found', 'details' => []]);

	    }

 	}
 	// public function shiftClose($date){
 	// 	$query = $this->db->query("SELECT counterno,SUM(BillAmount) as full ,SUM(RoundOff) as adjusted, CreatedBy ,COUNT(BillNo) as bills,InvDate FROM tblpos WHERE InvDate = '".$date."' AND accountsettled = 0 AND isPending = 0 GROUP BY counterno,CreatedBy,InvDate");
 	// 	if ($query && $query->num_rows) {
	 //        $item = $query->fetchAllArray();
	 //        foreach ($item as $key => $value) {
  //                 $where = $value['CreatedBy'] ? "AND CreatedBy='".$value['CreatedBy']."'" : "";
	 //              $cash_query = $this->db->query("SELECT SUM(BillAmount) as cash FROM tblpos WHERE InvDate = '".$date."' AND accountsettled = 0 AND counterno ='".$value['counterno']."' ".$where." AND PayType='cash' AND isPending = 0");
	 //              $card_query = $this->db->query("SELECT SUM(BillAmount) as card FROM tblpos WHERE InvDate = '".$date."' AND accountsettled = 0 AND counterno ='".$value['counterno']."' ".$where." AND PayType !='cash' AND isPending = 0");

	 //              $cash = $cash_query->fetchArray();
	 //              $card = $card_query->fetchArray();
	 //              $item[$key]['cash'] = $cash;
	 //              $item[$key]['card'] = $card;
	 //        }
	 //        echo json_encode(['success'=>true,'details'=>$item]);
	 //    }
	 //    else{
	 //    	echo json_encode(['success'=>false,'details'=>[]]);
	 //    }
 	// }
 	public function shiftClose($date){
        $this->reCalculateShiftClose($date);
 		$query = $this->db->query("SELECT *  FROM tblshiftclosing WHERE DATE(trandate) = '".$date."'");
 		if ($query && $query->num_rows) {
	        $item = $query->fetchAllArray();
	        foreach ($item as $key => $value) {
	        	$item[$key]['totalbills'] = (int) $value['noofcashbills'] + (int) $value['noofcreditbills']; 
	        	$item[$key]['total'] = (int) $value['cardsales'] + (int) $value['cashsales'];
                $item[$key]['adjustments']= (object)[];
                $adj_query = $this->db->query("select * from tblshiftclosingadjustdetails WHERE shiftno='{$item[$key]['shiftno']}'");
                if ($adj_query && $adj_query->num_rows) {
                    $item[$key]['adjustments'] = $adj_query->fetchAllObject();
                }
	        }
	        echo json_encode(['success'=>true,'details'=>$item]);
	        exit;
	    }
	    else{
	    	echo json_encode(['success'=>false,'details'=>[]]);
	        exit;
	    }

 	}
 	public function closeBills($bills,$user){

 		foreach ($bills as $key => $value) {
            $remitted_amt = isset($value['remittedTotal']) ? $value['remittedTotal'] : 0;
            $adjusted = isset($value['adjusted']) ? $value['adjusted'] : 0;
            $remitted = $this->db->query("UPDATE tblshiftclosing SET remitted = '$remitted_amt', closedby = '$user',adjusted='$adjusted' WHERE shiftno =".$value['shiftno'] );
            if ($remitted){
                $sql = $this->db->query("DELETE FROM tblshiftclosingadjustdetails WHERE shiftno = {$value['shiftno']}");
                if (!$sql) {
                    echo json_encode(['success'=>false]);
                    exit;
                }

                if(isset($value['adjustments'])) {
                    foreach ($value['adjustments'] as $k=>$v){
                        $add_adjustmnt_det = $this->db->query("insert into tblshiftclosingadjustdetails(shiftno, Remarks, Amount)
		values ({$value['shiftno']},'{$v['Remarks']}',{$v['Amount']})");
                        if(!$add_adjustmnt_det) {
                            echo json_encode(['success'=>false]);
                            exit;
                        }
                    }
                } else {
                    $add_adjustmnt_det = $this->db->query("insert into tblshiftclosingadjustdetails(shiftno, Remarks, Amount)
		values ({$value['shiftno']},'Adjustment',{$value['adjusted']})");
                    if(!$add_adjustmnt_det) {
                        echo json_encode(['success'=>false]);
                        exit;
                    }
                }
            }
	    else{
	    	echo json_encode(['success'=>false]);
	        exit;
	    }

 		}
	      echo json_encode(['success'=>true]);
	        exit;
 	}
 	public function counterDetails(){
 			$counters = $this->counters;
 			echo(json_encode(['success'=>true,'counters'=>$counters]));
 	}
 	public function enablecounter($id){
 		// $counter = $this->db->query("SELECT keyenabled FROM tblcountersettings WHERE counterid =".$id);
 		// $counters = $counter->fetchArray();
 		// if ($counters['keyenabled'] == 1) {
 		// 	  echo json_encode(['success'=>False,'msg'=>'Someone Enabled this Counter Just Now...']);
 		// 	exit;
 		// }
 		// else{

 		// 	$okay = $this->db->query("UPDATE tblcountersettings SET keyenabled = 1 WHERE counterid =".$id);
 		// 	if ($okay) {
 			  echo json_encode(['success'=>TRUE,'msg'=>'Successfully Enabled']);
 			  exit;
 		// 	}
 		// 	  echo json_encode(['success'=>False,'msg'=>'Something Went Wrong...']);

 		// 	exit;
 		// }
 	}
 	public function getcounterno($key)
 	{
 		$counter = $this->counters[$key];
 		// if ($counter && $counter->num_rows) {

 		    // $counters = $counter->fetchArray();
 			echo json_encode(['success'=>true,'counter'=>$counter]);
 			exit;
 		// }
 		// else{
 		// 	echo json_encode(['success'=>false,'msg'=>'Something Went Wrong...']);
 		// 	exit;
 		// }
 	}
 	public function CloseCoutnterBill($counterno,$operator)
    {
        $trandate = $this->db->query("SELECT trandate  FROM tblshiftclosing WHERE operator = '".$operator."' AND counterno =".$counterno." ORDER BY shiftno DESC LIMIT 1");
        $wheredate = '';
        $wheredatereturn = '';
        if ($trandate && $trandate->num_rows) {
            $trandate = $trandate->fetchArray();
            $wheredate = "AND CreatedDateTime > '".$trandate['trandate']."'";
            $wheredatereturn = "AND returntime > '".$trandate['trandate']."'";
        }

        $query = $this->db->query("SELECT counterno,SUM(BillAmount) as full ,SUM(RoundOff) as adjusted, CreatedBy ,COUNT(BillNo) as bills,InvDate,MAX(BillNo) as endbill , MIN(BillNo) as startbill , MAX(CreatedDateTime) as endtime , MIN(CreatedDateTime) as starttime FROM tblpos WHERE counterno = ".$counterno." AND CreatedBy = '".$operator."' ".$wheredate." AND accountsettled = 0 AND isPending = 0 GROUP BY counterno,CreatedBy");
        if ($query && $query->num_rows) {
            $item = $query->fetchAllArray();
            foreach ($item as $key => $value) {
                $where = $value['CreatedBy'] ? "AND CreatedBy='".$value['CreatedBy']."'" : "";
                $cash_query = $this->db->query("SELECT SUM(BillAmount) as cash , COUNT(BillNo) as countcash FROM tblpos WHERE accountsettled = 0 AND counterno ='".$value['counterno']."' ".$where." AND PayType='cash' AND isPending = 0 ".$wheredate);

                $card_query = $this->db->query("SELECT SUM(BillAmount) as card , COUNT(BillNo) as countcard FROM tblpos WHERE accountsettled = 0 AND counterno ='".$value['counterno']."' ".$where." AND PayType !='cash' AND isPending = 0 ".$wheredate);

                $cash_return = $this->db->query("SELECT SUM(amountrepaid) as cashrepaid  FROM tblposreturn WHERE accountsettled = 0 AND settlecounterno ='".$value['counterno']."'".$wheredatereturn);


                $cash = $cash_query->fetchArray();
                $card = $card_query->fetchArray();
                $cashreturn = $cash_return->fetchArray();

                $item[$key]['cash'] = (int) $cash['cash'] - (int) $cashreturn['cashrepaid'];
                $item[$key]['cashrepaid'] = (int) $cashreturn['cashrepaid'];
                $item[$key]['countcash'] = $cash['countcash'];
                $item[$key]['card'] = $card['card'];
                $item[$key]['countcard'] = $card['countcard'];
            }
            echo json_encode(['success'=>true,'details'=>$item]);
        }
        else{
            echo json_encode(['success'=>false,'details'=>[]]);
        }
    }
 	public function addShiftClose($data,$branchid){
 		$starttime = date("H:i:s",strtotime($data['starttime']));
 		$endtime = date("H:i:s",strtotime($data['endtime']));
 		$cardsales = $data['card'] != '' ? $data['card'] : 0 ;
 		$cashsales = $data['cash'] != '' ? $data['cash'] : 0 ;
 		$add = $this->db->query("INSERT INTO `tblshiftclosing`(`Branchid`, `counterno`, `operator`, `noofcashbills`, `noofcreditbills`, `cashsales`, `cardsales`, `adjusted`,`starttime`,`endtime`,`startbillno`,`endbillno`) VALUES ( ".$branchid." , ".$data['counterno']." , '".$data['CreatedBy']."' , ".$data['countcash']." , ".$data['countcard']." , ".$cashsales." , ".$cardsales." , ".$data['adjusted'].", '".$starttime."', '".$endtime."', ".$data['startbill'].", ".$data['endbill']." )");
 		if($add) {
 			echo json_encode(['success'=>true]);
 		}
 		else {
 			echo json_encode(['success'=>false]);
 		}
 	}

     public function reCalculateShiftClose($date){
         $query = $this->db->query("SELECT *  FROM tblshiftclosing WHERE DATE(trandate) = '".$date."'");
         if ($query && $query->num_rows) {
             $bills = $query->fetchAllArray();
             foreach ($bills as $key => $value) {

                 $cash_query = $this->db->query("SELECT SUM(BillAmount) as cash , COUNT(BillNo) as countcash FROM tblpos WHERE counterno ='" . $value['counterno'] . "'  AND PayType='cash' AND BillNo BETWEEN '" . $value['startbillno'] . "' AND '" . $value['endbillno'] . "' AND InvDate = date('" . $value['trandate'] . "')");

                 $card_query = $this->db->query("SELECT SUM(BillAmount) as card , COUNT(BillNo) as countcard FROM tblpos WHERE counterno ='" . $value['counterno'] . "'  AND PayType !='cash' AND BillNo BETWEEN '" . $value['startbillno'] . "' AND '" . $value['endbillno'] . "' AND InvDate = date('" . $value['trandate'] . "')");


                 $cash = $cash_query->fetchArray();
                 $card = $card_query->fetchArray();
                 $cashTotal = $cash['cash'];
                 $countcash = $cash['countcash'];
                 $cardTotal = $card['card'];
                 $countcard = $card['countcard'];

                 $amount = 0;

                 $adj_query = $this->db->query("select sum(Amount) AS Amount from tblshiftclosingadjustdetails WHERE shiftno =" . $value['shiftno']);
                 if ($adj_query && $adj_query->num_rows) {
                     $amount = $adj_query->fetchObject()->Amount;
                 }

                 $update = $this->db->query("UPDATE tblshiftclosing SET noofcashbills = '$countcash',noofcreditbills = '$countcard',cashsales = '$cashTotal',cardsales = '$cardTotal',adjusted = '$amount' WHERE shiftno =" . $value['shiftno']);
                 if (!$update) {
                     echo json_encode(['success' => false]);
                     exit;
                 }
             }
         }

     }
     public function loadReturnBill($counter,$bill,$date){
         
        $retsql = "SELECT * from tblposreturn WHERE orgCounterno = '$counter' AND orgBillno = '$bill' AND orgInvdate = '".$date."'"; 
        $retbill = $this->db->query($retsql);
        
        if ($retbill->num_rows > 0) { 
            echo json_encode(['success'=>false,'msg'=>'Bill already return']);
            exit;
        } 
         
        $sql = "SELECT * from tblpos WHERE counterno = $counter AND BillNo = $bill AND InvDate = '".$date."'";
        // echo $sql;die();
        $bills = [];
        $bill = $this->db->query($sql);

        if ($bill && $bill->num_rows) {

            $bills = $bill->fetchArray();

            $sql1 = "SELECT * FROM tblposdetails WHERE postblid = ".$bills['posid'];

            $item = $this->db->query($sql1);

            if ($item && $item->num_rows) {

              $items = $item->fetchAllArray();

              $bills['items'] = $items;

            }
            echo(json_encode(['success'=>true,'counters'=>$bills]));

            exit;
        }
        else{
            echo json_encode(['success'=>false,'msg'=>'Please Enter a Valid Detail']);
            exit;
        }
    }
     public function processReturn($counter, $returndata, $repayAmount, $operator, $settlecounterno) {
        $today = date("Y-m-d"); 
        $data = $returndata[0];  
         
        $settlesql = $this->db->query("SELECT CreatedBy FROM tblpos WHERE counterno = '".$data['counterno']."' AND InvDate = '".$data['InvDate']."'");
        $settle = $settlesql->fetchArray();
        $settlementby = $settle["CreatedBy"];
        
        $sql = "INSERT into tblposreturn( BranchId , settlecounterno , InvDate , BillNo , PartyCode ,Partyname, PartyAddress, PartyEmail,amountrepaid, Total, discountperc, TotalDiscount, isdiscountapplied, SGST, CGST, IGST, GrandTotal, RoundOff, BillAmount, PayType, paidamount, changeamount, noofbags, accountsettled, isPending, shiftno, CreatedBy, CreatedDateTime, AmendedBy, AmendedDateTime, orgPosid, orgCounterno, orgBillno, orgInvdate, settlementby) VALUES ( '".$data['BranchId']."' , '".$settlecounterno."' , '".$data['InvDate']."' , '".$data['BillNo']."' ,'".$data['PartyCode']."','".$data['Partyname']."','".$data['PartyAddress']."','".$data['PartyEmail']."' ,'".$repayAmount."','".$data['Total']."', '".$data['discountperc']."', '".$data["TotalDiscount"]."', '".$data["isdiscountapplied"]."', '".$data["SGST"]."', '".$data["CGST"]."', '".$data["IGST"]."', '".$data['GrandTotal']."' , '".$data['RoundOff']."' , '".$data['BillAmount']."' , '".$data['PayType']."' , '' , '' , '".$data['noofbags']."',0, '".$data['isPending']."', '".$data["shiftno"]."', '".$data["CreatedBy"]."', '".$data["CreatedDateTime"]."', '".$data["AmendedBy"]."', '".$data["AmendedDateTime"]."' , '".$data["posid"]."', '".$data["counterno"]."', '".$data["BillNo"]."','".$data['InvDate']."', '".$settlementby."')";
        
        $add = $this->db->query($sql);
        
        $postblid = $this->db->insert_id();
        
        $base_query = "INSERT into tblposreturndetails(postblid , ItemCode , ItemName, MRP, Rate, Qty,freeqty, Unit, Amount, DiscAmt , taxableamt, taxrate, sgsttax, cgsttax, igsttax) VALUES";

        $values = '';
        
        $addet = "";
        $posid = $data["posid"]; 
        
        if($posid != "0") { 
            
            $item = $data["items"];
            
            foreach ($item as $key => $value) {
                 
                if($value["returnAmount"] > 0) { 
               
                    $values .= "(".$postblid.", '".$value['ItemCode']."', '".$value['ItemName']."', '".$value['MRP']."', '".$value['Rate']."', '".$value['returnQty']."', '','".$value['Unit']."', '".$value['returnAmount']."', '".$value['DiscAmt']."', '".$value['taxableamt']."', '".$value['taxrate']."', '".$value['sgsttax']."', '".$value['cgsttax']."', '".$value['igsttax']."'),";
                }
            } 
            $sqldet = rtrim($base_query.$values,','); 
            
            $addet = $this->db->query($sqldet);
        }
        
        if($addet) { 
        	echo json_encode(['success'=>true]);
        }
        else {
        	echo json_encode(['success'=>false]);
        }
    } 

	public function login($usercode, $pass, $user_branch) {
		//$sql = "select usr.UserCode,usr.acccode,usr.DefaultScreen,mas.Picture as image,mas.AccName as name from tbluseremployee usr join tblaccmas mas on mas.AccCode = usr.acccode where usr.UserCode = '$usercode' and usr.Password = '$pass' and  FIND_IN_SET('$user_branch', mas.AvailableBranches) and Status = 1";
		$sql = "select usr.UserCode,usr.acccode,usr.DefaultScreen, usr.imagefile as image, usr.UserCode as name from tbluseremployee usr where usr.UserCode = '$usercode' and usr.Password = '$pass'";
		$result = $this->db->query($sql);
		if ($result && $result->num_rows) {
			$result = $result->fetchArray();

			$_SESSION['UserCode'] = $result['UserCode'];
			$_SESSION['UserId'] = $result['acccode'];
			$_SESSION['UserImage'] = $result['image'];
			$_SESSION['UserName'] = $result['name'];
			$_SESSION['Branch'] = $user_branch;

			$_SESSION['database'] = ['db_name' => $config['database'], 'db_user' => $config['username'], 'db_password' => $config['password'], 'branchname' => ''];

			/*$sql_db = "select * from tblclientdetails where branchcd='$user_branch'";
			$result = $this->db->query($sql_db);
			if ($result && $result->num_rows) {
				$result_db = $result->fetchObject();
                $_SESSION['database'] = ['db_name' => $result_db->branchdbname, 'db_user' => $result_db->branchusrname, 'db_password' =>$result_db->branchpassword, 'branchname' =>$result_db->branchname];
				echo json_encode(['success'=>true, 'login'=> ['branch' => $_SESSION['Branch'], 'name' => $_SESSION['UserName'], 'code' => $_SESSION['UserCode']]]);
				exit;
			}*/

			echo json_encode(['success'=>true, 'login'=> ['branch' => $_SESSION['Branch'], 'name' => $_SESSION['UserName'], 'code' => $_SESSION['UserCode']]]);
			exit;
		}

		echo json_encode(['success'=> false, 'message' => 'Unable to login. Please check your credentials.']);
		exit;
	}

	public function getBranch($user) {
		global $settings;

		$branch_details = [];
		$sql = "select BranchId,BranchName from tblbranch";
		
		$sql .= " where BranchId = '{$settings->BranchId}'";

		$branch = $this->db->query($sql);
		if ($branch && $branch->num_rows) {
			while($row = $branch->fetchObject()) {
				$branch_details[] = ['branch_id'=> $row->BranchId, 'branch_name'=> $row->BranchName];
			}

			echo(json_encode(['success'=> true, 'branch'=> $branch_details]));
			return;
		}
		echo json_encode(['success' => false, 'message' => 'No Branch Found', 'branch' => []]);
		return;
	}

	public function getCompany() {
		$sql ="SELECT BranchName as name, Address as address, City as city, State as state, Country as country, PinCode as pincode, MobileNumber as mobile, Telephone as phone, GstRegisNo as gst_reg_no, (SELECT name FROM `countries` WHERE id = Country) as country, (SELECT name FROM `states` WHERE id = State) as state  FROM tblbranch WHERE BranchId = '{$_SESSION['Branch']}'";
		$company = $this->db->query($sql);
		if ($company && $company->num_rows) {
			 $company = $company->fetchArray();
			 echo(json_encode(['success'=>true, 'company'=>$company]));
			 return;
		}
	    echo(json_encode(['success'=> false, 'message'=> 'Could not get company details']));
		return;
	}

	public function logOut() {
		session_destroy();
		echo json_encode(['success' => true]);
	}
 }
