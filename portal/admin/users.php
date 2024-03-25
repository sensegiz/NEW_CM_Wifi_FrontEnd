<?php
//1 error shows
//0 supresses
error_reporting(E_ALL);
ini_set("display_errors", 0);
ob_start();
include_once('page_header_admin.php');

session_start();

$status_msg  =  '';
$id  =   0;


?>

        <style>
.content {
    margin-left: 218px;
    padding: 35px 20px;}
  {box-sizing: border-box;}
}

    
</style>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

 <!--<div class="col-lg-10 pad0 ful_sec"> 
      	<div class="row pad0">-->
        <div class="content">
        	<h1>Add Users</h1>
            <div class="pull-right log-det">
                                
                <span class="sprite user"></span>
                <p class="username"><?php if(isset($_SESSION['userName']) && $_SESSION['userName'] != ''){echo strtoupper($_SESSION['userName']);} ?> </p>
                <p><span class="logout"> LOGOUT</span></p>
                <!--<span class="sprite dwn-arr"></span>-->
            </div>
            <div class="detail-content">
                
                <div class="form-det">
                        <div class="">
                                <span class='error'></span>
                                <span class='success'></span>

                                <form method="post" id="sendInviteForm" class="form-inline" name="form-inline">            
                                        <input type="hidden" name="u_id" id="u_id" value="0" />
                                        <input type="hidden" name="admin_id" id="admin_id" value="<?php if(isset($_SESSION['adminId']) && $_SESSION['adminId']>0){echo strtoupper($_SESSION['adminId']);} ?>" />
                                        
                                        <label>Email Id</label>
                                        <input type="text" name="user_email" id="user_email" value="" class="form-validate dev-inp" style="width: 50%" placeholder="Email Id"/>
                                        <label>Phone &nbsp; &nbsp; (include country code)</label>
                                        <input type="text" name="user_phone" id="user_phone" value="" class="form-validate dev-inp" style="width: 50%;" placeholder="ex: +919626262626"/>
                                        <label>Password </label>
                                        <input type="password" name="user_pass" id="user_pass" value="" class="form-validate dev-inp" style="width: 50%;" placeholder="Password"/>
                                        <label>Confirm Password </label>
                                        <input type="password" name="user_cpass" id="user_cpass" value="" class="form-validate dev-inp" style="width: 50%;" placeholder="Confirm Password"/>
                                                      
                                        <br />
                                        <br />
                                        <input type="submit" class="save_but addUser" name="add_user" value="Add User"/>
                              </form>
                        </div>
                  
                </div>
                

                
                <div id="form-email">
<!--                         
                                <span class='error'></span>
                                <span class='success'></span> -->
                                
                                <!-- <form method="post" id="sendInviteForm" class="form-inline" name="form-inline">             -->
                                <form id="ss">
                                <h1 id="form_head">Update Email</h1>
                                <br />
                                <br />
                                
                                <label for="u_name">Email</label>  
                                <br /> <div> 
                                <!-- <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span> -->
                                <!-- <input  type="email" name="u_email" id="u_email" style="width: 120%" /> -->
                                <div class="input-group" style="width: 140%">
                                <input id="u_email" type="email" class="form-control" name="u_email" placeholder="Email" >
                                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>   
                                 </div>
                                </div>
                                <br />
                                <label for="user_email_new"> Enter New Email </label>
                                <br />
                                <div class="input-group" style="width: 140%">
                                <input id="user_email_new" type="email" class="form-control" name="user_email_new" placeholder="Enter New Email Id" value="">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>   
                                 </div>
                                <!-- <input type="email" name="user_email_new" id="user_email_new" value="" class="form-validate dev-inp" style="width: 120%" placeholder="Email"/> -->
                                
                                <!-- <br />
                                <label for="user_email_newC"> Confirm Email</label>
                                <input type="email" name="user_email_newC" id="user_email_newC" value="" class="form-validate dev-inpC"  placeholder="Confirm email"/>
                                                       -->
                                <br />
                                <button type="button" class="btn btn-success" id="save_but_updateUser" name="save_but_updateUser" >Update Email</button>
                                <button type="button" class="btn btn-danger" id="cancel" >Cancel</button>
                                <!-- <input type="submit" class="save_but_updateUser" name="save_but_updateUser" value="Update Email"/> -->
                                <!-- <input type="button" class="cancel"  value="Cancel"/> -->
                               </form>
                               <div class="alert alert-warning" id="mm">
                                <strong>WARNING! </strong> Enter user email
                                    </div>
                                    <div class="alert alert-danger" id="nn">
                                <strong>!! </strong> User Already Exists
                                    </div>
                                    
                                   
                              
                  
                </div>
               
                
                
                <!-- -->
    <div class="lp-det">
         <span class='error-tab'></span>
        <div class="table-responsive">
            <table class="table table-hover table-bordered table-lp">
                <tr class="active tb-hd">
                    <th width="40">Sl No</th>
                    <th>Email Id</th>
                    <th>Phone</th>
                    <th>Date</th> 
                    <th>Coin Offline Time</th> 
                    <th>Edit</th> 
                    <th>Update Email</th> 
                    <th>Delete</th>
                                     
                </tr>
                <tbody class="users users-list" id="invitedUsers">

                </tbody>
                            
<!--                <tr class="active-row">
                    <td>1</td>                    
                    <td>Software</td>
                    <td>2</td>
                    <td>Yes</td>                    
                    <td><button type="submit">Edit</button></td>                    
                    <td><span class="sprite del"></span></td>
                </tr>-->
            </table>
        </div>
    </div>                
         
            </div>
        </div>

        </div>
    </div>

  
    <style>

     #ss {
        /* color:green; */
        /* border: 1px solid #4CAF50; */
        /* border: solid_black 5px; */
        float: top-right;
        margin-right: 0px;
        padding: 0px;
        position:relative;
	    top:-310px;
	    left:500px;
	    width:220px;
	    height:0px;
        /* {box-sizing: border-box 5px;} */
        }
        #mm{
            width:250px;
            height:30px;
            padding:5px;
            position:relative;
	        top:-170px;
	        left:810px;
        
        }
        #nn{
            width:250px;
            height:30px;
            padding:5px;
            position:relative;
	        top:-170px;
	        left:810px;
        
        }
        
       
        
    
    }
    

     
    </style>




<?php
    include_once('page_footer_admin.php');
?>

<script type="application/javascript">
   
    getInvitedUsers();
     document.getElementById("form-email").style.display="none";
     document.getElementById("mm").style.display="none";
     document.getElementById("nn").style.display="none";
</script>