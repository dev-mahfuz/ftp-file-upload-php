<?php
set_time_limit(0);
error_reporting( error_reporting() & ~E_NOTICE );

function get_ftp_setting(){
	global $FTP_SETTINGS;
	if(isset($FTP_SETTINGS)){
		return $FTP_SETTINGS;
	}
	
    $value = @file_get_contents(__DIR__.'/ftp.config');
    if ($value != false && $value != '' && is_string($value)) {
        $value = json_decode($value, true);
		if(!isset($value['ftp_upload'])){
			$value['ftp_upload'] = 0;
		}
        $FTP_SETTINGS = $value;
        return $value;
    }
    return ['ftp_upload' => 0];
}

function save_ftp_setting($data){
    return @file_put_contents(__DIR__.'/ftp.config', json_encode($data));
}

function upload_to_ftp($filename, $config = array()){
	$ftp_setting = get_ftp_setting();
	
    if ($ftp_setting['ftp_upload'] == 0) {
        return false;
    }
	if($filename!=''){
		
		include_once 'autoload.php';
		
		$ftp = new \FtpClient\FtpClient();
        $ftp->connect($ftp_setting['ftp_host'], false, $ftp_setting['ftp_port']);
        $login = $ftp->login($ftp_setting['ftp_username'], $ftp_setting['ftp_password']);

        if ($login) {
            if (!empty($ftp_setting['ftp_path'])) {
                if ($ftp_setting['ftp_path'] != "./") {
                    $ftp->chdir($ftp_setting['ftp_path']);
                }
            }
            $file_path = substr($filename, 0, strrpos( $filename, '/'));
            $file_path_info = explode('/', $file_path);
            $path = '';
            if (!$ftp->isDir($file_path)) {
                foreach ($file_path_info as $key => $value) {
                    if (!empty($path)) {
                        $path .= '/' . $value . '/' ;
                    } else {
                        $path .= $value . '/' ;
                    }
                    if (!$ftp->isDir($path)) {
                        $mkdir = $ftp->mkdir($path);
                    }
                } 
            }
            $ftp->chdir($file_path);
            $ftp->pasv(true);
            if ($ftp->putFromPath($filename)) {
                if (empty($config['delete'])) {
                    @unlink($filename);
                } 
                $ftp->close();
                return true;
            }
            $ftp->close();
        }
	}
    return false;
}


function delete_file_from_ftp($filename){
    $ftp_setting = get_ftp_setting();
    
    if ($ftp_setting['ftp_upload'] == 0) {
        return false;
    }
    if($filename!=''){
        include_once 'autoload.php';
        
        $ftp = new \FtpClient\FtpClient();
        $ftp->connect($ftp_setting['ftp_host'], false, $ftp_setting['ftp_port']);
        $login = $ftp->login($ftp_setting['ftp_username'], $ftp_setting['ftp_password']);

        if ($login) {
            if (!empty($ftp_setting['ftp_path'])) {
                if ($ftp_setting['ftp_path'] != "./") {
                    $ftp->chdir($ftp_setting['ftp_path']);
                }
            }
            $ftp->pasv(true);
            $ftp->delete($filename);
            @unlink($filename);
            $ftp->close();
        }
    }
    return false;
}
