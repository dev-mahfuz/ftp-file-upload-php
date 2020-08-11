<?php
    require('include/function.php');
    if(isset($_FILES['file']['name'])){

        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["file"]["name"]);

        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            $upload_status = "The file <b>". basename( $_FILES["file"]["name"]). "</b> has been uploaded to local directory.";
        try {

            $upload = upload_to_ftp($target_file);
            $upload_status .= '<br>The file has been uploaded to ftp.';
            $upload_status .= '<br>The file has been deleted from local directory.';

        } catch (Exception $e) {
            $upload_status .= '<br>'.$e->getMessage();
        }
        } else {
            $upload_status = "Sorry, there was an error uploading your file.";
        }

    }
    $ftp_setting = get_ftp_setting();
?>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no" />
    <title>Upload file to FTP</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <div>
                    <div>
                        <h2>Upload file to FTP</h2>
                        <small>You can upload files directly from your server to another FTP server and load them from there.</small><br>
                    </div>
                    <div>
                        <br><br>
                        <?php if($ftp_setting['ftp_upload']!=1){ ?>
                            <div class="alert alert-danger">You must enable ftp storage before upload file.</div><a href='edit_settings.php' class="btn btn-info">Edit Setting</a>
                        <?php }else{  if(isset($upload_status)){ ?>
                            <div class="alert alert-info"><?=$upload_status?></div>
                        <?php } ?>
                        <form class="upload-file" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <input type="file" id="file" name="file" class="form-control">
                            </div>
                            <div class="clearfix"></div>
                            <button type="submit" class="btn btn-primary">Upload</button>
                            <a href='edit_settings.php' class="btn btn-info">Edit Setting</a>
                        </form>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div> 
    </div>
</body>
</html>