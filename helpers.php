<?php 

/*

	this function is to split full name string
	into two part first name and last name
	
	like :

	$fullname = "Ajay Kumar";
	$name = split_name($fullname);
	echo $name['firstname']; // Ajay 
	echo $name['lastname']; // Kumar 

*/

function split_name($name)
{
	$parts = explode(" ", $name);
	$lastname = array_pop($parts);
	$firstname = implode(" ", $parts);
	return ['firstname' => $firstname, 'lastname' => $lastname];
}



/*
	Suppose you have a file name and want to know where is located and file name and extension of file so this function will help you like : 

	$path  = 'var/tr/img/hotel_the_cliff_bay_spa_suite_2048x1310.jpg';
	$info = file_path($path);

	`$info` has array with some data 

	// array:4 [
	//   "dirname" => "var/tr/img"
	//   "basename" => "hotel_the_cliff_bay_spa_suite_2048x1310.jpg"
	//   "extension" => "jpg"
	//   "filename" => "hotel_the_cliff_bay_spa_suite_2048x1310"
	// ]
*/

function file_path($path)
{
	$fileParts = pathinfo($path);

	if(!isset($fileParts['filename']))
	{
		$fileParts['filename'] = substr(
				$fileParts['basename'], 0, 
				strrpos($fileParts['basename'], '.')
			);
	}
		
	return $fileParts;
}



/*
	a variable contain string but in string 
	has some number or integer and some time 
	we want to have that number so this function will help in it.

	for example : 

	$string = '+ 9876543210';
	$number = extract_int($string);
	echo $number; // 9876543210;
*/
function extract_int($string)
{
	return (int) filter_var($string, FILTER_SANITIZE_NUMBER_INT);
}



function statusBool($status)
{
	$res = 0;

	if ($status == 'success') {
		$res = 1;
	}
	elseif ($status != 'failure') {
		exit('<h1>Something went wrong</h1>');
	}

	return $res; 
}



/*
	This method give a unique number token

	echo uid(); // 150419499964660 random every time
*/
function uid()
{
	return intval(time().rand(1000,99999));
}


/*
	This method will give a unique token string 
	echo new_token(); // "9428575ccc20aaf7728a4bf710343437" 
*/
function new_token()
{
	return mycrypt(uid());
}


/*
	This method to encrypt any string like name or else, 
	but it won't decrypt or it genrate random hash every 
	time to same value. 

	for example : 

	echo mycrypt('foo bar'); // "dcc8e2090a73ddd93bd16e5069242e76"


*/
function mycrypt($value = '')
{
	return md5(uniqid(rand(), true).bin2hex(mcrypt_create_iv(22, MCRYPT_DEV_URANDOM)).$value);
}


/*
	to encrypt any value to short and it can be decryptable
	for example 
	
	echo myencrypt('foo', 'bar'); // k/JUkkgBuLDwAFcd/YxKXg==
*/
function myencrypt($value, $salt = '')
{
	// return base64_encode($value.'fgf_salt');
	return openssl_encrypt($value,"AES-128-ECB", $salt);
}



/*
	This is the decrypt method to `myencrypt()`

	for example : 

	echo mydecrypt('k/JUkkgBuLDwAFcd/YxKXg==', 'bar'); // foo

*/
function mydecrypt($value, $salt = '')
{
	// return str_replace('fgf_salt', '', base64_decode($value));
	return openssl_decrypt($value,"AES-128-ECB", $salt);
}


/*
	this function to clean any special chars from string 

	for example : 

	echo clean('foo^&&^%**'); // foo

*/
function clean($string) {
	 $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
	 $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
	 return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
}


function clean_html($html)
{
	$html = preg_replace("/<([a-z][a-z0-9]*)[^>]*?(\/?)>/i",'<$1$2>', $html);
	$html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
	return $html;
}


/*
	This method to add percent to current value

	example : 

	echo add_percent(88, 10); // 88

*/
function add_percent($value, $per)
{
	return $value+(($value*$per)/100);
}


/*
	This method to show all file and dir of given path in array

	Example : 
	
	$lists = list_folder_files('storage');
	
	`$lists contain an array for all files of every sub folder`
	// array:27 [
			  1 => "/var/www/html/storage/doc/media/stylesheet.css"
			  2 => "/var/www/html/storage/JSON.php"
			  3 => "/var/www/html/storage/Test-JSON.php"
			  4 => "/var/www/html/storage/LICENSE"
			]	

*/

function list_folder_files($dir){
	$ffs = scandir($dir);
	$result = [];
	foreach($ffs as $ff){
		if($ff != '.' && $ff != '..'){
			if(is_dir($dir.'/'.$ff)){
				$resultTemp = list_folder_files($dir.'/'.$ff);
				$result = array_merge($resultTemp, $result);

			}else{
				$result[] = $dir.'/'.$ff;
			}
		}
	}
	return $result;
}


