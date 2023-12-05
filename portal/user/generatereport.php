<?php


// Include the main TCPDF library (search for installation path).
require_once('/var/www/html/sensegiz-dev/library/TCPDF/TCPDF-master/examples/tcpdf_include.php');
require_once('/var/www/html/sensegiz-dev/library/TCPDF/TCPDF-master/tcpdf.php');


require_once('/var/www/html/sensegiz-dev/src/config/config.php');
require_once('/var/www/html/sensegiz-dev/src/config/connect.php');
require_once('/var/www/html/sensegiz-dev/src/utils/ConnectionManager.php');
require_once('/var/www/html/sensegiz-dev/src/utils/GeneralMethod.php');
require_once('/var/www/html/sensegiz-dev/src/utils/CurlRequest.php');


$updated_on = 'UPDATED_ON';
$device_value = 'VALUE';
$low_threshold = 'LOW THRESHOLD';
$high_threshold = 'HIGH THRESHOLD';

$user_id = $_GET['user_id'];

class MYPDF extends TCPDF {

	public function Header() {

		$logoimg = $this->logoname;
		$logoformat = $this->logoformat;

		$image_file = '/var/www/html/sensegiz-dev/portal/user/user_logo/'.$logoimg;

		$this->Image($image_file, 85, 10, 35, 20, $logoformat, '', '', false, 300, '', false, false, 0, false, false, false);
	}

	// Page footer
    	public function Footer() {

		$footer_image_file = '/var/www/html/sensegiz-dev/portal/img/logo.png';
		$footer_logo_html = '<div style="width:100opx !important;height:100px;">powered by<img src="' . $footer_image_file . '" width:"40px;" height:"20px;"/></div>';		

		// get the current page break margin      
		$bMargin = $this->getBreakMargin();

        	// get current auto-page-break mode
		$auto_page_break = $this->AutoPageBreak;

		// disable auto-page-break
		$this->SetAutoPageBreak(false, 0);

		// write html image
		$this->writeHTML($footer_logo_html, true, 0, true, 0, 'R');

        	// restore auto-page-break status
		$this->SetAutoPageBreak($auto_page_break, $bMargin);

        	// set the starting point for the page content
		$this->setPageMark();		


    	}

	public function DrawHeader($header, $w){

		// Colors, line width and bold font
		$this->SetFillColor(74, 114, 243);
		$this->SetTextColor(255);
		$this->SetDrawColor(43, 89, 240);
		$this->SetLineWidth(0.3);
		$this->SetFont('', 'B');

		$num_headers = count($header);
		for($i = 0; $i < $num_headers; ++$i) {
			$this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
		}
		$this->Ln();

		// Color and font restoration
		$this->SetFillColor(224, 235, 255);
		$this->SetTextColor(0);
		$this->SetFont('');

	}


	public function ColoredTable($header,$data) {

		

		// Header
		
		$num_headers = count($header);
		if($num_headers == 2){
			$w = array(53, 35);
		}elseif($num_headers == 3){
			$w = array(53, 30, 48);
		}elseif($num_headers == 4){
			$w = array(53, 30, 48, 48);
		}else{
			$w = array(53, 30, 30, 30, 44);
		}


		$this->DrawHeader($header, $w);


		// Data
		$fill = 0;
		foreach($data as $row) {
			if($num_headers == 2){
				$this->Cell($w[0], 6, $row[0], 'LR', 0, 'L', $fill);
				$this->Cell($w[1], 6, $row[1], 'LR', 0, 'L', $fill);
				
			}
			elseif($num_headers == 3){
				$this->Cell($w[0], 6, $row[0], 'LR', 0, 'L', $fill);
				$this->Cell($w[1], 6, $row[1], 'LR', 0, 'L', $fill);
				$this->Cell($w[2], 6, $row[2], 'LR', 0, 'R', $fill);
				
			}elseif($num_headers == 4){
				$this->Cell($w[0], 6, $row[0], 'LR', 0, 'L', $fill);
				$this->Cell($w[1], 6, $row[1], 'LR', 0, 'L', $fill);
				$this->Cell($w[2], 6, $row[2], 'LR', 0, 'R', $fill);
				$this->Cell($w[3], 6, $row[3], 'LR', 0, 'R', $fill);				
			}
			else{
				$this->Cell($w[0], 6, $row[0], 'LR', 0, 'L', $fill);
				$this->Cell($w[1], 6, $row[1], 'LR', 0, 'L', $fill);
				$this->Cell($w[2], 6, $row[2], 'LR', 0, 'R', $fill);
				$this->Cell($w[3], 6, $row[3], 'LR', 0, 'R', $fill);
				$this->Cell($w[4], 6, $row[4], 'LR', 0, 'R', $fill);				
				
			}
			$this->Ln();
			$fill=!$fill;
			
		}
		$this->Cell(array_sum($w), 0, '', 'T');
	}

}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('PDF Export');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
//$pdf->setFooterData(array(0,64,0), array(0,64,128));

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP+10, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
//$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->setImageScale(1.53);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

