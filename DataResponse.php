<?php

/*

File For PHP Req/Res

*/

require_once __DIR__ .'/config.php';

  $req = $_GET['req'];

  $db = mysql::getInstance($config);
  $app = new ClassApp($db);

 switch ($req) {

 	case 'company':
		return $app->getCompany();

 	case 'product':

 	    $code = $_GET['code'];

 		return $app->getProductByCode($code);

 	break;

 	case 'productList':

 		  $num = $_GET['pagenum'];

 		  $name = $_GET['name'];

 		  return $app->getProductList($num,$name);

 	break;
  case 'pendingList':

      $num  = $_GET['pagenum'];

      $name = $_GET['name'];

      $date = $_GET['date'];

      $date = date_format(date_create($date) , 'Y-m-d' );

      $counterno = $_GET['counterno'];

      return $app->getPendingList($num,$name,$date,$counterno);

  break;
  case 'posDetail':

      $posid  = $_GET['posid'];

      return $app->getPosDetails($posid);

  break;
  case 'discount':

     	  $counter = $_GET['counter'];

        return $app->getCounterDiscount($counter);

  break;

  case 'freeItem':

        $code = $_GET['code'];

        return $app->getFreeItems($code);

  break; 

  case 'saveBill':

        $data = $_POST['data'];

        $flag = $_POST['flagg'] ? 1 : 0;

        return $app->addpos($data,$flag);

  break;  

  case 'TrackUnsavedBill':
    $data = $_POST['data'];
    return $app->UnsavedPos($data);

  break;
  case 'TrackDeletedItems':
  $code = $_POST['code'];
  $rate = $_POST['rate'];
  $bill = $_POST['bill'];
    return $app->TrackDeletedItems($code,$rate,$bill);

  break;
  case 'Unsavedbill':
  
    $counterno = $_GET['counterno'];

    return $app->getUnsavedPos($counterno);

  break;

  case 'billNo':

        $counter = $_GET['counter'];

        return $app->getBillNo($counter);

  break;    
  case 'posclose':
      $date = $_GET['date'];
      return $app->shiftClose(date_format(date_create($date) , 'Y-m-d' ));
  break;
  case 'counterDetails':
      return $app->counterDetails();
  break;
  case 'enablecounter':
      $id = $_POST['id'];
      return $app->enablecounter($id);
  break;
  case 'getcounterno':
      $key = $_POST['key'];
      return $app->getcounterno($key);
  break;  
  case 'closeBills':
      if(!isset($_POST['bills'])){
          echo json_encode(['success' => false]);
          exit;
      }
      $bills = $_POST['bills'];
      $user = $_POST['user'];
      return $app->closeBills($bills,$user);
  break;

  case 'counterShiftClose':
      $counterno = $_POST['counterno'];
      $operator = $_POST['operator'];
     return $app->CloseCoutnterBill($counterno,$operator);
    
  break;

  case 'addShiftClose':
      $data = $_POST['data'];
      $branch = $_POST['branch'];
     return $app->addShiftClose($data,$branch);
    
  break;
  case 'loadReturnBill':
    $counter = $_POST['counter'];
    $bill = $_POST['billno'];
    $date = $_POST['date'];
    $date = str_replace(",","",$date);
    $date = date_format(date_create($date) , 'Y-m-d' );
    
    return $app->loadReturnBill($counter,$bill,$date);
  break;
case 'processReturn':
    $returndata = $_POST['returndata'];
    $repayAmount = $_POST['repayAmount'];
    $operator = $_POST['operator'];
    $counter = $_POST['counter']; 
    $settlecounterno = $_POST['settlecounterno']; 
     
    return $app->processReturn($counter, $returndata, $repayAmount, $operator, $settlecounterno);
  break; 

 	default:

 	

 	break;

 }