function checkRemoteFile($url)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$url);
	// don't download content
	curl_setopt($ch, CURLOPT_NOBODY, 1);
	curl_setopt($ch, CURLOPT_FAILONERROR, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	if(curl_exec($ch)!==FALSE)
	{
		return true;
	}
	else
	{
		return false;
	}
}

/*
 * Convert an integer to a string of uppercase letters (A-Z, AA-ZZ, AAA-ZZZ, etc.)
 */
function num2alpha($n)
{
	for($r = ""; $n >= 0; $n = intval($n / 26) - 1)
		$r = chr($n%26 + 0x41) . $r;
	return $r;
}

/*
 * Convert a string of uppercase letters to an integer.
 */
function alpha2num($a)
{
	$l = strlen($a);
	$n = 0;
	for($i = 0; $i < $l; $i++)
		 $n = $n*26 + ord($a[$i]) - 0x40;
	return $n-1;
}


/*
| this function is for finding word from string 
*/

function findWord($words, $string)
{
	$result = false;
	if (is_array($words)) {
		$tempResult = false;
		
		foreach ($words as $word) {
			$tempResult = findWord($word, $string);			
			if ($tempResult) {
				break;
			}
		}

		$result = $tempResult;
	}
	else{
		if (strpos(strtolower($string), strtolower($words)) !== false) {
			$result = true;
		}
	}

	return $result;
}


function pre_echo($array){
	echo '<pre>';
	print_r($array);
	echo '</pre>';
}


function ddp($array){
	pre_echo($array);
	exit;
}


function trimHtml($html)
{
	$html = trim( preg_replace('/\s+/', ' ', preg_replace('/\t+/', '',$html)));
	$html = str_replace('> <', '><', $html);
	return $html;
}


function proper($Word){
	return ucwords(strtolower($Word));
}


function roundUp($value){
	return round($value, 2, PHP_ROUND_HALF_UP);
}


function countObject($object){
	return count((array) $object);
}



function ifset(&$var, $else = '') {
	return isset($var) ? $var : $else;
}


function ifsetEqual(&$var, $value) {
	return (isset($var) &&  $var == $value) ? true : false;
}


/*
|--------------------------------------------------------------------------
| How to use this "Isset_Multi" function 
|--------------------------------------------------------------------------
|
| This fucntion is call like this 
|
| // This is the "Key Array" which have to search.
| $search = ["Name","Email"]; 
|
| // this is the array where we have to find above keys
| $array = ["Name"=>"Ajay", "Email"=>"ajay@flygoldfinch.com", "Status" => "Active"];
|
| $bool = Isset_Multi($search,$array); // true
|
*/

function isset_multi($search, $array){
	$array = is_object($array) ? json_decode(json_encode($array), true) : $array;

	$return = (
		is_array($search) &&  is_array($array) &&  
		(count(array_intersect_key(array_keys($array), $search)) === count($search))
	) ?  TRUE : FALSE;
	return $return;
}



function is_url_exist($url){
	$ch = curl_init($url);    
	curl_setopt($ch, CURLOPT_NOBODY, true);
	curl_exec($ch);
	$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

	if($code == 200){
		 $status = true;
	}else{
		$status = false;
	}
	curl_close($ch);
	return $status;
}


function parameterize_array($array, $glue = '=', bool $isReverse = false) {
    $out = [];

    foreach($array as $key => $value){
      $out[] = $isReverse ? $value.$glue.$key : $key.$glue.$value;
    }

    return $out;
}


function implode_kv($glue, $keyGlue, Array $array){
	return implode($glue, parameterize_array($array, $keyGlue));
}


function implodeEscape($glue, $pieces)
{
	return implode($glue, array_map('addslashes', $pieces));
}


// this funtion is made for impoding multidimetional array
function implode_r($g, $p) {
	return is_array($p) 
					? implode($g, array_map(__FUNCTION__, array_fill(0, count($p), $g), $p)) 
					: $p;
}



function insertIgnoreQuery($array, $table)
{
	$colums = array_keys($array);
	$values = array_values($array);
	$query = "INSERT IGNORE INTO `".$table
					.'` (`'.implodeEscape("`, `", $colums).'`)'
						." VALUES ('".implodeEscape("', '", $values)."'); ";
	return $query;
}


function do_get_request($url)
{
	$url = htmlspecialchars_decode($url);

	$headers = @get_headers($url);
	
	if (isset($headers[0]) && $headers[0] == 'HTTP/1.1 200 OK') {
		return file_get_contents( $url );
	}else{
		return null;
	}
}


