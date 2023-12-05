<?php
/*
  Module                      : General
  File name                   : GeneralMethod.php
  Description                 : General utility functions
 */

class GeneralMethod {

    //put your code here
    public function __construct() {
        
    }

    public function isValidEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return TRUE;
        }
        else {
            return FALSE;
        }
    }    
    
    //Changing date format from 15/08/2015 to 2015-08-15
    public function changeDateformat($myDate){
        $new_st_date  =  '';
        if($myDate!=''){
            $st_dt  = explode('/', $myDate);
            
            $new_st_date  =   $st_dt[2].'-'.$st_dt[1].'-'.$st_dt[0];
        }
        return $new_st_date;
    }
    
    /*
      Function            : sendMails($emails,$subject,$message)
      Brief               : Function used to display the result in json format.
      Details             : Function used to display the result in json format.
      Input param         : $emails,$subject,$message
      Input/output param  : Nil
      Return              : bool
     */
    public function sendMails($emailsArray,$subject,$message){
        
        require 'library/sendgrid-php/vendor/autoload.php';

        $sendgrid = new SendGrid(SENDGRID_APIKEY);
        $email    = new SendGrid\Email();

        $email->setSmtpapiTos($emailsArray)
              ->setFrom("admin@cumulations.com")
              ->setFromName('SensGiz')
              ->setSubject($subject)
              ->setHtml($message);

        $sendgrid->send($email);  
   
    }
    
     /*
      Function            : ConvertLocalTimezoneToGMT($gmttime,$timezoneRequired)
      Brief               : Function used to convert user timezone datetime to GMT timezone datetime
      Details             : Function used to convert user timezone datetime to GMT timezone datetime
      Input param         : $gmttime,$timezoneRequired
      Input/output param  : Nil
      Return              : GMT date
     */   
    public function ConvertLocalTimezoneToGMT($gmttime,$timezoneRequired){
            //   $system_timezone = date_default_timezone_get();

               $local_timezone = $timezoneRequired;
               date_default_timezone_set($local_timezone);
               $local = date("Y-m-d h:i:s A");

               date_default_timezone_set("GMT");
               $gmt = date("Y-m-d h:i:s A");

           //    date_default_timezone_set($system_timezone);
               $diff = (strtotime($gmt) - strtotime($local));

               $date = new DateTime($gmttime);
               $date->modify("+$diff seconds");
               $timestamp = $date->format("Y-m-d H:i:s");
               return $timestamp;
    }
    
     /*
      Function            : isValidImage($profileImg)
      Brief               : Function to validate image file type
      Details             : Function to validate image file type
      Input param         : $profileImg
      Input/output param  : Nil
      Return              : Bool
     */       
    public function isValidImage($profileImg){
        
            $imgtype  = getimagesize($profileImg['tmp_name']);
            
            if($imgtype['mime']=='image/jpeg' || $imgtype['mime']=='image/png'){
                return true;
            }
            else{
                return false;
            }
    }
    
    //To create associate keys 
    function assoc_by($key, $array) {
        $new = array();
        foreach ($array as $v) {
            if (!array_key_exists($v[$key], $new))
                $new[$v[$key]] = $v;
        }
        return $new;
    }    
   

