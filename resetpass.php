<?php 
//print('i m 1');exit();
require_once 'src/config/config.php';
require_once 'src/config/connect.php';
require_once 'src/utils/GeneralMethod.php';
require_once 'src/utils/ConnectionManager.php';


if(isset($_GET['enc']) && $_GET['enc']!=''){
    
    $enc  =  $_GET['enc'];
    
    echo '<html>
    <head>
        <title>Reset Password</title>
        <style>
        .container{
            text-align: center;
        }
            label{
                display: block;
                font-size: 16px;
                padding: 5px 0;
                font-weight: 600;
            }
            
            .formcontrol{
                    margin-bottom: 15px;
                    height: 30px;
                    color: #7e7e7e;
                    font-weight: 300;
display: block;
   
margin: 10px auto;
            }
            
            .butn{ cursor: pointer;
                    border-radius: 0;
                    box-shadow: none;
                    color: #fff;
                    background-color: #6dc418;
                    width: 175px;
                    text-align: center;
                    font-weight: bold;
                    font-size: 14px;
                    color: #fff;
                    line-height: 16px;
                  /**  display: block;*/
                    padding: 10px 20px;
                    border: none;
                    
            }
            .resth2{
                
            }
            .error{color:red;}
/*                padding: 6px;
    border: 1px solid #0ff000;*/
        </style>
    </head>
    <body>
        <div class="container">
            <form action="resetpass.php" method="post">
                <h2 class=""><u>Reset Password</u></h2>
                <span class="error" id="error"></span>
                <label>New Password</label>
                <input class="formcontrol" type="password" name="password" id="password" required/>
                <label>Confirm New Password</label>
                <input class="formcontrol" type="password" name="confrim_password" id="confrim_password" required/>                
                <input type="hidden" name="encstring" value="'.$enc.'"/>                
                <button type="submit" name="submit" class="butn" onclick="return Validate()">Submit</button>
            </form>
            
        </div>
        
        <script type="text/javascript">
            function Validate() {
                document.getElementById("error").innerHTML ="";
                var password = document.getElementById("password").value;
                var confirmPassword = document.getElementById("confrim_password").value;
                if (password != confirmPassword) {
                    document.getElementById("error").innerHTML ="Password and Confirm password does not match";
                    return false;
                }
                return true;
            }
</script>
    </body>
</html>';
}


elseif(isset($_POST['submit'])){
//        print_r($_POST);
        //exit();
    
//new
    $encString  =  $_POST['encstring'];
    $password   = md5($_POST['password']);
 
    $db             = new ConnectionManager();
    
    $sQuery = " SELECT user_id,user_email,added_on,updated_on "
            . " FROM users"                    
            . " WHERE encrypted_code=:encrypted_code AND is_deleted =0";       

    $db->query($sQuery);
    $db->bind(':encrypted_code',$encString);
    $rowRes = $db->single();
     
    if(empty($rowRes)){
        echo ' <html><body class="login-sn">
                            <div class="login-detail" style="text-align: left">
                                   
                                <h1 style="color:#f00">User not exists</h1>
                                </div>    
                        </body></html>';
        exit();        
    }
    else{
        $userId  =  $rowRes['user_id'];
        
        $verify =  '';
			//update password
                        $sQuery3 = " UPDATE users "
                               . " SET user_password=:user_password,updated_on=now()"
                               . " WHERE user_id=:user_id AND is_deleted =0 ";
                        $db->query($sQuery3);                    
                        $db->bind(':user_id',$userId);
                        $db->bind(':user_password',$password);
                        
                        if($db->execute()){                   
                            $verify =  'Password reset successfull!';
                        } 
                        else{
                            $verify =  'Password reset is not successfull!';  
                        }
                        
        echo '<!doctype html>
                        <html>
                        <head>
                        <meta charset="utf-8">
                        <title>Reset Password</title>
                        <meta charset="utf-8">
                        <meta http-equiv="X-UA-Compatible" content="IE=edge">
                        <meta name="viewport" content="width=device-width, initial-scale=1">
                        </head>
                        <body class="login-sn">
                            <div class="login-detail" style="text-align: center">
                                   
                                <h1 style="color:#26A65B">'.$verify.'</h1>
                                </div>    
                        </body>
               </html>';                        
                              
        
    }
}
 
////    
//    
//     $password  = md5($_POST['password']);
//    
////    SELECT * FROM `users` WHERE `email`
//     $conn	=	new MySQLi(HOST, USER, PASSWORD, DATABASE);
//     
////     $encEmail  = urldecode($_POST['encstring']);
//     
//     $genMethod  =  new GeneralMethod();
//     
//     //int- to escape unnecessary string at the end
//     $decUser  = (int) $genMethod->decryptNET3DES(ENKEY, $encUser);
////print_r($userId);
//
//     $userId  =  substr($decUser, 0, -3);
////     print_r('-userid');
////   print_r($userId);
//    //check for database connection error
//    if(mysqli_connect_errno()){
//        echo 'Failed to connect to MySQL '.mysqli_connect_error();
//    }
//    $results  =  mysqli_query($conn,"SELECT email FROM `users` WHERE `user_id`='$userId' AND `is_deleted`=0");
//    
////    print_r($results);
//if($results){
//    $row  = $results->fetch_assoc();
//    if(empty($row)){
//        
//        echo ' <html><body class="login-sn">
//                            <div class="login-detail" style="text-align: left">
//                                   
//                                <h1 style="color:#f00">User not exists</h1>
//                                </div>    
//                        </body></html>';
//        exit();
//    }
//}
//else{
//    echo 'Something went wrong';exit();
//}
//
//if($userId!=''){
//            $sql = "UPDATE `users` SET `password`='$password' WHERE `user_id`='$userId' AND `is_deleted`=0";
//    
//            $result  =  mysqli_query($conn,$sql);
//                        
//               if($result){
//                  $verify =  'Password reset successfull!';
//               }
//               else{
//                  $verify =  'Password reset is not successfull!'; 
//               }
//               
//        echo '<!doctype html>
//                        <html>
//                        <head>
//                        <meta charset="utf-8">
//                        <title>Reset Password</title>
//                        <meta charset="utf-8">
//                        <meta http-equiv="X-UA-Compatible" content="IE=edge">
//                        <meta name="viewport" content="width=device-width, initial-scale=1">
//                        </head>
//                        <body class="login-sn">
//                            <div class="login-detail" style="text-align: left">
//                                   
//                                <h1 style="color:#26A65B">'.$verify.'</h1>
//                                </div>    
//                        </body>
//               </html>';    
//}
//    mysqli_close($conn);        
//}
//else{
//    echo 'Nothing here';
//}