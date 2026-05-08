<?php
$encryptionKey = "ADAS*@$!2011";

function Convert($str, $ky = '')
{
    if ($ky == '')
        return $str;

    $ky = str_replace(chr(32), '', $ky);

    if (strlen($ky) < 8)
        exit('key error');

    $kl = strlen($ky) < 32 ? strlen($ky) : 32;

    $k = array();
    for ($i = 0; $i < $kl; $i++) {
        $k[$i] = ord($ky[$i]) & 0x1F;
    }

    $j = 0;
    for ($i = 0; $i < strlen($str); $i++) {
        $e = ord($str[$i]);
        $str[$i] = $e & 0xE0 ? chr($e ^ $k[$j]) : chr($e);
        $j++;
        $j = $j == $kl ? 0 : $j;
    }

    return $str;
}
function EncryptURL($plainText)
{        
	  // return base64_encode(Convert($plainText, "ADAS*@$!2011"));
	   return strtr(base64_encode(addslashes(gzcompress(serialize($plainText),9))), '+/=', '-_,');
	   
}
function DecryptURL($encryptedString)
{
	   // return Convert(base64_decode($encryptedString), "ADAS*@$!2011");
	   
	   return unserialize(gzuncompress(stripslashes(base64_decode(strtr($encryptedString, '-_,', '+/=')))));
}

function random_strings($length_of_string) 
{ 
  
    $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'; 
    return substr(str_shuffle($str_result),0, $length_of_string); 
} 

function CalculateTimeDiff($t1,$t2)
{
	$a1 = explode(":",$t1);
	$a2 = explode(":",$t2);
	$time1 = (($a1[0]*60*60)+($a1[1]*60)+($a1[2]));
	$time2 = (($a2[0]*60*60)+($a2[1]*60)+($a2[2]));
	$diff = abs($time1-$time2);
	$hours = floor($diff/(60*60));
	$mins = floor(($diff-($hours*60*60))/(60));
	$secs = floor(($diff-(($hours*60*60)+($mins*60))));
	$result = $hours.":".$mins.":".$secs;
	//$result = $hours." Hours, ".$mins." minutes";
	return $result;
}

function CalculateTimeAdd($t1,$t2)
{
	$a1 = explode(":",$t1);
	$a2 = explode(":",$t2);
	$time1 = (($a1[0]*60*60)+($a1[1]*60)+($a1[2]));
	$time2 = (($a2[0]*60*60)+($a2[1]*60)+($a2[2]));
	$add = abs($time1+$time2);
	$hours = floor($add/(60*60));
	$mins = floor(($add-($hours*60*60))/(60));
	$secs = floor(($add-(($hours*60*60)+($mins*60))));
	$result = $hours.":".$mins;
	return $result;
}

function GetNoOfDaysbetweenDates($toDate, $fromDate)
{
	return (((strtotime($toDate) - strtotime($fromDate) ) / (60 * 60 * 24)) + 1);
}

function calculateAverageTime($t,$p)
{
	$add = abs($t / $p);
	$hours = floor($add/(60));
	$mins = round(($add-($hours*60))/(60));
	//$secs = floor(($add-(($hours*60*60)+($mins*60))));
	$result = $hours.":".$mins;

	return $result;
}

function calculateAverageTimeClock($t,$p)
{
	$a1 = explode(":",$t);
	$time1 = (($a1[0]*60*60)+($a1[1]*60)+($a1[2]));
	$add = abs($time1 / $p);
	$hours = floor($add/(60*60));
	$mins = floor(($add-($hours*60*60))/(60));
	$secs = floor(($add-(($hours*60*60)+($mins*60))));
	$result = $hours.":".$mins;

	return $result;
}

function encode($str)
{
  for($i=0; $i<11;$i++)
  {
    $str=strrev(base64_encode($str)); //apply base64 first and then reverse the string
  }
  return $str;
}

//function to decrypt the string
function decode($str)
{
  for($i=0; $i<11;$i++)
  {
    $str=base64_decode(strrev($str)); //apply base64 first and then reverse the string}
  }
  return $str;
}

function GetNoOfDaysInMonth($month, $year)
{
   return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year %400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
}

function GetPermissions($moduleId)
{
	$URL = $_SERVER['PHP_SELF'];
	$folders = explode('/', $URL);
	$CURRENT_URL = $folders[count($folders) - 1] ;

	$arrPerms = ($_SESSION["_PERMISSIONS_"][$moduleId]);
	$PagePerm = array();
	if(is_array($arrPerms))
	{
		foreach($arrPerms as $objPerm)
		{
			$menuLink = $objPerm["ADAS_ERP_MODULE_MENU_LINK_URL"];
			list($folder, $url) = explode('/', $menuLink);
			if($url == $CURRENT_URL)
			{
				$PagePerm[$objPerm["BUTTON_NAME"]] =  $objPerm["ADAS_ERP_BUTTONS_ID"];
			}
		}
	}
	else
	{
		echo 'Permission Denied.';
		exit();
	}
	return $PagePerm;
}