//To get the image extension
function getExtension($str) {
         $i = strrpos($str,".");
         if (!$i) { return ""; }
         $l = strlen($str) - $i;
         $ext = substr($str,$i+1,$l);
         return $ext;
}    
    
    //3DES encryption
    function encryptNET3DES($key,  $text){
        
        $td = mcrypt_module_open(MCRYPT_3DES, NULL, MCRYPT_MODE_ECB, NULL);

        $vector = str_repeat(chr(0), mcrypt_enc_get_iv_size($td));

        // Complete the key
        $key_add = 24 - strlen($key);
        $key .= substr($key, 0, $key_add);

        // Padding the text
        $text_add = strlen($text)%8;
        for ($i=$text_add; $i<8; $i++){
                $text .= chr(8-$text_add);
        }

        mcrypt_generic_init($td, $key, $vector);
        $encrypt64 = mcrypt_generic($td, $text);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

         // Return the encrypt text in 64 bits code
        return base64_encode($encrypt64);
    }
    
    //3DES decryption
    function decryptNET3DES($key,  $text){
        $text=	base64_decode($text);
        $td = mcrypt_module_open(MCRYPT_3DES, NULL, MCRYPT_MODE_ECB, NULL);

        $vector = str_repeat(chr(0), mcrypt_enc_get_iv_size($td));

        // Complete the key
        $key_add = 24 - strlen($key);
        $key .= substr($key, 0, $key_add);


        mcrypt_generic_init($td, $key, $vector);
        $encrypt64 = mdecrypt_generic($td, $text);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

         // Return the encrypt text in 64 bits code
        return $encrypt64;
    }   
   
