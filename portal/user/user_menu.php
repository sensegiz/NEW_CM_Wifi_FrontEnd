

<style>
	/**********************************
Responsive navbar-brand image CSS
- Remove navbar-brand padding for firefox bug workaround
- add 100% height and width auto ... similar to how bootstrap img-responsive class works
***********************************/

.navbar-brand {
  padding: 0px;
}
.navbar-brand>img {
  height: 100%;
  padding: 15px;
  width: auto;
}



.logout{
	margin-top:25px;
	padding-top:5px !important;
}



/*************************
EXAMPLES 2-7 BELOW 
**************************/

/* EXAMPLE 2 (larger logo) - simply adjust top bottom padding to make logo larger */

.example2 .navbar-brand>img {
  padding: 7px 15px;
}

  /*sticky header position*/
.sticky.is-sticky {
    position: fixed;
    left: 0;
    right: 0;
    top: 0;
    z-index: 1000;
    width: 100%;
}





.navbar-nav>li>.dropdown-menu1 {
	z-index: 9999;
}

.navbar-default .navbar-nav > li.dropdown:hover > a, 
.navbar-default .navbar-nav > li.dropdown:hover > a:hover,
.navbar-default .navbar-nav > li.dropdown:hover > a:focus {
   // background-color: rgb(231, 231, 231);
    color: rgb(85, 85, 85);
}
li.dropdown:hover > .dropdown-menu1 {
    display: block;
}

//.nav > li {
    position: relative;
    display: block;
    margin-inline: 88px;
}
.navbar-nav > li > .dropdown-menu1 {
    margin-top: -8px;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
    //margin-inline: -24px;
    margin-left:-14px;
}

.navbar-nav > li > .list{
    margin-top: -8px;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
    //margin-inline: -24px;
    margin-left:-54px;
}
.dropdown-menu1 {
    position: absolute;
    top: 100%;
    left: 20px;
    z-index: 1000;
    display:none;
    float: left;
    min-width: 0px;
    width:185px;
    padding: 5px 0;
    margin: 2px 0 0;
        margin-top: 2px;
    list-style: none;
    font-size: 14px;
    background-color: 
#eee;
border: 0px solid #ccc;
border: 0px solid
rgba(0,0,0,.15);
border-radius: 4px;
    border-top-left-radius: 4px;
    border-top-right-radius: 4px;
-webkit-box-shadow: 0 6px 12px rgba(0,0,0,.175);
box-shadow: 0 6px 12px
    rgba(0,0,0,.175);
    background-clip: padding-box;
}



.dropdown-menu1 > li > a {

    display: block;
    padding: 5px 15px;
    clear: both;
    font-weight: 400;
    line-height: 1.42857143;
    color: #d75656;
    white-space: nowrap;
    text-decoration: none;
    color: 

    #8f8f8f;
    font-weight: bold;

}
@media only screen and (min-width: 800px) and (max-width:10000px){
.open > .dropdown-menu1 {
    display: none;
	
}
}
@media only screen and (min-width: 200px) and (max-width:495px){
	.dropdown-menu1 {
    position: absolute;
    top: 100%;
    left: 0;
    z-index: 1000;
    display:block;
    float: left;
    min-width: 201px;
    padding: 5px 0;
    margin: 2px 0 0;
        margin-top: 2px;
    list-style: none;
    font-size: 14px;
    background-color: 
#eee;
border: 0px solid #ccc;
border: 0px solid
rgba(0,0,0,.15);
border-radius: 4px;
    border-top-left-radius: 4px;
    border-top-right-radius: 4px;
-webkit-box-shadow: 0 6px 12px rgba(0,0,0,.175);
box-shadow: 0 6px 12px
    rgba(0,0,0,.175);
    background-clip: padding-box;
	//margin-left:120px;


}



.navbar-fixed-top .navbar-collapse, .navbar-fixed-bottom .navbar-collapse {

    max-height: 456px;

}
.dropdown-toggle{
	//margin-left:100px;

}
.logout{
	margin-top:0px;
	padding-top:5px !important;
	font-weight:bold;
}
.navbar-nav > li > .list {
    margin-top: -8px;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
    //margin-inline: -24px;
    margin-left: -9px;
}
}

.navbar-default .navbar-nav > .open > a, .navbar-default .navbar-nav > .open > a:hover, .navbar-default .navbar-nav > .open > a:focus {
    background-color: 
transparent;
color:
    #555;
}