function httpGet($url){
	$ch = curl_init();  

	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	//  curl_setopt($ch,CURLOPT_HEADER, false); 

	$output = curl_exec($ch);

	curl_close($ch);
	return $output;
}



function httpPost($url, $data = null,  $header = [])
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);  
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);     
	$result = curl_exec($ch);
	
	return $result;
}


function currencyExchange($From = '', $To = ''){

	$url = ($From == '' || $To == '')

			 ? 'http://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20yahoo.finance.xchange%20where%20pair%20in%20(%22USDEUR%22,%20%22USDJPY%22,%20%22USDBGN%22,%20%22USDCZK%22,%20%22USDDKK%22,%20%22USDGBP%22,%20%22USDHUF%22,%20%22USDLTL%22,%20%22USDLVL%22,%20%22USDPLN%22,%20%22USDRON%22,%20%22USDSEK%22,%20%22USDCHF%22,%20%22USDNOK%22,%20%22USDHRK%22,%20%22USDRUB%22,%20%22USDTRY%22,%20%22USDAUD%22,%20%22USDBRL%22,%20%22USDCAD%22,%20%22USDCNY%22,%20%22USDHKD%22,%20%22USDIDR%22,%20%22USDILS%22,%20%22USDINR%22,%20%22USDKRW%22,%20%22USDMXN%22,%20%22USDMYR%22,%20%22USDNZD%22,%20%22USDPHP%22,%20%22USDSGD%22,%20%22USDTHB%22,%20%22USDZAR%22,%20%22USDISK%22)&env=store://datatables.org/alltableswithkeys'

			 : 'http://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20yahoo.finance.xchange%20where%20pair%20in%20(%22'.$From.$To.'%22)&env=store://datatables.org/alltableswithkeys';

	// fatching response here
	$response = httpGet($url);

	// converting response to object
	$response = rejson_decode(simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA));
	return $response;
}



function fixjson($s)
{
	$s = preg_replace('/\s(?=([^"]*"[^"]*")*[^"]*$)/', '', $s);
	$s = str_replace(['"',  "'"],['\"', '"'],$s);
	$s = preg_replace('/(\w+):/i', '"\1":', $s);
	$s = str_replace('""https"', '"https', $s);
	return $s;
}	


// this function to remove leadung zero like 001 => 1; 
function removeLeadingZero($value=0)
{
	return +$value;
}


// 
function sub_string($string, $length = 50, $start = 0){
	return strlen($string) >= $length ? substr($string,$start,$length).'...' : $string; 
}


function array_intval(Array $array)
{
	return array_map('intval', $array);
}


// this funtion is made for checking multidimetional array is int or not
function is_int_array($array){
	return ctype_digit(implode_r('', $array));
}

function bool_array($Array){
	return (is_array($Array) && !empty($Array)) ? TRUE : FALSE;
}

function bool_object($Array){
	return (is_object($Array) && !empty($Array)) ? TRUE : FALSE;
}

function is_xml($string){
	return preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $string) ? true : false;
}

function xml_to_json($xml){
	return simplexml_load_string($xml, "SimpleXMLElement", LIBXML_NOCDATA);
}


/**
 * Simple function to replicate PHP 5 behaviour
 */
function microtime_float()
{
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
}

function timeStart()
{
	$timeStart = microtime_float();
	echo "<br> Start Time : ".$timeStart. "<br>";
	return $timeStart;
}


function timeEnd($timeStart = null)
{
	$timeEnd = microtime_float();

	echo "<br> End Time : ".$timeEnd."<br>";

	if (!is_null($timeStart)) {
		$time = $timeEnd - $timeStart;
		echo "<br> Total time it take to execute : <b>$time seconds<b><br>";
	}
	return $timeEnd;
}

// this function to get full 24 hr time from am to pm
function timeFull($time='', $full = true)
{	
	$time =  str_replace(' : ', ':', $time);
	if (!$full) {
		$time = date("H:i", strtotime($time));
	}

	return $time;
}


function addDaysinDate($date,$days, $format = "Y-m-d"){
	$date = strtotime("+".$days." days", strtotime($date));
	return  date($format, $date);
}


function secToDay($sec)
{
	return $sec/(60*60*24);
}


function date_differences($EndDate,$StartDate, $format = 'Y-m-d'){

	$EndDate_T = new DateTime();
	$StartDate_T = new DateTime();

	if ($format != 'Y-m-d') {
		$EndDate_T = new DateTime(Date_Formatter($EndDate, $format, 'Y-m-d'));
		$StartDate_T = new DateTime(Date_Formatter($StartDate, $format, 'Y-m-d'));
	}
	else{
		$EndDate_T = new DateTime($EndDate);
		$StartDate_T = new DateTime($StartDate);
	}

	$interval = $EndDate_T->diff($StartDate_T);
	$differences = $interval->format('%a');
	return $differences;
}