function getLastNDays($days, $format = 'd/m/Y'){
    $m = date("m"); $de= date("d"); $y= date("Y");
    $dateArray = array();
    for($i=0; $i<=$days-1; $i++){
        $dateArray[] = date($format, mktime(0,0,0,$m,($de-$i),$y)); 
    }
    return array_reverse($dateArray);
}    
////
    
    
    
    /*
      Function            : generateResult($inRes)
      Brief               : Function used to display the result in json format.
      Details             : Function used to display the result in json format.
      Input param         : res - result in array
      Input/output param  : Nil
      Return              : Outputs Json data
     */

    public function generateResult($inaRes) {
        $Req = \Slim\Slim::getInstance();
        $Req->contentType('application/json');
        echo json_encode($inaRes);
    }

    /*
      Function            : decodeJson($injData)
      Brief               : Function used to decode input json.
      Details             : Function used to decode input json.
      Input param         : Json data
      Input/output param  : Nil
      Return              : Outputs array
     */

    public function decodeJson($injData) {
        $bvalid = true;
        if ($injData == "" || !$injData) {
            $bvalid = false;
        } else {
            $aDecoded = json_decode($injData, true);
            if (!is_array($aDecoded))
                $bvalid = false;
        }

        if ($bvalid)
            return $aDecoded;
        else {
            return array(
                JSON_TAG_TYPE => JSON_TAG_ERROR,
                JSON_TAG_CODE => NULL,
                JSON_TAG_DESC => INVALID_JSON,
                JSON_TAG_ERRORS => array()
            );
        }
    }

    /*
      Function            : bCrypt($insPassword, $sSalt)
      Brief               : Function used to encode the password into BCrypt format.
      Details             : Function used to encode the password into BCrypt format using the salt data from database.
      Input param         : $insPassword - input password, $sSalt - salt data retrieved from database.
      Input/output param  : Nil
      Return              : Outputs hashed password.
     */

    public function bCrypt($insPassword, $sSalt) {

        //This string tells crypt to use blowfish for 5 rounds.
        $Blowfish_Pre = '$2a$05$';
        $Blowfish_End = '$';
        $bcrypt_salt = ($Blowfish_Pre . $sSalt . $Blowfish_End);

        $hashed_password = crypt($insPassword, $bcrypt_salt);
        return $hashed_password;
    }

    /*
      Function            : createHashedPassword($sPassword)
      Brief               : Function used to create hashed password.
      Details             : Function used to create hashed password.
      Input param         : $sPassword - password
      Input/output param  : Nil
      Return              : Outputs hashed password.
     */

    public function createHashedPassword($sPassword) {

        $aData = array();
        $Blowfish_Pre = '$2a$05$';
        $Blowfish_End = '$';

        $Allowed_Chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789./';
        $Chars_Len = 63;

        // 18 would be secure as well.
        $Salt_Length = 21;

        //$mysql_date = date('Y-m-d');
        $salt = "";

        for ($i = 0; $i < $Salt_Length; $i++) {
            $salt .= $Allowed_Chars[mt_rand(0, $Chars_Len)];
        }
        $bcrypt_salt = $Blowfish_Pre . $salt . $Blowfish_End;

        $hashed_password = crypt($sPassword, $bcrypt_salt);
        $aData['hashedPass'] = $hashed_password;
        $aData['salt'] = $salt;

        return $aData;
    }

    /*
      Function            : generateUUID()
      Brief               : Function used to generate UUID.
      Details             : Function used to generate UUID.
      Input param         : Nil
      Input/output param  : Nil
      Return              : Outputs UUID v4.
     */

    public function generateUUID() {

        // http://www.php.net/manual/en/function.uniqid.php#94959

        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                // 32 bits for "time_low"
                mt_rand(0, 0xffff), mt_rand(0, 0xffff),
                // 16 bits for "time_mid"
                mt_rand(0, 0xffff),
                // 16 bits for "time_hi_and_version",
                // four most significant bits holds version number 4
                mt_rand(0, 0x0fff) | 0x4000,
                // 16 bits, 8 bits for "clk_seq_hi_res",
                // 8 bits for "clk_seq_low",
                // two most significant bits holds zero and one for variant DCE1.1
                mt_rand(0, 0x3fff) | 0x8000,
                // 48 bits for "node"
                mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    /*
      Function            : sanitizeString($insString)
      Brief               : Function used to sanitize input string.
      Details             : Function used to sanitize input string.
      Input param         : String
      Input/output param  : Nil
      Return              : Outputs sanitized string.
     */

    public function sanitizeString($insString) {
       $insString = strip_tags($insString); 
        if (get_magic_quotes_gpc())
            $insString = stripslashes($insString);
        $insString =  trim($insString);
        return $insString;
    }

    /*
      Function            : isValidDate( $postedDate )
      Brief               : Function used to check date.
      Details             : Function used to check date.
      Input param         : String
      Input/output param  : Nil
      Return              : bool
     */

    public function isValidDate($postedDate) {
        if (preg_match('/^[0-9]{4}-([0-9]|0[1-9]|1[0-2])-([0-9]|0[1-9]|[1-2][0-9]|3[0-1])$/', $postedDate)) {
            list($year, $month, $day) = explode('-', $postedDate);
            return checkdate($month, $day, $year);
        } else {
            return false;
        }
    }

    /*
      Function            : isValidDatetime($dateTime)
      Brief               : Function used to check date time object
      Details             : Function used to check date time object
      Input param         : String
      Input/output param  : Nil
      Return              : bool
     */

    public function isValidDatetime($dateTime) {
        $matches = array();
        // if(preg_match("/^(\d{4})-(\d{2})-(\d{2}) ([0-1][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/", $dateTime, $matches)){
        if (preg_match("/^(\d{4})-([0-9]|0[1-9]|1[0-2])-([0-9]|0[1-9]|[1-2][0-9]|3[0-1]) ([0-1][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/", $dateTime, $matches)) {

            // print_r($matches); echo "<br>";
            $yy = trim($matches[1]);
            $mm = trim($matches[2]);
            $dd = trim($matches[3]);
            return checkdate($mm, $dd, $yy); // <- Problem here?
        } else {
//        echo "wrong format<br>";
            return false;
        }
    }

    /*
      Function            : emptyElementExists($arr)
      Brief               : Function used to verify whether an array has null element.
      Details             : Function used to verify whether an array has null element.
      Input param         : array
      Input/output param  : Nil
      Return              : bool
     */

    public function emptyElementExists($arr) {
        //If one or more elements of an array contains null values then this function returns true.
        return array_search("", $arr) !== false;
    }

       /*
      Function            : generateZip($sFolderPath, $sZipName)
      Brief               : Function used to generate zip file for download
      Details             : Function used to generate zip file for download
      Input param         : folder path and zip name
      Input/output param  : Nil
      Return              : Int
     */

    public function generateZip($sFolderPath, $sZipName) {
        $iStatus = 0;
        try {

            $zip = new ZipArchive;
            $sZipPath = dirname($sFolderPath) . "/";
            $res = $zip->open($sZipPath . $sZipName, ZipArchive::OVERWRITE);
            if ($res === TRUE) {
                if ($handle = opendir($sFolderPath)) {
                    while (false !== ($entry = readdir($handle))) {
                        if ($entry != "." && $entry != ".." && !strstr($entry, '.php')) {
                            $zip->addFile($sFolderPath . "/" . $entry, $entry);
                        }
                    }
                    closedir($handle);
                }
                $zip->close();
            } else {
                $iStatus = 1;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
  
    /*
      Function            : outputFile($file, $sName)
      Brief               : Function used to present PNG as download.
      Details             : Function used to present PNG as download.
      Input param         : File path and name for saving
      Input/output param  : Nil
      Return              : Nil (Presents a PNG file for download in browser)
     */

    public function outputFile($file, $sName) {
        // Download the PNG file
//        $file = str_replace(" ", "\\ ", $file);
        header("Content-Type: application/png");
        header("Content-Disposition: attachment; filename=" . str_replace(" ", "_", $sName));
//        header("Content-Length: " . filesize($file));
        ob_clean();
        flush();
        readfile($file);
        unlink($file);
        exit;
    }
    /*
      Function            : encodePassword($insRawPassword)
      Brief               : Function used to encode password.
      Details             : Function used to encode password.
      Input param         : $insRawPassword - Raw password.
      Input/output param  : Nil
      Return              : $sEncodedString - Encoded password
     */
  public function encodePassword($insRawPassword){
    $sBase64encoded = base64_encode($insRawPassword);
    //Add salt of 15 character string to the 2nd position of base_64 encoded string.
    $sEncodedString = substr($sBase64encoded, 0, 2) .PASSWORD_SALT. substr($sBase64encoded,2);
    return $sEncodedString;
  }
    
    /*
      Function            : decodePassword($sPassword)
      Brief               : Function used to decode password.
      Details             : Function used to decode password.
      Input param         : $sPassword - Encoded password.
      Input/output param  : Nil
      Return              : $sDecodedPassword - Decoded password
     */
    public function decodePassword($sPassword){
    $sEncodedPassword = str_replace(substr($sPassword, 2, 15), "", $sPassword);
    $sDecodedPassword = base64_decode($sEncodedPassword);
    return $sDecodedPassword;
}

    /*
      Function            : getFileExtension($fileName)
      Brief               : Function used to get file Extension.
      Details             : Function used to get file Extension.
      Input param         : $filename - File Name.
      Return              : $ext - File Extension 
     */
    public function getFileExtension($fileName){

    // 1. The "explode/end" approach
    $ext = end(explode('.', $fileName));
    // 2. The "strrchr" approach
    $ext = substr(strrchr($fileName, '.'), 1);
    // 3. The "strrpos" approach
    $ext = substr($fileName, strrpos($fileName, '.') + 1);
    // 4. The "preg_replace" approach
    $ext = preg_replace('/^.*\.([^.]+)$/D', '$1', $fileName);
    // 5. The "never use this" approach
    $exts = split("[/\\.]", $fileName);
    $n = count($exts)-1;
    $ext = $exts[$n];
    return $ext ;
    }

    function makeSafe($value) {
	$value = htmlentities($value, ENT_QUOTES, 'UTF-8'); // use htmlspecialchars() if you want
	return $value;
   }
   
   //Genearte API Key
    function generateApiKey(){
       
        $apKey = md5(time());
        
        return $apKey;        
    } 
    
   //Get User Id From header
    function getUserId(){
        $instance = \Slim\Slim::getInstance();
        $headers    = $instance->request()->headers();        
        $userId     = $headers['uid'];
//        print_r($headers);exit();
        return $userId;
        
    }    

    
}

?>