.navbar-nav.navbar-right:last-child {

    margin-right: 0px;

}
.dropdown-menu1 > li > a:hover {
	background-color: #ddd;
}

.navbar-default {

    background-color: white;

	border-color:#e7e7e7;

}

@media only screen and (min-width: 495px) and (max-width:767px){

.dropdown  {

    overflow-y: auto;
    height: 180px;
	//text-align:center;
}

.navbar-nav.navbar-right:last-child {

    margin-right:0px;

}

.navbar-nav .open .dropdown-menu1 {
    position: static;
    float: none;
    width: auto;
    margin-top: 0px;
    background-color: transparent;
    border: 0;
    -webkit-box-shadow: none;
    box-shadow: none;
    //background-color: 
    #f8f8f8;
    margin-left:11px;
}
.dropdown-menu1 {
    position: absolute;
    top: 100%;
    left: 0;
    z-index: 1000;
    display:block;
    float: left;
    min-width: 201px;
    padding: 5px 0;
    //margin: 2px 0 0;
        margin-top: 2px;
    list-style: none;
    font-size: 14px;
    background-color: #eee;
	border: 0px solid #ccc;
	border: 0px solid rgba(0,0,0,.15);
	border-radius: 4px;
   	 border-top-left-radius: 4px;
   	 border-top-right-radius: 4px;
	-webkit-box-shadow: 0 6px 12px rgba(0,0,0,.175);
	box-shadow: 0 6px 12px rgba(0,0,0,.175);
        background-clip: padding-box;
	margin-left:250px;
	
}
.dropdown-toggle{
	margin-left:250px;

}
.logout {

    margin-top: 0px;
    padding-top: 5px !important;
   // text-align: center;
    margin-left: 243px;
    font-weight:bold;

}
}

.menu
{
    height: 25px;
    background-color: #111111;
}

.menu a 
{
    color : gray;
    text-decoration : none;    
}

.menu a:hover
{
    color : white;
    text-decoration : none;    
}

.list
{
    list-style-type : none;
    padding:0;
}

.item
{
    position     : sticky;
    display      : inline-block;
    vertical-align : top;
    width        : 184px;   
}

.item>.list
{
    display : none;
    //background-color : #222222;
}

.item:hover>.list
{
    display : block;    
}

.list > li > a {

    display: block;
    padding: 5px 15px;
    clear: both;
    font-weight: 400;
    line-height: 1.42857143;
    color: #d75656;
    white-space: nowrap;
    text-decoration: none;
    color: #8f8f8f;
    font-weight: bold;

}

.list > li > a:hover{
	background-color : #ddd;
}

.nav-tabs > li {
    width: 48.33%;
}

</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


  <!--<nav class="navbar navbar-default">-->
  	<nav class="navbar navbar-default  navbar-fixed-top">
    <div class="container-fluid">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar2" >
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <div><a href="../index.php"><img  src="../user/user_logo/<?php echo trim($_SESSION['logo']); ?>" id="hp" alt="logo"/></a></div>
      </div>
      <div id="navbar2" class="navbar-collapse collapse ">
	<div style="width:100%;">
        <ul class="nav navbar-nav navbar-right">
          
          <li class="dropdown open " style="margin-left:15px ;margin-top:16px ;">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown open" role="button" aria-expanded="false">  <span class="glyphicon glyphicon-user"  style="font-size:12px;"></span> <?php if(isset($_SESSION['userName']) && $_SESSION['userName'] != ''){echo strtoupper($_SESSION['userName']);} ?> <i class="fa fa-angle-down"></i></span></a>
		
            <ul class="dropdown-menu1" role="menu1"><i style="font-size:24px;margin-left:94px;margin-top:-22px;color:#eee" class="fa">&#xf0d8;</i>
              <li><a href="guide.php">User Guide</a></li>
		
		
                <li><a href="gateways.php">Activity Dashboard</a></li>
               
                <li><a href="analytics.php">Analytics</a></li> 
		
		
		

		 <!-- <li><a href="map-studio.php">Map Center</a></li>  -->
         <li><a href="map-studio2.php">Map Center</a></li>   
		<li><a href="accelerations_types.php">Vibration Monitoring</a></li>
                              

   
        
                <li><a href="info.php">Info</a></li> 
		
		

               		 
             
            </ul>
          </li>
