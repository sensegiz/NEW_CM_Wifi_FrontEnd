<?php

class VerifyAPIKey extends Slim\Middleware {

    private $whiteList;

    public function __construct() {
        //Define the urls that you want to exclude from Authentication
        $this->whiteList = ['/app/login','/app/forgotpassword','/admin/login','/admin/sendinvite','/admin/invited-users',
            '/admin/create-password','/admin/gateways','/admin/blacklist-gateways','/admin/blacklist-devices', '/admin/add-user'];
        //'/user/gateways','/user/devices/:gatewayId','/user/devices/:gatewayId/:deviceId','/user/threshold','/user/get-currentvalue'
    }

    public function call() {
        if (in_array($this->app->request->getResourceUri(), $this->whiteList)) {
            $this->next->call();
            return;
        }

        $headers    = $this->app->request->headers();
        $api_key    = $headers['Api-Key'];
        $uid        = $headers['uid'];
        
        
        $db         = new ConnectionManager();
        try {
            //if admin continue otherwise check for api key
            if(isset($headers['admin']) && $headers['admin']!=''){
                $this->next->call();
                return;
            }
            
            
            //$query = "SELECT if((user_id =:user_id AND NOW() < expires_on), TRUE, FALSE) as api_check FROM api_keys WHERE api_key = :api_key";
	$query = "SELECT CASE WHEN user_id =:user_id AND NOW() < expires_on THEN 1 ELSE 0 END as api_check FROM api_keys WHERE api_key = :api_key";
            $db->query($query);
            $db->bind(':user_id', $uid);
            $db->bind(':api_key', $api_key);
            $result = $db->single();
            if ($result['api_check']) {
                
                    //Delete if any expired tokens exists
                            $sQuery = " DELETE FROM api_keys "                                    
                                    . " WHERE user_id=:user_id AND expires_on<NOW() AND is_deleted =0";
                            $db->query($sQuery);
                            $db->bind(':user_id', $uid);
                            $db->execute();                
                    //
                
                $this->next->call();
            } else {
                $this->app->response->status(ERRCODE_UNAUTHORIZED);
                $this->app->response->body(json_encode(array(JSON_TAG_STATUS => JSON_TAG_ERROR,
                    JSON_TAG_DESC => INVALID_API_KEY)));
            }
        } catch (Exception $exc) {
            $this->app->response->status(ERRCODE_SERVER);
            $this->app->response->body(array(JSON_TAG_STATUS => JSON_TAG_ERROR,
                JSON_TAG_DESC => SERVER_EXCEPTION));
        }
    }

}
