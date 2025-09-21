<?php

class CurlRequest{
    
//    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json',"OAuth-Token: $token"));
    
    //To make GET requests
    function getRequestData($url){
        
        $ch = curl_init();  
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $output=curl_exec($ch);

        curl_close($ch);
        return $output;
    }
    
    //To make POST requests
    function postRequestData($url,$dataArray){
          $postData = http_build_query($dataArray, '', '&');
//          print_r('post data-'.$postData);exit();
          //create name value pairs seperated by &
//          foreach($params as $k => $v) 
//          { 
//             $postData .= $k . '='.$v.'&'; 
//          }
//          $postData = rtrim($postData, '&');

           $ch = curl_init();  
           curl_setopt($ch,CURLOPT_URL,$url);
           curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
           
           curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3600);
           
           curl_setopt($ch,CURLOPT_HEADER, false); 
           curl_setopt($ch, CURLOPT_POST, count($postData));               
           curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);    

           $output=curl_exec($ch);

           curl_close($ch);
           return $output;        
    }


    //

     function postRequestDataForTransmission($url,$dataArray){
          $postData = http_build_query($dataArray, '', '&');

          error_log("Sending to: $url");
          error_log("Data: $postData");


           $ch = curl_init();  
           curl_setopt($ch,CURLOPT_URL,$url);
           curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
           
           curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3600);
           
           curl_setopt($ch,CURLOPT_HEADER, false); 
           curl_setopt($ch, CURLOPT_POST, true);               
           curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);    

           $output=curl_exec($ch);

            if (curl_errno($ch)) {
            // Log the error
            $error_msg = curl_error($ch);
             error_log("CURL ERROR (Transmission API): " . $error_msg);
            curl_close($ch);
            return false;
            }

           curl_close($ch);
           return $output;        
    }
    
    //To make POST requests
    function putRequestData($url,$dataArray){
        
        $postData = http_build_query($dataArray, '', '&');

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

        $output = curl_exec($ch);

        curl_close ($ch);
        return $output;       
    }
    
    //To make DELETE requests
    function deleteRequestData($url){
        
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//      curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json',"OAuth-Token: $token"));
        
        $output = curl_exec($curl);  
        
        curl_close ($ch);
        return $output;    
    }

//end
}