<li class="dropdown open " style="margin-left:10px ;margin-top:16px ;">
<a href="#" class="dropdown-toggle" data-toggle="dropdown open" role="button" aria-expanded="false"><span style="font-size:15px;margin-right:6px" class="glyphicon glyphicon-cog"></span>Settings</a>
<ul class="list dropdown-menu1" role="menu1"><i style="font-size:24px;margin-left:84px;margin-top:-17px;color:#eee" class="fa">&#xf0d8;</i>
	<li><a href="general_settings.php">General Settings</a></li> 
	<li><a href="device_settings.php">Device Settings</a></li>
	<li><a href="settings.php">Notification Settings</a></li> 
	<li><a href="add_contacts.php">Add Phone/Emails</a></li>	
    <li><a href="reports.php">Report Generation</a></li>    		  			  
</ul>
</li>

<li><a href="javascript:;" class="logout"><i style="font-size:15px;margin-right:6px" class="fa">&#xf011;</i>Logout</a></li>
<li style="margin-top:7px;margin-right:-64px;margin-left:15px"><span style="font-size:10px">Powered by</span></li>
<li style="margin-right:-20px;margin-top:8px"><a href="../index.php"><img width="55" height="36" src="../img/logo.png" alt="logo"/></a></li>
        </ul>
      </div>
</div>
      <!--/.nav-collapse -->


    <!-- === Low Battery Status === -->
    <div class="slideOut">
        <div class="slideOutTab" onclick="devicesLowBattery()">
            <div>
              <p>Low Battery COINs <span id="slide"><img id="imgCtrl" src="../img/arrow-down-wht.png" width="24px" height="22px"></span></p>
            </div>
        </div>
        <div class="modal-content">
        <!--<div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title">Help Tips</h4> </div>-->

            <div class="modal-body">
                <div class="col-xs-6">
                    <h3>Information </h3>
                    <p>COINs under low battery with respect to Gateway ID </p><br />
                    <!-- tabs -->
                    <div class="tabbable">
                        <ul class="nav nav-tabs text-center">
                            <li class="active"><a href="#home" data-toggle="tab" onclick="devicesLowBattery()">Low Battery COINs</a></li>
                            <!-- <li><a href="#about" data-toggle="tab">Gateway status</a></li> -->
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="home">                
                                 <div class="CoinLowbatteryStatus">   
                                        
                                </div>
                            </div> 
                            <div class="tab-pane" id="about"> 
                                <div class="">
                                    <!--<h1>About Tab</h1>--><br>
                                    <table class="table table-striped" style="width:100% !important">
                                        <tr>
                                            <td>Gateway Id</td>
                                            <td>Date</td>
                                            <td>Duration</td>
                                        </tr>
                                    </table>                 
                                </div>
                            </div>
                        
                        </div>
                    </div>
                    <!-- /tabs -->
                </div>

            <!--end of modal body-->
            </div>
            <div class="modal-footer"> </div>
        </div>
    </div>
    <!-- // Scroll content -->

    <!-- === Low Battery Ends === -->


    </div>
    <!--/.container-fluid -->
  </nav>

 

  <script>
$(document).ready(function() {
  $('[data-toggle=offcanvas]').click(function() {
    $('.row-offcanvas').toggleClass('active');
  });
});

    /* === Low Battery === */
        this.$slideOut = $('.slideOut');

        // Slideout show
        this.$slideOut.find('.slideOutTab').on('click', function() {
          $(".slideOut").toggleClass('showSlideOut');
        });

        //slide arrow toggle
        $(document).on("click", "#slide",function(e){
                    
            var imgSrc = $('#imgCtrl').attr('src');

            if(imgSrc=='../img/arrow-down-wht.png'){
                $('#imgCtrl').attr('src','../img/arrow-up-wht.png');
            }
            else{
                $('#imgCtrl').attr('src','../img/arrow-down-wht.png');
            }
                   
        }); 
    /* === Low Battery Ends === */
</script>


<?php 
    if(isset($_SESSION['userId']) && isset($_SESSION['apikey'])){
        echo '<input type="hidden"name="sesval" id="sesval" value="" data-uid="'.$_SESSION['userId'].'" data-key="'.$_SESSION['apikey'].'"  data-date_format="'.$_SESSION['date_format'].'"  data-rms_values="'.$_SESSION['rms_values'].'"  data-logo="'.$_SESSION['logo'].'"  data-temp_unit="'.$_SESSION['temp_unit'].'"/> ';
    }
?>
