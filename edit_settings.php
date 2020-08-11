<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no" />
    <title>Edit FTP Settings</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
</head>
<body>
    <?php 
        require('include/function.php');
        $ftp_setting = get_ftp_setting();
    ?>
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6 ">
                <div>
                    <div class="header">
                        <h2>FTP Settings</h2>
                        <small>You can upload files directly from your server to another FTP server and load them from there.</small><br>
                        <small>Impotant: This may slow down your site's upload/delete speed, make sure to use fast FTP server.</small>
                    </div>
                    <div class="body">
                        <div class="alert alert-success ftp-settings-alert" style="display:none"></div>
                        <form class="ftp-settings" method="POST">
                            <label for="ftp_upload">FTP Storage</label>
                            <div class="form-group">
                                <input type="radio" name="ftp_upload" id="ftp_upload-enabled" value="1" <?=($ftp_setting['ftp_upload']==1)?'checked':''?>>
                                <label for="ftp_upload-enabled">Enabled</label>
                                <input type="radio" name="ftp_upload" id="ftp_upload-disabled" value="0" <?=($ftp_setting['ftp_upload']==0)?'checked':''?>>
                                <label for="ftp_upload-disabled" class="m-l-20">Disabled</label>
                            </div>
                            <div class="form-group">
                                <div><label>FTP Hostname</label>
                                    <input type="text" id="ftp_host" name="ftp_host" class="form-control" value="<?=$ftp_setting['ftp_host']?>">
                                    
                                </div>
                            </div>
                            <div class="form-group">
                                <div><label>FTP Username</label>
                                    <input type="text" id="ftp_username" name="ftp_username" class="form-control" value="<?=$ftp_setting['ftp_username']?>">
                                    
                                </div>
                            </div>
                            <div class="form-group">
                                <div> <label>FTP Password</label>
                                    <input type="password" id="ftp_password" name="ftp_password" class="form-control" value="<?=$ftp_setting['ftp_password']?>">
                                   
                                </div>
                            </div>
                            <div class="form-group">
                                <div> <label>FTP Port</label>
                                    <input type="text" id="ftp_port" name="ftp_port" class="form-control" value="<?=$ftp_setting['ftp_port']?>">
                                   
                                </div>
                            </div>
                            <div class="form-group">
                                <div><label>FTP Path</label>
                                    <input type="text" id="ftp_path" name="ftp_path" class="form-control" value="<?=$ftp_setting['ftp_path']?>">
                                    
                                </div>
                            </div>
                            <div class="form-group">
                                <div><label>FTP Endpoint (IP or domain where the FTP server is pointed to) example: cdn.domain.com.</label>
                                    <input type="text" id="ftp_endpoint" name="ftp_endpoint" class="form-control" value="<?=$ftp_setting['ftp_endpoint']?>">
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <span class="help-block">Make sure to click on "Test Connection".</span><br>
                            <span class="help-block small">You must save before "Test Connection".</span><br><br>
                            <button type="submit" class="btn btn-primary">Save</button>
                            <button type="button" class="btn btn-warning" onclick="TestFTP()">Test FTP Connection</button>
                            <a href='upload.php' class="btn btn-info">Upload</a>
                        </form>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div> 
    </div>

    <script>
    	function TestFTP() {
    		$('form.ftp-settings').find('.btn-warning').text('Please wait..');
    		$.get('include/ajax.php', {f: 'test_ftp'}, function (response) {
    			if (response.status == 200) {
    				alert('Connection established!');
    			} else if (response.status == 400) {
    				alert(response.data.message);
    			}
    			$('form.ftp-settings').find('.btn-warning').text('Test FTP Connection');
    		});
    	}
    	
    	$(document).ready(function(){
            var form_ftp_settings = $('form.ftp-settings');

        	form_ftp_settings.submit(function(e) {
                e.preventDefault(); // avoid to execute the actual submit of the form.
                form_ftp_settings.find('.btn-primary').text('Please wait..');

                $.ajax({
                    url: 'include/ajax.php?f=save',
                    type:'POST',
                    data: form_ftp_settings.serialize(), 
                    success: function(data)
                    {
                        if (data.status == 200) {
                            form_ftp_settings.find('.btn-primary').text('Save');
                            $('.ftp-settings-alert').html('Settings updated successfully').fadeIn();
                            setTimeout(function () {
                                $('.ftp-settings-alert').empty().hide('slow');
                            }, 2000);
                        }
                    }
                });
        	});
        });
    	
    </script>
</body>
</html>