if (@file_exists(dirname(__FILE__).'/lang/jpn.php')) {
	require_once(dirname(__FILE__).'/lang/jpn.php');
	$pdf->setLanguageArray($l);
}


// set default font subsetting mode
$pdf->setFontSubsetting(true);
$pdf->setCellPadding(0);







$db = new ConnectionManager();

	$sQuery = "SELECT *"
		." FROM daily_report WHERE send_report='Y' AND user_id = $user_id";	

	$db->query($sQuery);
	$res = $db->resultSet();


	if(is_array($res) && !empty($res)){
        	foreach ($res as $key => $value) {

			$count = 0;

			$userID = $value[user_id];

			//Get User Details
			$userLogo = getUserDetails($db, $userID);
			$logoimg = rtrim(ltrim($userLogo[logo]));
			$logoformat = substr($logoimg, strpos($logoimg, ".") + 1);

			$pdf->logoname = $logoimg;
			$pdf->logoformat = $logoformat;


			// Add a page
			$pdf->AddPage();


// Set some content to print
$html = <<<EOD
<h2>Daily Report</h2>
EOD;

$br = <<<EOD
<br/>
EOD;


			// Print text using writeHTMLCell()
			$pdf->writeHTMLCell(0, 10, '', '', $html, 0, 1, 0, true, '', true);


			$acc = $value[accelerometer];
			$gyro = $value[gyroscope];
			$temp = $value[temperature];
			$humid = $value[humidity];
			$tempstream = $value[temperaturestream];
			$humidstream = $value[humiditystream];

			if($acc == 'Y'){
				$sen = " Accelerometer";
				$sen .= ",";
			}
			if($gyro == 'Y'){
				$sen .= " Gyroscope";
				$sen .= ",";
			}
			if($temp == 'Y'){
				$sen = " Temperature";
				$sen .= ",";
			}
			if($humid == 'Y'){
				$sen .= " Humidity";
				$sen .= ",";
			}
			if($tempstream == 'Y'){
				$sen .= " Temperature Stream";
				$sen .= ",";
			}
			if($humidstream == 'Y'){
				$sen = " Humidty Stream";
				$sen .= ",";
			}

			$sen = rtrim($sen, ',');


			$startTime = $value[report_start_time];
			$endTime = $value[report_end_time];

			$timezone = $value[timezone];

			$zonename = getZoneName($db, $timezone);
			$zone = $zonename[name];


			$interval = (new DateTime($startTime))->diff(new DateTime($endTime))->days;
			
			$t = explode(":",$timezone); 
			$h = ltrim($t[0],$timezone[0]);
			$m = $t[1];

		
			if($timezone[0] == '+'){
				$start = (new DateTime($startTime));
				$start->add(new DateInterval('PT' .$h . 'H' . $m . 'M'));
				$s = $start->format('H:i');
				echo $s;
			}
			if($timezone[0] == '-'){
				$start = (new DateTime($startTime));
				$start->sub(new DateInterval('PT' .$h . 'H' . $m . 'M'));
				$s = $start->format('H:i');
				echo $s;
			}

// set some text to print
$txt = <<<EOD
Sensors Selected for Report : $sen <br/>
Report Duration : $interval days <br/> 
Report Start Time : $s <br/> <br/>
EOD;


			$pdf->writeHTMLCell(0, 10, '', '', $txt, 0, 1, 0, true, '', true);

			
			//Get User Details
			$userDetails = getUserDetails($db, $userID);
			$tempUnit = $userDetails[temp_unit];


			//Get gateways
			$userGateways  =  getUserGateways($db,$userID);

			$totalGateways=count($userGateways);


			$newStart = date('Y-m-d H:i:s', strtotime($startTime . ' +1 day'));
			$newEnd = date('Y-m-d H:i:s', strtotime($endTime . ' +1 day'));


			for($x=0; $x<$totalGateways; $x++){
				
				$gateway_id = $userGateways[$x];

				$coins = getUserCoins($db, $gateway_id);

				$totalcoins=count($coins);
				
				for($y=0; $y<$totalcoins; $y++){
					
					$device_id = $coins[$y];	

					$coinname = getCoinNickName($db, $gateway_id, $device_id);
			
					$nickname = $coinname[nick_name];
					
					if($acc == 'Y'){

						$graphimage = $gateway_id . '_' . $device_id . '_' . '01';
						$varName = $userID . '_' . $gateway_id. "_accres" . $device_id;						

						$lthres = "(CASE WHEN ('x'||lpad(low_threshold,16,'0'))::bit(64)::bigint =1 THEN '0.001' WHEN ('x'||lpad(low_threshold,16,'0'))::bit(64)::bigint =2 THEN '0.1' WHEN ('x'||lpad(low_threshold,16,'0'))::bit(64)::bigint >=3 THEN (('x'||lpad(low_threshold,16,'0'))::bit(64)::bigint)/8::float END)";
						$hthres = "(CASE WHEN ('x'||lpad(high_threshold,16,'0'))::bit(64)::bigint =1 THEN '0.001' WHEN ('x'||lpad(high_threshold,16,'0'))::bit(64)::bigint =2 THEN '0.1' WHEN ('x'||lpad(high_threshold,16,'0'))::bit(64)::bigint >=3 THEN (('x'||lpad(high_threshold,16,'0'))::bit(64)::bigint)/8::float END)";

						$accSQL = "SELECT (updated_on at time zone '$zone') AS updated_on, device_value, $lthres AS low,  $hthres AS high FROM threshold WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type IN ('01', '02') AND updated_on BETWEEN '$startTime' AND '$endTime' ORDER BY updated_on DESC";
						$db->query($accSQL);
						$$varName = $db->resultSet();

						
						if(is_array($$varName) && !empty($$varName)){

							$count = $count + 1;

							$pdf->Write(5, 'Gateway ID- '.$gateway_id.' Coin ID- '. $device_id. ' Coin Name- '. $nickname, '', 0, '', false, 0, false, false, 0);

							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);
							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);

							$pdf->Write(5, 'Accelerometer', '', 0, '', false, 0, false, false, 0);
							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);
											
							$pdf->writeHTML('<img src="/var/www/html/sensegiz-dev/portal/user/daily_report/'.$graphimage.'.png" width="600" height="230" />', true, 0, true, 0);

							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);
							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);

							if($count%2 == 0){
								$pdf->AddPage();
							}												


						}
						
						

					}

					if($gyro == 'Y'){
						$graphimage = $gateway_id . '_' . $device_id . '_' . '03';
						$varName = $userID . '_' . $gateway_id. "_gyrores" . $device_id;						

						$lthres = "(('x'||lpad(low_threshold,16,'0'))::bit(64)::bigint)*10";
						$hthres = "(('x'||lpad(high_threshold,16,'0'))::bit(64)::bigint)*10";

						$gyroSQL = "SELECT (updated_on at time zone '$zone') AS updated_on , device_value, $lthres AS low,  $hthres AS high FROM threshold WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type IN ('03', '04') AND updated_on BETWEEN '$startTime' AND '$endTime' ORDER BY updated_on DESC";
						$db->query($gyroSQL);
						$$varName = $db->resultSet();

						$head = array ($updated_on,  $device_value, $low_threshold, $high_threshold);
						$data = array();
						

						if(is_array($$varName) && !empty($$varName)){

							$count = $count + 1;

							$pdf->Write(5, 'Gateway ID- '.$gateway_id.' Coin ID- '. $device_id. ' Coin Name- '. $nickname, '', 0, '', false, 0, false, false, 0);

        						
							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);
							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);

							$pdf->Write(5, 'Gyroscope', '', 0, '', false, 0, false, false, 0);
							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);

						
							$pdf->writeHTML('<img src="/var/www/html/sensegiz-dev/portal/user/daily_report/'.$graphimage.'.png" width="600" height="230" />', true, 0, true, 0);

							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);
							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);

							if($count%2 == 0){
								$pdf->AddPage();
							}
						}
						
						

					}

					if($temp == 'Y'){
						$graphimage = $gateway_id . '_' . $device_id . '_' . '05';
						$varName = $userID . '_' . $gateway_id. "_tempres" . $device_id;						

						$lthres = "CASE WHEN ('x'||lpad(low_threshold,16,'0'))::bit(64)::bigint > 126 THEN -((('x'||lpad(low_threshold,16,'0'))::bit(64)::bigint)-126) WHEN ('x'||lpad(low_threshold,16,'0'))::bit(64)::bigint = 126 THEN 0 ELSE ('x'||lpad(low_threshold,16,'0'))::bit(64)::bigint END";
						$hthres = "CASE WHEN ('x'||lpad(high_threshold,16,'0'))::bit(64)::bigint > 126 THEN -((('x'||lpad(high_threshold,16,'0'))::bit(64)::bigint)-126) WHEN ('x'||lpad(high_threshold,16,'0'))::bit(64)::bigint = 126 THEN 0 ELSE ('x'||lpad(high_threshold,16,'0'))::bit(64)::bigint END";

						if($tempUnit == 'Fahrenheit'){
							$dval = "(CAST(device_value AS DOUBLE PRECISION)*1.8)+32";
						}else{

							$dval = "device_value";
						}

						$tempSQL = "SELECT (updated_on at time zone '$zone') AS updated_on , $dval AS device_value, $lthres AS low,  $hthres AS high FROM threshold WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type IN ('05', '06') AND updated_on BETWEEN '$startTime' AND '$endTime' ORDER BY updated_on DESC";
						$db->query($tempSQL);
						$$varName = $db->resultSet();

						$head = array ($updated_on,  $device_value, $low_threshold, $high_threshold);
						$data = array();
						

						if(is_array($$varName) && !empty($$varName)){

							$count = $count + 1;

							$pdf->Write(5, 'Gateway ID- '.$gateway_id.' Coin ID- '. $device_id. ' Coin Name- '. $nickname, '', 0, '', false, 0, false, false, 0);

        						
							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);
							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);

							$pdf->Write(5, 'Temperature', '', 0, '', false, 0, false, false, 0);
							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);

						
							$pdf->writeHTML('<img src="/var/www/html/sensegiz-dev/portal/user/daily_report/'.$graphimage.'.png" width="600" height="230" />', true, 0, true, 0);

							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);
							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);

							if($count%2 == 0){
								$pdf->AddPage();
							}
						}
						
						

					}

	
					if($humid == 'Y'){
						$graphimage = $gateway_id . '_' . $device_id . '_' . '07';
						$varName = $userID . '_' . $gateway_id. "_humidres" . $device_id;						

						$lthres = "('x'||lpad(low_threshold,16,'0'))::bit(64)::bigint";
						$hthres = "('x'||lpad(high_threshold,16,'0'))::bit(64)::bigint";

						$humidSQL = "SELECT (updated_on at time zone '$zone') AS updated_on , device_value, $lthres AS low,  $hthres AS high FROM threshold WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type IN ('07', '08') AND updated_on BETWEEN '$startTime' AND '$endTime' ORDER BY updated_on DESC";
						$db->query($humidSQL);
						$$varName = $db->resultSet();

						$head = array ($updated_on,  $device_value, $low_threshold, $high_threshold);
						$data = array();
						

						if(is_array($$varName) && !empty($$varName)){

							$count = $count + 1;

							$pdf->Write(5, 'Gateway ID- '.$gateway_id.' Coin ID- '. $device_id. ' Coin Name- '. $nickname, '', 0, '', false, 0, false, false, 0);

        						
							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);
							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);

							$pdf->Write(5, 'Humidity', '', 0, '', false, 0, false, false, 0);
							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);

						
							$pdf->writeHTML('<img src="/var/www/html/sensegiz-dev/portal/user/daily_report/'.$graphimage.'.png" width="600" height="230" />', true, 0, true, 0);

							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);
							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);

							if($count%2 == 0){
								$pdf->AddPage();
							}
						}
						
						

					}


		
					if($tempstream == 'Y'){
						$graphimage = $gateway_id . '_' . $device_id . '_' . '09';
						$varName = $userID . '_' . $gateway_id. "_tempstrmres" . $device_id;

						if($tempUnit == 'Fahrenheit'){
							$dval = "(CAST(device_value AS DOUBLE PRECISION)*1.8)+32";
						}else{

							$dval = "device_value";
						}						

						$tempSQL = "SELECT (updated_on at time zone '$zone') AS updated_on, $dval AS device_value FROM devices_stream WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type='09' AND updated_on BETWEEN '$startTime' AND '$endTime' ORDER BY updated_on DESC";		
						$db->query($tempSQL);
						$$varName = $db->resultSet();

						$head = array ($updated_on,  $device_value);
						$data = array();


						if(is_array($$varName) && !empty($$varName)){
							$count = $count + 1;
							$pdf->Write(5, 'Gateway ID- '.$gateway_id.' Coin ID- '. $device_id. ' Coin Name- '. $nickname, '', 0, '', false, 0, false, false, 0);

        						
							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);
							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);

							$pdf->Write(5, 'Temperature Stream', '', 0, '', false, 0, false, false, 0);
							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);

																			
							$pdf->writeHTML('<img src="/var/www/html/sensegiz-dev/portal/user/daily_report/'.$graphimage.'.png" width="600" height="230" />', true, 0, true, 0);
							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);
							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);
							
							if($count%2 == 0){
								$pdf->AddPage();
							}

						}
						
												

					}

					if($humidstream == 'Y'){
						$graphimage = $gateway_id . '_' . $device_id . '_' . '10';
						$varName = $userID . '_' . $gateway_id. "_humidstrmres" . $device_id;

						$humidSQL = "SELECT (updated_on at time zone '$zone') AS updated_on, device_value FROM devices_stream WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type='10' AND updated_on BETWEEN '$startTime' AND '$endTime' ORDER BY updated_on DESC";		
						$db->query($humidSQL);
						$$varName = $db->resultSet();

						$head = array ($updated_on,  $device_value);
						$data = array();


						if(is_array($$varName) && !empty($$varName)){

							$count = $count + 1;

							$pdf->Write(5, 'Gateway ID- '.$gateway_id.' Coin ID- '. $device_id. ' Coin Name- '. $nickname, '', 0, '', false, 0, false, false, 0);

        						foreach ($$varName as $key => $value) {

								$upd = $value[updated_on];
								$dv = $value[device_value];

								$data[] = array($upd, $dv);
							}

							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);
							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);

							$pdf->Write(5, 'Humidity Stream', '', 0, '', false, 0, false, false, 0);
							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);

							$pdf->writeHTML('<img src="/var/www/html/sensegiz-dev/portal/user/daily_report/'.$graphimage.'.png" width="600" height="230" />', true, 0, true, 0);

							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);
							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);

							
							if($count%2 == 0){
								$pdf->AddPage();
							}
						

						}
						
						
					}
					
				}

				
				for($y=0; $y<$totalcoins; $y++){
					
					$device_id = $coins[$y];

					$coinname = getCoinNickName($db, $gateway_id, $device_id);
			
					$nickname = $coinname[nick_name];					

					if($acc == 'Y'){
	
						$varName = $userID . '_' . $gateway_id. "_accres" . $device_id;						

						$lthres = "(CASE WHEN ('x'||lpad(low_threshold,16,'0'))::bit(64)::bigint =1 THEN '0.001' WHEN ('x'||lpad(low_threshold,16,'0'))::bit(64)::bigint =2 THEN '0.1' WHEN ('x'||lpad(low_threshold,16,'0'))::bit(64)::bigint >=3 THEN (('x'||lpad(low_threshold,16,'0'))::bit(64)::bigint)/8::float END)";
						$hthres = "(CASE WHEN ('x'||lpad(high_threshold,16,'0'))::bit(64)::bigint =1 THEN '0.001' WHEN ('x'||lpad(high_threshold,16,'0'))::bit(64)::bigint =2 THEN '0.1' WHEN ('x'||lpad(high_threshold,16,'0'))::bit(64)::bigint >=3 THEN (('x'||lpad(high_threshold,16,'0'))::bit(64)::bigint)/8::float END)";

						$accSQL = "SELECT (updated_on at time zone '$zone') AS updated_on, device_value, $lthres AS low,  $hthres AS high FROM threshold WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type IN ('01', '02') AND updated_on BETWEEN '$startTime' AND '$endTime' ORDER BY updated_on DESC";
						$db->query($accSQL);
						$$varName = $db->resultSet();

						$head = array ($updated_on,  $device_value, $low_threshold, $high_threshold);
						$data = array();


						if(is_array($$varName) && !empty($$varName)){

							$pdf->Write(5, 'Gateway ID- '.$gateway_id.' Coin ID- '. $device_id. ' Coin Name- '. $nickname, '', 0, '', false, 0, false, false, 0);


        						foreach ($$varName as $key => $value) {

								$upd = $value[updated_on];
								$dv = $value[device_value];
								$lth = $value[low];
								$hth = $value[high];

								$data[] = array($upd, $dv, $lth, $hth);
							}

							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);
							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);

							$pdf->Write(5, 'Accelerometer', '', 0, '', false, 0, false, false, 0);
							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);

						
							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);

							$pdf->ColoredTable($head, $data);
							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);
							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);

						
						}
						
						

					}

					if($gyro == 'Y'){

						$varName = $userID . '_' . $gateway_id. "_gyrores" . $device_id;						

						$lthres = "(('x'||lpad(low_threshold,16,'0'))::bit(64)::bigint)*10";
						$hthres = "(('x'||lpad(high_threshold,16,'0'))::bit(64)::bigint)*10";

						$gyroSQL = "SELECT (updated_on at time zone '$zone') AS updated_on , device_value, $lthres AS low,  $hthres AS high FROM threshold WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type IN ('03', '04') AND updated_on BETWEEN '$startTime' AND '$endTime' ORDER BY updated_on DESC";
						$db->query($gyroSQL);
						$$varName = $db->resultSet();

						$head = array ($updated_on,  $device_value, $low_threshold, $high_threshold);
						$data = array();
						

						if(is_array($$varName) && !empty($$varName)){

							$pdf->Write(5, 'Gateway ID- '.$gateway_id.' Coin ID- '. $device_id. ' Coin Name- '. $nickname, '', 0, '', false, 0, false, false, 0);

        						foreach ($$varName as $key => $value) {

								$upd = $value[updated_on];
								$dv = $value[device_value];
								$lth = $value[low];
								$hth = $value[high];

								$data[] = array($upd, $dv, $lth, $hth);
							}

							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);
							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);

							$pdf->Write(5, 'Gyroscope', '', 0, '', false, 0, false, false, 0);
							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);

						
							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);

							$pdf->ColoredTable($head, $data);
							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);
							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);
						
						}
						
						

					}

					if($temp == 'Y'){

						$varName = $userID . '_' . $gateway_id. "_tempres" . $device_id;						

						$lthres = "CASE WHEN ('x'||lpad(low_threshold,16,'0'))::bit(64)::bigint > 126 THEN -((('x'||lpad(low_threshold,16,'0'))::bit(64)::bigint)-126) WHEN ('x'||lpad(low_threshold,16,'0'))::bit(64)::bigint = 126 THEN 0 ELSE ('x'||lpad(low_threshold,16,'0'))::bit(64)::bigint END";
						$hthres = "CASE WHEN ('x'||lpad(high_threshold,16,'0'))::bit(64)::bigint > 126 THEN -((('x'||lpad(high_threshold,16,'0'))::bit(64)::bigint)-126) WHEN ('x'||lpad(high_threshold,16,'0'))::bit(64)::bigint = 126 THEN 0 ELSE ('x'||lpad(high_threshold,16,'0'))::bit(64)::bigint END";

						if($tempUnit == 'Fahrenheit'){
							$dval = "(CAST(device_value AS DOUBLE PRECISION)*1.8)+32";
						}else{

							$dval = "device_value";
						}

						$tempSQL = "SELECT (updated_on at time zone '$zone') AS updated_on , $dval AS device_value, $lthres AS low,  $hthres AS high FROM threshold WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type IN ('05', '06') AND updated_on BETWEEN '$startTime' AND '$endTime' ORDER BY updated_on DESC";
						$db->query($tempSQL);
						$$varName = $db->resultSet();

						$head = array ($updated_on,  $device_value, $low_threshold, $high_threshold);
						$data = array();
						

						if(is_array($$varName) && !empty($$varName)){

							$pdf->Write(5, 'Gateway ID- '.$gateway_id.' Coin ID- '. $device_id. ' Coin Name- '. $nickname, '', 0, '', false, 0, false, false, 0);

        						foreach ($$varName as $key => $value) {

								$upd = $value[updated_on];
								$dv = $value[device_value];
								$lth = $value[low];
								$hth = $value[high];

								$data[] = array($upd, $dv, $lth, $hth);
							}

							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);
							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);

							$pdf->Write(5, 'Temperature', '', 0, '', false, 0, false, false, 0);
							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);

						
							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);

							$pdf->ColoredTable($head, $data);
							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);
							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);
						
						}
						
						

					}

	
					if($humid == 'Y'){

						$varName = $userID . '_' . $gateway_id. "_humidres" . $device_id;						

						$lthres = "('x'||lpad(low_threshold,16,'0'))::bit(64)::bigint";
						$hthres = "('x'||lpad(high_threshold,16,'0'))::bit(64)::bigint";

						$humidSQL = "SELECT (updated_on at time zone '$zone') AS updated_on , device_value, $lthres AS low,  $hthres AS high FROM threshold WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type IN ('07', '08') AND updated_on BETWEEN '$startTime' AND '$endTime' ORDER BY updated_on DESC";
						$db->query($humidSQL);
						$$varName = $db->resultSet();

						$head = array ($updated_on,  $device_value, $low_threshold, $high_threshold);
						$data = array();
						

						if(is_array($$varName) && !empty($$varName)){

							$pdf->Write(5, 'Gateway ID- '.$gateway_id.' Coin ID- '. $device_id. ' Coin Name- '. $nickname, '', 0, '', false, 0, false, false, 0);

        						foreach ($$varName as $key => $value) {

								$upd = $value[updated_on];
								$dv = $value[device_value];
								$lth = $value[low];
								$hth = $value[high];

								$data[] = array($upd, $dv, $lth, $hth);
							}

							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);
							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);

							$pdf->Write(5, 'Humidity', '', 0, '', false, 0, false, false, 0);
							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);

						
							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);

							$pdf->ColoredTable($head, $data);
							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);
							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);
						

						}
						
						

					}


		
					if($tempstream == 'Y'){
						$graphimage = $gateway_id . '_' . $device_id . '_' . '09';
						$varName = $userID . '_' . $gateway_id. "_tempstrmres" . $device_id;

						if($tempUnit == 'Fahrenheit'){
							$dval = "(CAST(device_value AS DOUBLE PRECISION)*1.8)+32";
						}else{

							$dval = "device_value";
						}						

						$tempSQL = "SELECT (updated_on at time zone '$zone') AS updated_on, $dval AS device_value FROM devices_stream WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type='09' AND updated_on BETWEEN '$startTime' AND '$endTime' ORDER BY updated_on DESC";		
						$db->query($tempSQL);
						$$varName = $db->resultSet();

						$head = array ($updated_on,  $device_value);
						$data = array();


						if(is_array($$varName) && !empty($$varName)){

							$pdf->Write(5, 'Gateway ID- '.$gateway_id.' Coin ID- '. $device_id. ' Coin Name- '. $nickname, '', 0, '', false, 0, false, false, 0);

        						foreach ($$varName as $key => $value) {

								$upd = $value[updated_on];
								$dv = $value[device_value];


								$data[] = array($upd, $dv);
							}

							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);
							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);

							$pdf->Write(5, 'Temperature Stream', '', 0, '', false, 0, false, false, 0);
							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);

						
							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);

							$pdf->ColoredTable($head, $data);
							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);
							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);
						

						}
						
						
						

					}

					if($humidstream == 'Y'){

						$graphimage = $gateway_id . '_' . $device_id . '_' . '10';
						$varName = $userID . '_' . $gateway_id. "_humidstrmres" . $device_id;

						$humidSQL = "SELECT (updated_on at time zone '$zone') AS updated_on, device_value FROM devices_stream WHERE gateway_id='$gateway_id' AND device_id='$device_id' AND device_type='10' AND updated_on BETWEEN '$startTime' AND '$endTime' ORDER BY updated_on DESC";		
						$db->query($humidSQL);
						$$varName = $db->resultSet();

						$head = array ($updated_on,  $device_value);
						$data = array();


						if(is_array($$varName) && !empty($$varName)){

							$pdf->Write(5, 'Gateway ID- '.$gateway_id.' Coin ID- '. $device_id. ' Coin Name- '. $nickname, '', 0, '', false, 0, false, false, 0);

        						foreach ($$varName as $key => $value) {

								$upd = $value[updated_on];
								$dv = $value[device_value];

								$data[] = array($upd, $dv);
							}

							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);
							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);
							$pdf->Write(5, 'Humidity Stream', '', 0, '', false, 0, false, false, 0);
							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);
							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);

							$pdf->ColoredTable($head, $data);
							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);
							$pdf->writeHTMLCell(0, 5, '', '', $br, 0, 1, 0, true, '', true);
						

						}
						
						
					}
					
					
				}
				
			}

			$filename= $userID . "_DailyReport.pdf"; 
			
			ob_clean();
			$pdf->Output('/var/www/html/sensegiz-dev/portal/user/daily_report/'.$filename, 'F');

			$userMailArr = getUserEmailIds($db, $userID);  
			$subject     =  'SenseGiz Daily Report';  
			$messageEmail = '<html>
					<head><title>Alert</title></head>
					<body>
						<h4>Please find attached the daily report from SenseGiz.</h4>
					</body>
					</html>';
  			
			if(!empty($userMailArr)){
				sendMails($userID, $userMailArr, $subject, $messageEmail);
	
			}
							
	
		}
	}


