<?php

function response($status = 404, $data = false){
    header("Content-type: application/json");
	echo json_encode(array('status' => $status, 'data' => $data));
	exit;
}

if(!isset($_REQUEST['f'])){
	response();
}

require('function.php');

switch($_REQUEST['f']){
	case 'test_ftp':
        try {
			$upload = upload_to_ftp('testconnection_giphy.png', array(
						'delete' => 'no'
					));
            $status = 200;
			$message = 'success';
        } catch (Exception $e) {
            $status = 400;
            $message = $e->getMessage();
        }
		response($status,array('message' => $message));
		break;
	case 'save':
		$status = 400;
		if(save_ftp_setting($_POST)){
			$status = 200;
		}
		response($status);
		break;
	default:
		response();
		break;
}