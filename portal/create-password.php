<?php 
    $ver_code  =  '';
    if(isset($_GET['ver']) && $_GET['ver']!=''){
        $ver_code  =  $_GET['ver'];
    }
    else{
        header('Location: index.php');	
        exit();
    }

?>

<html>
    <head>
        <title>Create New Password</title>
        <link href="css/developer.css" rel="stylesheet"> 
        <style>
            .error-msg{
                font-size: 16px;
            }
            .form-group{
                padding-left: 94px;
                text-align: left;
                margin-bottom: 10px;
            }
            
            .form-group label{
                display: block; 
                font-weight: 600; 
                text-align: left;
            }
            
            .form-group input{
                display: block;
                padding: 10px 12px;
                width: 230px;
            }
            
            .submit{
                 background-color: #26a65b;
                border: medium none;
                border-radius: 3px;
                color: #fff;
                font-size: 14px;
                font-weight: 600;
                margin-top: 20px;
                padding: 10px 20px;
                text-decoration: none;
                width: 230px;
                cursor: pointer;
            }
            .msg-success{
                display: none;
            }
        </style>
    </head>
        <body>
                                <div style="text-align: center">                                    
                                    <div class="msg-success" style="margin: 0 auto;;width: 450px">
                                        <h1 style="font-size: 25px;text-align: center;">You have created new password successfully</h1>
                                        <a href="index.php" class="submit">LOGIN</a>
                                    </div>
                                    
                                    <div class="form-container" style="margin: 0 auto;;width: 450px;">
                                        <div class="form-group">
                                            <h1 style="font-size: 25px;text-align: left;">Create New Password</h1>
                                        </div>
                                        <form id="passwordForm" action="" method="" id="passwordForm">
                                            <input type="hidden" name="verification_code" id="verification_code" value="<?php echo $ver_code;?>"/>
                                            <div class="form-group">
                                                <span class="error-msg"></span>
                                                <label>New Password</label>
                                                <input type="password" name="password" id="password" value="" autocomplete="off"/>
                                            </div>
                                            <div class="form-group">
                                                <label>Confirm Password</label>
                                                <input type="password" name="confirm_password" id="confirm_password" autocomplete="off"/>
                                            </div>
                                            <div class="form-group">
                                                <button name="submit" class="submit" id="submit" type="button">SUBMIT</button>
                                            </div>
                                        </form>
                                    </div>
                                                                             
                                </div>
        </body>
<!--scripts-->
<script src="js/jquery-1.11.0.min.js"></script> 
<script src="js/jquery.serialize-object.min.js"></script>
<script src="js/url_path.js"></script> 
<script src="js/custom.js"></script>        
</html>