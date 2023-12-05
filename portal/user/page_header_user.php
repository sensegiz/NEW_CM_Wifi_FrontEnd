<?php
    $file_name  = basename($_SERVER['REQUEST_URI']);

    $active1 =  $active2 = $active3 =  $active4 =  $active5 =  $active6 =  '';
    if($file_name=='gateways.php'){
        $active1  =  'activecl';
    }
    else if($file_name=='devices.php'){
        $active2  =  'activecl';
    }
     
    ob_start();
    session_start();

?>

<html>
<head>
<meta charset="utf-8">
<title>SenseGiz Portal</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="../css/master.css" rel="stylesheet">
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/developer.css" rel="stylesheet">
<link href="../css/responsive.css" rel="stylesheet">
<link rel="stylesheet" href="../css/jquery-ui.css">
<!--<link href="css/jquery.simple-dtpicker.css" rel="stylesheet">-->
<!--<link href="css/bootstrap-modal.css" rel="stylesheet">-->
     <script src="../js/jquery-1.11.0.min.js"></script>
     <!-- <script src="../js/custom_user.js"></script>  -->
    
    <link href="../css/intlTelInput.css" rel="stylesheet" type="text/css">
<!--<script async="" src="//www.google-analytics.com/analytics.js"></script>-->
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
	<!--<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>-->

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	
<script type="text/javascript" src="https://code.jquery.com/jquery-1.10.2.js"></script>

 <!--<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">-->
  <link rel="apple-touch-icon" href="/bootstrap/img/apple-touch-icon.png">
  <link rel="apple-touch-icon" sizes="72x72" href="/bootstrap/img/apple-touch-icon-72x72.png">
  <link rel="apple-touch-icon" sizes="114x114" href="/bootstrap/img/apple-touch-icon-114x114.png">
  <script async="" src="//www.google-analytics.com/analytics.js"></script>

  <!--<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>-->
  <!--<script type="text/javascript" src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>-->




<!--<link rel="shortcut icon" type="image/x-icon" href="https://static.codepen.io/assets/favicon/favicon-aec34940fbc1a6e787974dcd360f2c6b63348d4b1f4e06c77743096d55480f33.ico">-->
	<!--<link rel="mask-icon" type="" href="https://static.codepen.io/assets/favicon/logo-pin-8f3771b1072e3c38bd662872f6b673a722f4b3ca2421637d5596661b4e2132cc.svg" color="#111">-->
	

	<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">-->
	<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>-->
	<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>-->


	
	
<!-- <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css"/> -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<!--<script type="text/javascript" src="js/bootstrap.min.js"></script>-->
        	
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" type="text/css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>



	
	

	
</head>
<style>
/*
 * Off Canvas
 * --------------------------------------------------
 */
@media screen and (max-width: 768px) {
  .row-offcanvas {
    position: relative;
    -webkit-transition: all 0.25s ease-out;
    -moz-transition: all 0.25s ease-out;
    transition: all 0.25s ease-out;
  }

  .row-offcanvas-left
  .sidebar-offcanvas {
    //left: -33%;
	left: -56%;
  }

  .row-offcanvas-left.active {
    //left: 33%;
	left: 60%;
  }

 // .sidebar-offcanvas {
    position: absolute;
    top: 0;
    width: 33%;
    margin-left: 10px;
  }
.sidebar-offcanvas {
    position: absolute;
    top: 52px;
    width: 56%;
    margin-left: -6px;
}
}


/* Sidebar navigation */
.nav-sidebar {
  background-color: #1c1b21;
  margin-right: -15px;
  margin-bottom: 20px;
 /* margin-left: -15px;*/
   margin-left:-7px;

}
.nav-sidebar > li {

    border-bottom: 1px solid  #aaa !important;

}
/*.nav-sidebar > li > a {
  padding-right: 20px;
  padding-left: 20px;
}*/
.nav-sidebar > li > a {

  
    padding: 17px 5px 5px 38px;
    font-size: 14px;
    color:#52B3D9;

    font-weight: bold;
}
.nav-sidebar > .active > a {
  color: #fff;
  background-color: #428bca;
}


.nav-sidebar > li > a:hover, .nav > li > a:focus {

    text-decoration: none;
  
    background-color: #131217;

	color:#E1D7D7 !important;

}


//.affix-top,.affix{
 position: static;
}

@media (min-width: 999px) {
  #sidebarmenu{
    position: static;
    margin-top:30px;
    width:15%;
  }
  
  #sidebarmenu{
    position: fixed;
    top:45px;
    width:16.667%;
    overflow-y:auto;
    margin-left:-23px;
    height:90%
  }
}

#sidebarmenu li.active {
  border:0 #eee solid;
  border-right-width:5px;
}


//.nav > li > a:hover, .nav > li > a:focus {

    text-decoration: none;
    background-color: 

    #eee;

}

@media only screen and (min-width: 560px) and (max-width:660px){

	.row-offcanvas-left.active {


    left: 35%;

}

.sidebar-offcanvas {

    position: absolute;
    top: 62px;
    width: 195px;
    margin-left: -16px;

}


.row-offcanvas-left .sidebar-offcanvas {

    //left: -33%;
    left: -190%;
}
}



@media only screen and (min-width: 661px) and (max-width:768px){


.row-offcanvas-left .sidebar-offcanvas {
    //left: -33%;
    left: -179%;
}
.sidebar-offcanvas {
    position: absolute;
    top: 61px;
    width: 200px;
    margin-left: -6px;
}
.row-offcanvas-left.active {
    //left: 33%;
    left: 31%;
}

}
@media only screen and (min-width: 769px) and (max-width:995px){
.sidebar-offcanvas {
    position: absolute;
    top: 78px;
    width: 200px;
    margin-left: -120px;
}
}
</style>

<body>
<div class="outer-div">
 <!-- <div class="pos-fix">-->
      <!--div class="logo"><a href="../index.php"><img width="215" height="78" src="../user/user_logo/<?php echo trim($_SESSION['logo']); ?>" alt="logo"/></a>-->
<!--      <!--<p>Admin Panel</p>-->
<!--<div class="container-fluid" data-offset="50" data-spy="scroll" data-target="#sidebarmenu" style="margin-top:50px">-->
      
      <!--<div class="row row-offcanvas row-offcanvas-left">-->
        
         <!--<div class="col-2 col-sm-2 col-md-2 sidebar-offcanvas" data-offset="60" id="sidebar" role="navigation">-->
           <!--<div id="sidebarmenu"style="margin-top:9px">-->
 <div class="container-fluid">
      <div class="row row-offcanvas row-offcanvas-left">
        
        <!-- sidebar -->
	<div class="col-2 col-sm-2">
        <div class=" col-2 col-sm-2 sidebar-offcanvas" id="sidebarmenu" role="navigation">
        <ul class="nav nav-sidebar gateway-list-lfnav">
      </ul>
		</div>
      
</div>

<?php 
    if(isset($_SESSION['userId']) && isset($_SESSION['apikey'])){
        echo '<input type="hidden"name="sesval" id="sesval" value="" data-uid="'.$_SESSION['userId'].'" data-key="'.$_SESSION['apikey'].'"  data-date_format="'.$_SESSION['date_format'].'"  data-rms_values="'.$_SESSION['rms_values'].'"  data-logo="'.$_SESSION['logo'].'"  data-temp_unit="'.$_SESSION['temp_unit'].'"/> ';
    }
?>
    