function getZoneName($db, $zone){
                
        $sQuery = "SELECT name FROM pg_timezone_names WHERE utc_offset = '$zone' limit 1";       
         
        $db->query($sQuery);
        
       $row = $db->single();

	return $row;
       
}


function getUserDetails($db, $userId){
                
        $sQuery = "SELECT * FROM users WHERE user_id=:user_id";       
         
        $db->query($sQuery);
        $db->bind(':user_id',$userId);
        
       	$row = $db->single();

	return $row;
       
}



function getUserCoins($db, $gateway){
                
        $sQuery = " SELECT device_id"
                . " FROM gateway_devices"                    
                . " WHERE gateway_id=:gateway_id AND is_deleted =0 ORDER BY device_id";       
         
        $db->query($sQuery);
        $db->bind(':gateway_id',$gateway);
        
       $row = $db->resultSet();


       $resArray = [];
       if(is_array($row) && !empty($row)){
           foreach ($row as $key => $value) {
               $resArray[] = $value['device_id'];
           }
       }

        return $resArray;
}

function getCoinNickName($db, $gateway_id, $device_id){

	$sQuery = "SELECT nick_name FROM gateway_devices WHERE gateway_id=:gateway_id AND device_id=:device_id AND is_deleted =0 limit 1";       
         
        $db->query($sQuery);
        $db->bind(':gateway_id',$gateway_id);
        $db->bind(':device_id',$device_id);
        
	$row = $db->single();

	return $row;

}

