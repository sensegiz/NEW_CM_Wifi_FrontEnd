
function getBasePath(){
//    var basePath = "http://localhost/sensegiz/admin/";
////  var basePath = "http://40.83.125.24/sensegiz/admin/";
//
//    return basePath;
 
        var baseurl  = window.location.origin;//http://sensegiz.com
//        var basePath = baseurl+"/sensegiz-dev/admin/";//Local
        
        var basePath = baseurl+"/sensegiz-dev/admin/";//Staging or Dev
        
    //  var basePath = "http://35.165.93.101/sonrex/admin/";

        return basePath;    
     
}

function getBasePathUser(){
//    var basePathUser      = "http://localhost/sensegiz/user/";
  //var basePathUser      = "http://40.83.125.24/sensegiz/user/";
  
        var baseurl  = window.location.origin;//http://sensegiz.com
//        var basePath = baseurl+"/sensegiz/user/";
        
        var basePath = baseurl+"/sensegiz-dev/user/";//Staging or Dev

    return basePath;
}

function getBasePathApp(){
 
        var baseurl  = window.location.origin;//http://sensegiz.com
//        var basePath = baseurl+"/sensegiz-dev/app/";
        
        var basePath = baseurl+"/app/";//Staging or Dev

        return basePath;    
     
}
