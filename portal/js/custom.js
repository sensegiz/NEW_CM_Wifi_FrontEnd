/*
 * Project					: SenseGiz
 * Module					: General
 * File name                                    : custom.js
 * Description                                  : This file contains all functions whcih are need for admin panel to work smoothly. 
 *						  All click events and load events are written inside this file
 * Author                                       : Vinay
 * 						
 */

basePath = getBasePath();

// DOM Ready Function
// ==================
//$('document').ready(function(){

////      var basePath = "http://localhost/sensegiz/admin/";
////        var basePath = "http://40.83.125.24/sensegiz/admin/";
              
//new commit
	// File name mapping
	// ============================	
	var apiUrlLogin               = "login";
        var apiUrlCreatePassword      = "create-password";
         
        var apiUrlLoginPhp   = "login.php";
        var apiUrlLogout     = "logout.php";
        
        var pageUrlAddUsers  = "users.php";
        var pageUrlGateways  = "gateways.php";                        
        var pageUrlLogin     = "index.php";
           
          
	/*
	 * Function                         : Click event on login button 
	 * Brief                            : events which should happen while clicking login button in login page
	 * Detail                           : events which should happen while clicking login button in login page
	 * Input param                      : event
	 * Input/output param               : NA
	 * Return                           : NA
	 */		
	$( "#login" ).submit(function( event ) {
                event.preventDefault();
                $('.error-msg').html('');
	//	if(validateLogin()) {		
			formData = $('form#login').serializeJSON();
                        //also same
                        //formData = JSON.stringify($('form#login').serializeObject());
			var email = $("input#email").val();
			var password = $("input#password").val(); 
			authString = make_base_auth(email, password);
			$.ajax({
				type: "POST",
				url: basePath + apiUrlLogin,				
				data: formData,
				async: false,
                                dataType:"JSON",
				beforeSend: function (xhr){ 
					xhr.setRequestHeader('Authorization', authString);
				},
				success: function(data, textStatus, xhr) {
                                   
					if(xhr.status == 200 && textStatus == 'success') {
						if(data.status == 'success') {
                                                    var records  =  data.records;
                                                   
                                                    if(records.login_type=='user'){
                                                        
                                                        //email ll be as name
                                                        loggedInUser  =  records.user_email;                                                        
                                                        loginType     =  'user';
                                                        loginId       =  records.user_id;
							date_format = records.date_format;
							rms_values = records.rms_values;
							logo = records.logo;
							temp_unit = records.temp_unit;

                                                        
                                                        var keyDet = records.key_details;
//                                                      return;
                                                        setSession(loggedInUser, authString, loginType, loginId, keyDet.api_key, date_format, rms_values, logo, temp_unit);
                                                    }
                                                    if(records.login_type=='admin'){
                                                       
                                                        
                                                        loggedInUser  = records.name;
                                                        loginType     = 'admin';
                                                        loginId       = records.admin_id;
                                                       
							setSession(loggedInUser, authString, loginType, loginId); 
                                                    }
							//loggedInUser  = data.name;
                                                        //loggedInEmail = data.email;
							//setSession(loggedInUser, loggedInEmail, authString);
                                                        
						}
					}
                                        
				},
                                error: function(errData, status, error){
                                        var resp = errData.responseJSON; 
                                        $('.error-msg').html(resp.description);
                                    }
			});	
		//}
	});
        
        /**
         * Function 			:   To validate login form
         * Input param                  :   Nil
         * Return                       :   True/False;
         */
        function validateLogin(){
          var email     =   $('#email').val();
          var password  =   $('#password').val();
          
          if(email==''){
              $('.error-msg').html('Please enter email id');
              return false;
          }
          else{
             if(!isvalidEmail(email)){
                $('.error-msg').html('Enter valid email id');
                return false;                 
             }
          }
          if(password==''){
              $('.error-msg').html('Please enter password');
              return false;
          }
          
          return true;
        }
        
        /**
         * Function 	    :    To validate email id
         * Input param      :    email
         * Return           :    True/False;
         */
        function isvalidEmail(email){
                var patt = /^.+@.+[.].{2,}$/i;
                $('.error-msg').html('');
                if (!patt.test(email)) {                              
                    return false;
                }
                else{
                    return true;
                }
        }
        
	/*
	 * Function                             : make_base_auth(user, password)
	 * Brief 				: Basic authentication string generation
	 * Detail 				: Basic authentication string generation
	 * Input param                          : user, password
	 * Input/output param                   : Basic hash value
	 * Return				: Returns hash value for authentication
	 */		
	function make_base_auth(user, password) {
		var tok = user + ':' + password;
		var hash = btoa(tok);
		return "Basic " + hash;
	}  
        
	/*
	 * Function 			: setSession(loggedInUser, authString, loginType)
	 * Brief 			: setting a login session on successful login
	 * Detail 			: setting a login session on successful login in server side
	 * Input param 			: loggedInUser, authString
	 * Input/output param           : NA
	 * Return			: NA
	 */		
	function setSession(loggedInUser, authString, loginType, loginId, api_key, date_format, rms_values, logo, temp_unit) {
		formData = "userName=" + loggedInUser + "&loginType=" + loginType + "&loginId=" + loginId + "&authString=" + authString + "&apikey=" + api_key + "&date_format=" + date_format + "&rms_values=" + rms_values + "&logo=" + logo + "&temp_unit=" + temp_unit;
              
		$.ajax({
			type: "GET",
			url: apiUrlLoginPhp,
			data: formData,
			success: function(data, textStatus, xhr) {
                           if(loginType=='admin'){
				window.location = "admin/"+pageUrlAddUsers;
                            }
                            else if(loginType=='user'){
                                window.location = "user/"+pageUrlGateways;
                            }
			}
		});
	}        
        
        //Logout
        $(".content").on( "click", ".logout",function(e){
                e.preventDefault();
               
                $.get( '../'+apiUrlLogout, function(data, textStatus, xhr) {
                        window.location = "../"+pageUrlLogin;
                });					                         
        });
        
	/*
	 * Function                         : Click event on Submit button 
	 * Brief                            : events which should happen while clicking on Submit button in Create Password page	 
	 * Input param                      : event
	 * Input/output param               : NA
	 * Return                           : NA
	 */		
	$(document).on('click','#submit', function( event ) {
                event.preventDefault();
                $('.error-msg').html('');
		$('.success').html('');
                    
                    var verCode       = $('#verification_code').val();
                    var pass       = $('#password').val();
                    var conf_pass  = $('#confirm_password').val();
        
                    if(pass ==''){
                             $('.error-msg').html('*Please enter password');
                             $('#password').focus();
                             return false;
                    }
                    if(conf_pass ==''){
                             $('.error-msg').html('*Please enter confirm password');
                             $('#confirm_password').focus();
                             return false;
                    } 
                    
                    if(pass!=conf_pass){
                             $('.error-msg').html('*Password & Confirm password fields should be same');
                             $('#confirm_password').focus();
                             return false;                        
                    }

			formData = $('form#passwordForm').serializeJSON();
                        
                        var postData  =  {
                            verification_code:verCode,
                            password:pass,
                        }
                      			
			$.ajax({
				type: "POST",
				url: basePath + apiUrlCreatePassword,				
                                data: JSON.stringify(postData),
                                contentType: 'application/json; charset=utf-8',
                                dataType: 'json',
                                async: false,   
				beforeSend: function (xhr){ 
					 $('#submit').text('Submitting..');
				},
				success: function(data, textStatus, xhr) {
                                     
                                         $('#submit').text('SUBMIT');
					if(xhr.status == 200 && textStatus == 'success') {
						if(data.status == 'success') {
                                                   var res  =  data.records.result;
                                                   
                                                   if(res){
                                                      
                                                       $('.form-container').css("display","none");
                                                       $('.msg-success').css("display","block");                                                       
                                                   }
                                                   else{
                                                       
                                                       $('.error-msg').html('Something went wrong');
                                                   }
                                                        
						}
					}
                                        
				},
                                error: function(errData, status, error){
                                    $('#submit').text('SUBMIT');
                                        var resp = errData.responseJSON; 
                                        $('.error-msg').html(resp.description);
                                    }
			});	
		//}
	});
        
        function changeDateformat(myDate){
            new_start_date  =   '';
            if(myDate!=''){
                var st_dt  =   myDate.split('-');
                var new_start_date  =   st_dt[2]+'/'+st_dt[1]+'/'+st_dt[0];                
             }
             return new_start_date;
        }
        
        // Delete Confirmation Function
        function confirm_delete(){
	
                var r = confirm("Are you sure!");
                if (r == true) {
                        return true;
                } else {
                        return false;
                }
     
        }

  //To get URL parameters
        function getParameterByName(name) {
            name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
            var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
                results = regex.exec(location.search);
            return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
        }
      
        //Convert Timestamp to Date Time format. 2015-11-21 08:44:05 To 21/11/2015 08:44:05
        function timestampToDateTime(sent_date){

                           var st_dt_ful  =   sent_date.split(' ');
                           var dat  =  st_dt_ful[0];
                           var tim  =  st_dt_ful[1];
                                                     
                           var st_dt  =   dat.split('-');
                           var new_sent_date  =   st_dt[2]+'-'+st_dt[1]+'-'+st_dt[0];    
                                     
                           return new_sent_date+' '+tim;
        }

////