function getUserGateways($db,$userId){
                
        $sQuery = " SELECT gateway_id"
                . " FROM user_gateways"                    
                . " WHERE user_id=:user_id AND is_deleted =0";       
         
        $db->query($sQuery);
        $db->bind(':user_id',$userId);
        
       $row = $db->resultSet();


       $resArray = [];
       if(is_array($row) && !empty($row)){
           foreach ($row as $key => $value) {
               $resArray[] = $value['gateway_id'];
           }
       }

        return $resArray;
}

function getUserEmailIds($db,$userId){
                
        $sQuery = " SELECT notification_email"
                . " FROM notification_emails"                    
                . " WHERE user_id=:user_id AND is_deleted =0";       
         
        $db->query($sQuery);
        $db->bind(':user_id',$userId);
        
       $row = $db->resultSet(); 

       $resArray = [];
       if(is_array($row) && !empty($row)){
           foreach ($row as $key => $value) {
               $resArray[] = $value['notification_email'];
           }
       }
        return $resArray;
}

function sendMails($userID, $emailsArray, $subject, $message){

	$mailidslength=count($emailsArray);

	$filename= 'portal/user/daily_report/' . $userID . "_DailyReport.pdf"; 

		$message = urlencode($message);
		$subject = urlencode($subject);

	for($x=0;$x<$mailidslength;$x++){
	
		$ch=curl_init();

		curl_setopt($ch,CURLOPT_URL,"http://".SERVER_IP.":3000/sesattach/?message=".$message."&to=".$emailsArray[$x]."&subject=".$subject."&file=".$filename."");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$output =curl_exec($ch);
		curl_close($ch);
	} 
}


?>