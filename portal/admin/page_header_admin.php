<?php

$url        = $_SERVER["SCRIPT_NAME"];
$break      = Explode('/', $url);
$file_name  = $break[count($break) - 1];

//echo $file; 
//    $file_name  = basename($_SERVER['REQUEST_URI']);
//    print_r($file_name);exit();
    $active1 =  $active2 = $active3 =  $active4 =  $active5 =  $active6 =  '';
    if($file_name=='users.php'){
        $active1  =  'activecl';
    }
    else if($file_name=='gateways-admin.php' || $file_name=='devices-admin.php'){
        $active2  =  'activecl';
    }

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

</head>

<body>
<div class="outer-div">
  <div class="pos-fix">
      <div class="logo"><a href="../index.php"><img width="174" height="76" src="../img/logo.png" alt="logo"/></a>

    </div>
    <div class="menu">
        <ul class="admin-lftnav">
          <li><a href="users.php" class="register <?php echo $active1?>"> <span class="sprite usr"></span>Users<span class="reg"></span></a></li>
          <li><a href="gateways-admin.php" class="register <?php echo $active2?>"> <span class="sprite brd"></span>Gateways<span class="reg"></span></a></li>         

      </ul>
    </div>
  </div>