function getDefaultDateTime($date)
{
	$result = false;

	if ($date != '') {
		$dateTimeObj = new DateTime($date);
		return $dateTimeObj->format('Y-m-d H:i:s');
	}

	return $result;
}



function date_formatter($Date, $CurrentFormat = null, $DesireFormat = null){
	$DesireFormat = $DesireFormat == null ? 'Y-m-d' : $DesireFormat;
	$CurrentFormat = $CurrentFormat == null ? 'Y-m-d' : $CurrentFormat;

	$date = DateTime::createFromFormat($CurrentFormat, $Date);
	return ($date != FALSE) ? $date->format($DesireFormat) : FALSE; 
}

function getDateTime($dateTime, $dateformat = 'Y-m-d', $timeformat = 'H:i'){

	$dt = new DateTime($dateTime);
	$date = $dt->format($dateformat);
	$time = $dt->format($timeformat);
	return (object)["date" => $date, "time" => $time];
}

/* 
| this is simple funtion to convert time into hour and min but the 
| $time should be like 01:00:00
*/
function convertInHourMin($time){
	$timeArray = explode(':', $time);
	if (isset($timeArray[0]) && isset($timeArray[1])) {
		$hour = +$timeArray[0]." h ";
		$min = +$timeArray[1] == 0 ? '' : $timeArray[1].' min';

		return $hour.$min;
	}
}

function convertSeconds($seconds, $full = true)
{  
	$dt1 = new DateTime("@0");  
	$dt2 = new DateTime("@$seconds");
	$diff = $dt1->diff($dt2);

	$array = [];

	if ($full) {
		$array = [
				"day"		 => $diff->days,
				"hour"	 => $diff->h,
				"minute" => $diff->i,
				"second" => $diff->s,
			];
	}
	else{
		$array = [
				"day"	=> $diff->days,
				"h"	 	=> $diff->h,
				"min" => $diff->i,
				"sec" => $diff->s,
			];
	}

	$word = '';
	foreach ($array as $key => $value) {
		if ($value) {
			$newKey = $value > 1 && $full ? $key.'s' : $key;
			$word .= $value." ".$newKey." ";
		}
	}

	return $word;
	// return ->format('%a days, %h hours, %i minutes and %s seconds');
}  


function getArrayValueByPath($array, $path) {
		$temp = &$array;

		foreach($path as $key) {
			$temp = &$temp[$key];
		}
		return $temp;
}


function acronyms($string, $length = 3)
{
	$acro = preg_replace('~\b(\w)|.~', '$1', $string);
	return substr($acro, 0,$length);
}


function daysToMonth($days, $inRound = true)
{
	$month = $days/30;
	return $inRound ? ceil($month) : $month;
}


function timeStamp(){
	date_default_timezone_set('Asia/Kolkata');
	$DateTime_Obj = new DateTime();
	$DateTime_Array =  (array) $DateTime_Obj;
	return $TimeStamp = $DateTime_Obj->getTimestamp();
}



//Merge two arrays alternatively
function alternativelyMerge($array1=[], $array2 =[])
{
	if (count($array1) > count($array2)) {
		$bigArray = array_values($array1);
		$smallArray = array_values($array2);
	}
	else{
		$bigArray = $array2;
		$smallArray = $array1;
	}


	$new = [];
	
	for ($i=0; $i<count($bigArray); $i++) {
		if (isset($bigArray[$i])) {
			$new[] = $bigArray[$i];
		}

		if (isset($smallArray[$i])) {
			$new[] = $smallArray[$i];
		}
	}

	return $new;
}


function make_object($array){
	return json_decode(json_encode($array));
}

function json_decode_else($string, $else, $objectBool = false){
	$result = json_decode($string, $objectBool);
	return $result == '' ? $else : $result;
}


function rejson_decode($array, $is_array = false){
	return json_decode(json_encode($array), $is_array);
}



function echoLocation($origin = '', $destination = '', $glue = ', ')
{
	$dests = implode($glue, array_unique([$origin, $destination]));
	return $dests == '' ? 'In transit' : $dests;
}


function sort_by_column($x, $y, $col) {
	return $x[$col] - $y[$col];
}



/* 
* ----------------------------------------------*
*                 html helpers                  *
* ----------------------------------------------*
*/

function showIsChecked($bool) {
	return $bool ? 'checked=""' : '';
}

function displayNone($bool)
{
	return $bool ? 'style="display: none;"' : '';
}


function selectOptions($array, $sKey = '')
{
	$options = '';
	foreach ($array as $key => $value) {
		$selected = $key == $sKey ? 'selected' : '';
		$options .= '<option value="'.$key.'" '
								.$selected.'>'.$value.'</option>';
	}
	return $options;
}


