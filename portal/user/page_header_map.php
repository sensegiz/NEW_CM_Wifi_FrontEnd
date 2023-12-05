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
<link rel="stylesheet" href="../css/jquery-ui.css">
<!--<link href="css/jquery.simple-dtpicker.css" rel="stylesheet">-->
<!--<link href="css/bootstrap-modal.css" rel="stylesheet">-->
    <script src="../js/jquery-1.11.0.min.js"></script> 
    
    <link href="../css/intlTelInput.css" rel="stylesheet" type="text/css">   
</head>

<body>
    
<?php 
    if(isset($_SESSION['userId']) && isset($_SESSION['apikey'])){
        echo '<input type="hidden"name="sesval" id="sesval" value="" data-uid="'.$_SESSION['userId'].'" data-key="'.$_SESSION['apikey'].'" /> ';
    }
?>
    
