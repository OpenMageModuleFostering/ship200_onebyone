<?php



class Ship200_Onebyone_IndexController extends Mage_Core_Controller_Front_Action

{

	

	public function postbackAction(){

		

		$secret_key = Mage::getStoreConfig('onebyone/info/appkey');
		 $order_status_tracking = Mage::getStoreConfig('onebyone/info/order_status_tracking');
		  // $notify_customer_setting = Mage::getStoreConfig('onebyone/info/notify_customer');
		   $notify_customer_setting = 1;
		  
		   
		   
			if($notify_customer_setting == "1"){
					 $notifycustomer="true";
			}else{
				 $notifycustomer="false";
			}
		
		if($secret_key == ""){ echo "The Secret Key was never setup. Please refer to read_me file"; exit;}
		
		if($order_status_tracking == ""){ echo "Please Select The Order Status From Admin For Update With Tracking"; exit;}
		
	#Extra security
	// Check that request is coming from Ship200 Server
	$allowed_servers = file_get_contents('http://www.ship200.com/instructions/allowed_servers.txt');
	$servers_array = explode(",",$allowed_servers);

	$server = 0;
	foreach($servers_array as $ip){
		if($_SERVER['REMOTE_ADDR'] == $ip){$server = 1;}
	}
	if($server == 0){ echo "Incorrect Server"; exit;}
	// Check that request is coming from Ship200 Server		
		

		if($_POST['update_tracking'] != "" && $_GET['id'] == $secret_key){
					$order=Mage::getModel("sales/order")->loadByIncrementId($_POST['keyForUpdate']);
					
					
					
					$order->setData('state', $order_status_tracking);
					$order->setStatus($order_status_tracking);
					$comment = addslashes($_POST['carrier'])." tracking#: ".addslashes($_POST['tracking']);
					$history = $order->addStatusHistoryComment($comment, false);
					$history->setIsCustomerNotified($notifycustomer);
					$order->save();

					echo "Tracking Number: Inserted";

		}else{
			echo "Failed to Update Tracking";
		}
	}

	

}



?>