function calculateAge($birthday)// this function will return age in year
{
    list($year,$month,$day) = explode("-",$birthday);
    $year_diff  = date("Y") - $year;
    $month_diff = date("m") - $month;
    $day_diff   = date("d") - $day;
    if ($day_diff < 0 || $month_diff < 0)
      $year_diff--;
    return $year_diff;     
}	
//function to convert number to words. eg 1560 to One Thousane Five Hundred Sixty .........DEBA 27May2010
function convert_number($number) 
{ 
    if (($number < 0) || ($number > 999999999)) 
    { 
    	throw new Exception("Number is out of range");
    } 

    $Gn = floor($number / 1000000);  /* Millions (giga) */ 
    $number -= $Gn * 1000000; 
    $kn = floor($number / 1000);     /* Thousands (kilo) */ 
    $number -= $kn * 1000; 
    $Hn = floor($number / 100);      /* Hundreds (hecto) */ 
    $number -= $Hn * 100; 
    $Dn = floor($number / 10);       /* Tens (deca) */ 
    $n = $number % 10;               /* Ones */ 
    $res = ""; 
    if ($Gn) 
    { 
        $res .= convert_number($Gn) . " Million"; 
    } 

    if ($kn) 
    { 
        $res .= (empty($res) ? "" : " ") . 
            convert_number($kn) . " Thousand"; 
    } 

    if ($Hn) 
    { 
        $res .= (empty($res) ? "" : " ") . 
            convert_number($Hn) . " Hundred"; 
    } 
    $ones = array("", "One", "Two", "Three", "Four", "Five", "Six", 
        "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", 
        "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen", 
        "Nineteen"); 
    $tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty", 
        "Seventy", "Eigthy", "Ninety"); 

    if ($Dn || $n) 
    { 
        if (!empty($res)) 
        { 
            $res .= " and "; 
        } 

        if ($Dn < 2) 
        { 
            $res .= $ones[$Dn * 10 + $n]; 
        } 
        else 
        { 
            $res .= $tens[$Dn]; 

            if ($n) 
            { 
                $res .= "-" . $ones[$n]; 
            } 
        } 
    } 

    if (empty($res)) 
    { 
        $res = "zero"; 
    } 

    return $res; 
} 

function checkWeeklyOff($dateOfMonth,$weekDayArray,$dayFlag)
{  
	$dayNumber  = date('N',strtotime($dateOfMonth));

	if(count($weekDayArray) > 0)
	{
		if(array_key_exists($dayNumber,$weekDayArray))
		{
			list($nonWorkingDayStr,$nonWorkingHalfDayStr) = explode("_",$weekDayArray[$dayNumber]);
	
			$weekNumber = getWeekOfTheMonth1($dateOfMonth);
			if($dayFlag == 'F')
			{
				if(in_array($weekNumber,explode(",",$nonWorkingDayStr)))
					return true;
			}
			else// for half day
			{
				if(in_array($weekNumber,explode(",",$nonWorkingHalfDayStr)))
					return true;
			}
		}
	}
	return false;
}


function getWeekOfTheMonth($dateOfMonth)
{
	$d = date('j',strtotime($dateOfMonth));
	$w = date('w',strtotime($dateOfMonth))+1; //add 1 because date returns value between 0 to 6

	$dt = (floor($d % 7)!=0)? floor($d % 7) : 7;
	$k = (($w-$dt) > 0) ?  $w-$dt : 7+ ($w-$dt);
	
	$W = ceil(($d + $k)/7);
	
	return $W ;
}

function getWeekOfTheMonth1($dateOfMonth)
{
	list($year,$month,$day) = explode("-",$dateOfMonth);
	$startDateOfMonth = $year.'-'.$month.'-01';
	
	$dayNumberWeekArray = array();
	$firstDayOfFirstDate = date('w',strtotime($startDateOfMonth));//1 (for Monday) through 7 (for Sunday)
	
	$weekNumber = 1;
	for($d=1;$d<=$day;$d++)
	{
		if($d<10)
			$d = '0'.$d;
	
		$dateIndex = $year.'-'.$month.'-'.$d;
		if($firstDayOfFirstDate < 7)
		{
			$dayNumberWeekArray[$dateIndex] = $weekNumber;
			$firstDayOfFirstDate++;
		}
		else
		{
			$weekNumber++;
			$firstDayOfFirstDate = 1;
			$dayNumberWeekArray[$dateIndex] = $weekNumber;
		}
	}
	
	return $dayNumberWeekArray[$dateOfMonth];
}

function GetHeadValueByFormula($formula,$salHeadAmountDetail)// this function will return a function expression as string in which head will be replace by head value
{
	foreach($salHeadAmountDetail as $head => $headAmount)
	{
		$formula = preg_replace('/\b'.$head.'\b/', $headAmount, $formula);
	}
	
	return $formula;
}

function numberToRoman($num) 
{
	 // Make sure that we only use the integer portion of the value
	 $n = intval($num);
	 $result = '';
	 // Declare a lookup array that we will use to traverse the number:
	 $lookup = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400,
	 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40,
	 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
	 foreach ($lookup as $roman => $value) 
	 {
		 // Determine the number of matches
		 $matches = intval($n / $value);
		 // Store that many characters
		 $result .= str_repeat($roman, $matches);
		 // Substract that from the number
		 $n = $n % $value;
	 }
	 // The Roman numeral should be built, return it
	 return $result;
}

function http_post_lite($url, $data, $headers=null) {

$data = http_build_query($data);
$opts = array('http' => array('method' => 'POST', 'content' => $data));

if($headers) {
$opts['http']['header'] = $headers;
}
$st = stream_context_create($opts);
//$fp = fopen($url, 'rb', false, $st);

if(!$fp) {
return false;
}
return stream_get_contents($fp);
}

function drawGraph($val) 
{
	require_once ('../User/piegraph/jpgraph.php');
	require_once ('../User/piegraph/jpgraph_pie.php');
	
	$data = array(60,40);
	//$data = explode("_",$val);
	
	$graph = new PieGraph(500,200);
	$graph->SetShadow();
	$graph->title->Set("A simple Pie plot");
	
	$p1 = new PiePlot($data);
	$graph->Add($p1);
	$graph->Stroke();
}

function getLeaveModeMonthArray($leaveMode,$leaveStartMonth)//  this function will return an array acording to leave mode
{
	$monthsInKey = 12/$leaveMode;
	$allMonthArray = array();
	
	for($x=0; $x<$leaveMode; $x++)
	{
		$mArray = array();
		$c = 0;
		if(!in_array(12,$allMonthArray))
		{
			for($m = trim($leaveStartMonth,'0'); $m <= 12; $m++)
			{
				if(!in_array($m,$allMonthArray))
				{
					if($c < $monthsInKey)
					{
						$mArray[] = $m;
						$allMonthArray[] = $m;
						$c++;
					}
					else
					{
						$c=0;
						break;
					}
				}
			}
		}
		if(in_array(12,$allMonthArray))
		{
			for($m = 1; $m < trim($leaveStartMonth,'0'); $m++)
			{
				if(!in_array($m,$allMonthArray))
				{
					if($c < $monthsInKey)
					{
						$mArray[] = $m;
						$allMonthArray[] = $m;
						$c++;
					}
					else
					{
						$c=0;
						break;
					}
				}
			}
		}
		$LeaveModeMonthArray[] = $mArray;
	}
	return $LeaveModeMonthArray;
}

function SMSMerge($template_data, $data)
{
	foreach($data as $templateVariable => $variableValue)
	{
		$template_data = str_replace($templateVariable, $variableValue, $template_data);
	}	
	return $template_data;
}	
function excelReport($body, $filename)
{	
	header('Content-Type: application/force-download');
	header("Content-type: application/vnd.ms-excel"); 
	header("Content-Disposition: attachment; filename=".$filename);
	header('Content-Transfer-Encoding: binary');
	header("Pragma: no-cache");
	header("Expires: 0");
	print $body;
}

function calculateDaysBetweenDates($startDate,$endDate)// this function will return age in year
{
    $start_ts = strtotime($startDate);
	$end_ts = strtotime($endDate);
	$diff = $end_ts - $start_ts;
	
	$days = round($diff / 86400);
    return $days;     
}
function DateInWords($date)// this function will return date in words
{
   $d=date('j',strtotime($date));
   $m=date('F',strtotime($date));
   $y=date('Y',strtotime($date));
   
   $words=convert_number($d).' '.$m.' '.str_replace(' and','',convert_number($y));
    return $words;     
}
function exportIntoExcel($data, $headerNames, $filename, $type="", $grandTotal="")
{
	$head = "<table border=1><tr><td colspan='". count($headerNames) ."'  align='center'  bgcolor='#ABA7BA'> <strong>". 
				substr($filename, 0, (strlen($filename)-4)). " &nbsp;Report" ."</strong></td></tr><tr>";
	foreach ($headerNames as $headerName)
	{
		$head = $head."<td align=\"left\" bgcolor='#D18274'> <strong>".$headerName."</strong></td>";
	}
	
	$head = $head."</tr>";

	$dataList="<tr>";

	if(is_array($data))
	{
		$bgcolor = 0;
		foreach($data as $value)
		{
			$bgcolor = '#CCCCCC';
			if($bgcolor % 2 ==0)
				$bgcolor = '';
				
			if(is_array($value) && count($value) > 0)
			{
				foreach($value as $dataValue)
				{
					$dataList = $dataList."<td align=\"left\" bgcolor='".$bgcolor."'>". $dataValue."</td>"; 
				}
				$dataList = $dataList. "</tr>"; 
			}	
			$value=array();
			$bgcolor++;
		}
	}
	if($type == 1)
	{
		$dataList=$dataList."<tr><td style='text-align:right' colspan='".(count($headerNames)-1)."'><strong>Grand Total : Rs.</strong></td><td style='text-align:left'><strong>".$grandTotal."</strong></td></tr>";
	}
	$dataList=$dataList."</table>";
	header("Content-type: application/vnd.ms-excel"); 
	header("Content-Disposition: attachment; filename=".$filename);
	header("Pragma: no-cache");
	header("Expires: 0");
	print $head.$dataList;
}

function exportIntoExcelSinglePage($dataList, $filename)// in this function we send complete page data in htme formate
{
	$dataList = str_replace('border="0"', 'border="1"', $dataList);
	header("Content-type: application/vnd.ms-excel"); 
	header("Content-Disposition: attachment; filename=".$filename);
	header("Pragma: no-cache");
	header("Expires: 0");
	print $dataList;
}

function GetQueryStringParameters()
{
	$paramArray = array();
	if(isset($_GET['urlstring']))
	{
		$urlParams = DecryptURL($_GET['urlstring']);
		$params = explode('&', $urlParams);
		$paramArray = array();
		foreach($params as $param)
		{
			if ($param === '' || strpos($param, '=') === false) {
				continue;
			}
			list($key, $value) = explode('=',$param, 2);
			$paramArray[$key] = $value;
		}
	}

	return $paramArray;
}

function sinelec_set_flash(?string $type, ?string $message): void
{
	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	$_SESSION['type'] = $type;
	$_SESSION['message'] = $message;
}

function sinelec_consume_flash(): array
{
	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	$type = $_SESSION['type'] ?? null;
	$message = $_SESSION['message'] ?? null;

	$_SESSION['type'] = null;
	$_SESSION['message'] = null;
	unset($_SESSION['type'], $_SESSION['message']);

	return [
		'type' => $type,
		'message' => $message,
	];
}

function sinelec_env(string $key, ?string $default = null): ?string
{
	static $envCache = null;

	if ($envCache === null) {
		$envCache = [];
		$envPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env';
		if (is_file($envPath)) {
			$lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
			if (is_array($lines)) {
				foreach ($lines as $line) {
					$line = trim($line);
					if ($line === '' || str_starts_with($line, '#') || strpos($line, '=') === false) {
						continue;
					}

					list($envKey, $envValue) = explode('=', $line, 2);
					$envKey = trim($envKey);
					$envValue = trim($envValue);
					$envValue = trim($envValue, "\"'");
					$envCache[$envKey] = $envValue;
				}
			}
		}
	}

	return $envCache[$key] ?? $default;
}

function sinelec_validate_turnstile(string $token, ?string $remoteIp = null): array
{
	$secretKey = sinelec_env('SECRET_KEY');
	if (!$secretKey) {
		return [
			'success' => false,
			'error-codes' => ['missing-secret-key'],
		];
	}

	if ($token === '') {
		return [
			'success' => false,
			'error-codes' => ['missing-input-response'],
		];
	}

	$payload = [
		'secret' => $secretKey,
		'response' => $token,
	];

	if ($remoteIp) {
		$payload['remoteip'] = $remoteIp;
	}

	$options = [
		'http' => [
			'header' => "Content-type: application/x-www-form-urlencoded\r\n",
			'method' => 'POST',
			'content' => http_build_query($payload),
			'timeout' => 10,
		],
	];

	$context = stream_context_create($options);
	$response = @file_get_contents('https://challenges.cloudflare.com/turnstile/v0/siteverify', false, $context);

	if ($response === false) {
		return [
			'success' => false,
			'error-codes' => ['internal-error'],
		];
	}

	$decoded = json_decode($response, true);
	return is_array($decoded) ? $decoded : [
		'success' => false,
		'error-codes' => ['invalid-json'],
	];
}
?>
