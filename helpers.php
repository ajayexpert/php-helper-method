<?php 

function exitView($url = null)
{
	if ($url == null) {
		$url = urlReport();
	}
	$blade = ["url" => $url];
	exit(view('errors.404', $blade)->render());
}


function wrongView($url=null)
{
	if ($url == null) {
		$url = urlReport();
	}
	$blade = ["url" => $url];
	exit(view('errors.404', $blade)->render());
}

function splitName($name)
{
	$parts = explode(" ", $name);
	$lastname = array_pop($parts);
	$firstname = implode(" ", $parts);
	return ['firstname' => $firstname, 'lastname' => $lastname];
}


function backendTypes($type = "", $option = true)
{
	$types = [
			"user" 	=> "User",
			"admin" => "Admin",
			"su" 		=> "Super User",
		];

	if ($option) {
		$options = '';

		foreach ($types as $key => $value) {
			$selected = $key == $type ? 'selected' : '';
			$options .= '<option value="'.$key.'" '
									.$selected.'>'.$value.'</option>';
		}

		$types = $options;
	}

	return $types;
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


function filePath($path)
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



function extractInt($string)
{
	return (int) filter_var($string, FILTER_SANITIZE_NUMBER_INT);
}


function addDateColumns(Array $data)
{
	$data["created_at"] = date('Y-m-d H:i:s');
	$data["updated_at"] = date('Y-m-d H:i:s');
	return $data;
}


function loopImages(Array $data, Array $images, $type = 'object')
{
	$count = 0;
	foreach ($data as $key => &$value) {
		if ($key >= count($images)) {
			$count = 0;
		}

		$value['image'] = $images[$count];
		$count++;
	}
	if ($type == 'json') {
		$data = json_encode($data);
	}
	elseif ($type == 'object') {
		$data = rejson_decode($data);
	}
	return $data;
}


function statusBool($status)
{
	$res = 0;

	if ($status == 'success') {
		$res = 1;
	}
	elseif ($status != 'failure') {
		wrongView();
	}

	return $res; 
}


function newToken()
{
	return mycrypt(uid());
}


function mycrypt($value)
{
	return md5(bcrypt($value));
}

function myencrypt($value)
{
	// return base64_encode($value.'fgf_salt');
	return openssl_encrypt($value,"AES-128-ECB",'FGF');
}

function mydecrypt($value)
{
	// return str_replace('fgf_salt', '', base64_decode($value));
	return openssl_decrypt($value,"AES-128-ECB",'FGF');
}


function clean($string) {
	 $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
	 $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
	 return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
}

 
function removeAndSym($string){
	if (findWord('&amp;', $string)) {
		$string = str_replace( "&amp;", "&", $string);
		return removeAndSym($string);
	}else{
		return $string;
	}
}

function addPercent($value, $per)
{
	return $value+(($value*$per)/100);
}


function listFolderFiles($dir){
	$ffs = scandir($dir);
	$result = [];
	foreach($ffs as $ff){
		if($ff != '.' && $ff != '..'){
			if(is_dir($dir.'/'.$ff)){
				$resultTemp = listFolderFiles($dir.'/'.$ff);
				$result = array_merge($resultTemp, $result);

			}else{
				$result[] = $dir.'/'.$ff;
			}
		}
	}
	return $result;
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

function showIsChecked($bool) {
	return $bool ? 'checked=""' : '';
}

function displayNone($bool)
{
	return $bool ? 'style="display: none;"' : '';
}

function mylocal_path($path)
{
	return base_path('storage/mylocal/'.$path);
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



function properString($string)
{
	$string = proper($string);
	$search = ['To', 'Or', 'And', 'Of', 'With'];
	$replace = ['to', 'or', 'and', 'of', 'with'];
	return str_replace($search, $replace, $string);
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

function echoHtml($html)
{
	echo trimHtml($html);
}

function trimHtml($html)
{
	$html = trim( preg_replace('/\s+/', ' ', preg_replace('/\t+/', '',$html)));
	$html = str_replace('> <', '><', $html);
	return $html;
}


function myView($path, $blade = [])
{
	// return view($path, $blade);
	return trimHtml(view($path, $blade)->render());
}

function proper($Word){
	return ucwords(strtolower($Word));
}

function roundUp($value){
	return round($value, 2, PHP_ROUND_HALF_UP);
}

function countObject($object){
	return count((array)$object);
}



function ifset(&$var, $else = '') {
	return isset($var) ? $var : $else;
}

function ifsetEqual(&$var, $value) {
	return (isset($var) &&  $var == $value) ? true : false;
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


function implode_kv($array){
	return implode(', ', array_map(
		function ($v, $k) { return sprintf("%s='%s'", $k, $v); },
		$array,
		array_keys($array)
	));
}


function implodeEscape($glue, $pieces)
{
	return implode($glue, array_map('addslashes', $pieces));
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


/*
| this function is save image locally and then send a http request
| to another server then that server download the image using copy or 
| file_get_contents then it returns path of the remote dir 
| remote url is image.trawish.com
*/
function imageUpload($image)
{
	$imageName = md5(uid()).'.'.$image->getClientOriginalExtension();
	$image->move(base_path('public/images/tmp'), $imageName);
	$postUrl = 'http://'.env('IMAGE_DOMAIN').'/image/download';
	$url = url('images/tmp/'.$imageName);
	$name = httpPost($postUrl, ['url' => $url]);
	if (file_exists(base_path('public/images/tmp/').$imageName)) {
		unlink(base_path('public/images/tmp/').$imageName);
	}
	return $name;
}


function trashImage($path)
{
	$url = 'http://'.env('IMAGE_DOMAIN').'/image/trash';
	return httpPost($url, ['path' => $path]);
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


function currencyExchange($From, $To){

	$url = 'http://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20yahoo.finance.xchange%20where%20pair%20in%20(%22'.$From.$To.'%22)&env=store://datatables.org/alltableswithkeys';

	/*========================= this url for demo =========================
	$url = 'http://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20yahoo.finance.xchange%20where%20pair%20in%20(%22USDEUR%22,%20%22USDJPY%22,%20%22USDBGN%22,%20%22USDCZK%22,%20%22USDDKK%22,%20%22USDGBP%22,%20%22USDHUF%22,%20%22USDLTL%22,%20%22USDLVL%22,%20%22USDPLN%22,%20%22USDRON%22,%20%22USDSEK%22,%20%22USDCHF%22,%20%22USDNOK%22,%20%22USDHRK%22,%20%22USDRUB%22,%20%22USDTRY%22,%20%22USDAUD%22,%20%22USDBRL%22,%20%22USDCAD%22,%20%22USDCNY%22,%20%22USDHKD%22,%20%22USDIDR%22,%20%22USDILS%22,%20%22USDINR%22,%20%22USDKRW%22,%20%22USDMXN%22,%20%22USDMYR%22,%20%22USDNZD%22,%20%22USDPHP%22,%20%22USDSGD%22,%20%22USDTHB%22,%20%22USDZAR%22,%20%22USDISK%22)&env=store://datatables.org/alltableswithkeys';*/

	$response = httpGet($url);
	$response = rejson_decode(simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA));
	return $response;
}



function travelFeed(){
	$url = 'http://www.travelbizmonitor.com/Includes/xmlInclude/Travelbizmonitorrss.xml';
	$response = httpGet($url);
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


function removeLeadingZero($value=0)
{
	return +$value;
}


/*
| this function is to get 
*/
function getPax($roomGuests){
	$roomGuests = rejson_decode($roomGuests, true);
	$result = '';

	if (bool_array($roomGuests)) {

		$result = ["NoOfAdults" => 0,"NoOfChild" => 0, "ChildAge" => []];
		foreach ($roomGuests as $key => $value) {
			$result['NoOfAdults'] += isset($value['NoOfAdults']) ? $value['NoOfAdults'] : 0;
			if (isset($value->ChildAge)) {
				$result['ChildAge'] = array_merge($result['ChildAge'], $value['ChildAge']); 
			}
		}
		$result['NoOfChild'] = count($result['ChildAge']);
	}

	return rejson_decode($result);
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

function isset_multi($search,$array){
	$array = is_object($array) ? json_decode(json_encode($array), true) : $array;

	$return = (
		is_array($search) &&  is_array($array) &&  
		(count(array_intersect_key(array_keys($array), $search)) === count($search))
	) ?  TRUE : FALSE;
	return $return;
}



function sub_string($string, $length = 50, $start = 0){
	return strlen($string) >= $length ? substr($string,$start,$length).'...' : $string; 
}

// this funtion is made for impoding multidimetional array
function implode_r($g, $p) {
	return is_array($p) 
					? implode($g, array_map(__FUNCTION__, array_fill(0, count($p), $g), $p)) 
					: $p;
}

// this funtion is made for checking multidimetional array is int or not
function is_IntArray($array){
	return ctype_digit(implode_r('', $array));
}

function Bool_Array($Array){
	return (is_array($Array) && !empty($Array)) ? TRUE : FALSE;
}

function Bool_Object($Array){
	return (is_object($Array) && !empty($Array)) ? TRUE : FALSE;
}

function is_xml($string){
	return preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $string) ? true : false;
}

function xmlToJson($xml){
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
				"day"		 => $diff->days,
				"h"	 => $diff->h,
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


function starRating($count){
	$stars = '';

	if (is_numeric($count)) {
		for ($i=1; $i < 5 ; $i++) { 
			if ($i <= $count ) {
				$stars .= '<i class="fa fa-star font-gold font-size-13"></i>';
			}
			else{
				$stars .= '<i class="fa fa-star font-size-13"></i>';
			}
		}
	}
	return $stars;
}

function getStarImage($Count, $width = 18, $height = 18){
	
	global $Server_Name;
	
	$html = '';
	for($x = 1; $x <= $Count; $x++) {
		$html .= '<img src="'.urlImage('images/icon/star.gif').'" width="'.$width.'" height="'.$height.'">';
	}
	return $html;
}


function getArrayValueByPath($array, $path) {
		$temp = &$array;

		foreach($path as $key) {
				$temp =& $temp[$key];
		}
		return $temp;
}

function getPackageId($id = 0){
	return 'FGF'.str_pad($id, 5, '0', STR_PAD_LEFT);
}

function getUniqueId($id, $prefix = '', $count = 5){
	return $prefix.str_pad($id, $count, '0', STR_PAD_LEFT);
}


function acronyms($string, $length = 3)
{
	$acro = preg_replace('~\b(\w)|.~', '$1', $string);
	return substr($acro, 0,$length);
}

function uid()
{
	return time().rand(1000,99999);
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


function saveInFile($data = 'NoData', $name = '', $extn = 'txt', $path = 'test/SaveInFile'){

	$myfile = fopen($path.'/'.$name.'_'.timestamp().".".$extn, "w") or die("Unable to open file!");
	fwrite($myfile, $data);
	fclose($myfile);
}

function saveInStorage($data = 'NoData', $name = '', $extn = 'txt', $path = 'test/SaveInFile'){
	$path = storage_path($path);

	if (!file_exists($path)) {
		mkdir($path, 0777);
	}

	$filePath = $path.'/'.$name.'_'.timestamp().".".$extn;
	$myfile = fopen($filePath, "w") or die("Unable to open file!");
	fwrite($myfile, $data);
	fclose($myfile);
	return $filePath;
}

function json_decodeElse($string, $else, $objectBool = false){
	$result = json_decode($string, $objectBool);
	return $result == '' ? $else : $result;
}


function makeObject($array){
	return json_decode(json_encode($array));
}

function rejson_decode($array, $is_array = false){
	return is_bool($is_array) ? json_decode(json_encode($array), $is_array) : '';
}

function pdfHotelDesc($html){
	return str_replace(['<b>', '</b> <br />'], ['<b class="capitalize">', '</b>'], $html);
}

function echoLocation($origin = '', $destination = '', $glue = ', ')
{
	$dests = array_unique([$origin, $destination]); 
	$dests = implode($glue, $dests);
	return $dests == '' ? 'In transit' : $dests;
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


/*=====================default image=====================*/
function urlImage($path = '/')
{
	$path = ltrim($path,'/');
	$array = explode('/', $path);

	if (isset($array[0]) && $array[0] != 'storage') {
		$path = 'storage/'.$path;
	}
	
	$storageUrl = 'http://'.env('IMAGE_DOMAIN');
	return str_replace(url('/'), $storageUrl, url($path));
}



function flightImage($code = null)
{
	return urlImage('images/airlineImages/'.$code.'.gif');
}


/*
| this function is to fix skyscanner images array
*/
function ssImageArrayFix($images = [], $imageHost = '')
{
	$images = rejson_decode($images, true);
	$result = [];
	foreach ($images as $ikey => $iValues) {
		$types = array_keys($iValues);
		if (isset($types[0])) {
			$result[] = 'http://'.$imageHost.$ikey.$types[0];
		}
	}
	return $result;
}
	

function activityMode($key)
{
	$found = null;
	$modes = [
			 'sic' => 'SIC',
			 'no'  => 'No Transfer',
			 'private' => 'Private',
			 'selfdrive' => 'Self Drive'
		];

	if (isset($modes[$key])) {
		$found = $modes[$key];
	}
	return $found;
}


function activityTiming($key)
{
	$found = null;
	$timing = [
			'noon' => 'Noon',
			'morning'	=> 'Morning', 
			'evening'	=> 'Evening', 
			'halfday' => 'Half Day',
			'fullday' => 'Full Day'
		];

	if (isset($timing[$key])) {
		$found = $timing[$key];
	}
	return $found;
}


function urlReport(){
	return url('dashboard/report/submit');
}


function urlDefaultImageRoom(){
	return urlImage('images/default/room.png');
}

function urlDefaultImageHotel(){
	return urlImage('images/default/hotel.png');
}

function urlDefaultImageCruise(){
	return urlImage('images/default/hotel.png');
}

function urlDefaultImageActivity(){
	return urlImage('images/default/activity.png');
}

function urlDefaultImageProfile($value='')
{
	return urlImage('images/default/profile.jpg');
}


function  urlImageAirline($airlineCode='')
{
	$airlineCode = $airlineCode == '' ? '__' : $airlineCode;
	return urlImage('images/airlineImages/'.$airlineCode.'.gif');
}


/*======================Event Url======================*/
function urlPackageEvent($routeDbId){
	return url('/dashboard/package/event/'.$routeDbId);
}


// function urlRouteCreate($clientId)
// {
// 	return url('/dashboard/package/route/'.$clientId);
// }

/*=====================Package Url=====================*/

// function urlPackageOpen($token){
// 	return url('dashboard/package/open/'.$token);
// }


/*=====================Accommodation Url=====================*/
function urlAccomoBuilder($slug = ''){
	return url('dashboard/package/builder/accommodation/'.$slug);
}

function urlAccomoApi($slug = ''){
	return url('api/package/accommodation/'.$slug);
}

/*=====================Hotel Url=====================*/
function urlAllHotelsBuilder($packageDbId = 0){
	return url('dashboard/package/builder/hotels/'.$packageDbId);
}

function urlHotelsBuilder($slug = ''){
	return url('dashboard/package/builder/hotels/'.$slug);
}

function urlHotelsRoomBuilder($firstCartId = 0){
	return url('dashboard/package/builder/hotel/room/'.$firstCartId);
}

function urlHotelsRoomBookBuilder($firstCartId = 0){
	return url('dashboard/package/builder/hotel/room/book/'.$firstCartId);
}


function urlTbtqResult($firstCartId = "")
{
	return url('/t/hotels/result')."/".$firstCartId;
}

function urlSsResult($firstCartId = "")
{
	return url('/ss/hotels/result/'.$firstCartId);
}

/*=====================Cruises Url=====================*/

function urlAllCruisesBuilder($packageDbId = 0){
	return url('dashboard/package/builder/cruises/'.$packageDbId);
}

function urlCruisesBuilder($id = 0, $packageDbId = 0, $firstCartId = 0){
	return url('dashboard/package/builder/cruise/'.$firstCartId);
}

function urlCruisesCabinBuilder($id = 0, $packageDbId = 0, $firstCartId = 0){
	return url('dashboard/package/builder/cruise/cabin/'.$id.'/'.$packageDbId.'/'.$firstCartId);
}

function urlCruisesCabinBookBuilder($id = 0, $packageDbId = 0, $firstCartId = 0){
	return url('dashboard/package/builder/cruise/cabin/book/'.$id.'/'.$packageDbId.'/'.$firstCartId);
}


/*=====================Flights Url=====================*/
function urlFlightsBuilder($packageDbId = 0){
	return url('dashboard/package/builder/flights/'.$packageDbId);
}

function urlFlightsSearch($flightDbId = 0){
	return url('dashboard/package/builder/flight/'.$flightDbId);
}

function urlFlightsResult($flightDbId = 0){
	return url('dashboard/package/builder/flight/result/'.$flightDbId);
}


function urlFlightBook($flightDbId='')
{
	return url('/dashboard/package/builder/flight/book')."/".$flightDbId;
}


/*=====================Activities Url=====================*/

function urlActivitiesBuilder($slug){
	return url('dashboard/package/builder/activities/'.$slug);
}



/*=====================Car Url=====================*/

function urlCarsBuilder($packageDbId = 0){
	return url('dashboard/package/builder/car/'.$packageDbId);
}



/*=========================Pdf Url=========================*/

function urlPdfPacakge($packageDbId){
	return url('dashboard/package/pdf/'.$packageDbId);
}

/*===================== Responce Json =====================*/

function jsonError($error = null){
	return json_encode(error500($error));
}

function jsonResponse($status = 200, $response = 'ok', $data = [])
{
	$array = [
		"status" => $status,
		"response" => $response,
	];
	$array = array_merge($array, $data);
	return json_encode($array);
}


function error500($error='')
{
	$error = is_null($error) 
			 ? "Something Went Wrong please try again later." 
			 : $error;

	return ["status" => 500, "error" => $error];
}

/*====================Activity Function====================*/
/*
| this function is return price if sic price is available the it return 
| sic price eles it return private price id not private then it return 0
*/
function is_sic($NoOfPax, $object){
	$object = rejson_decode($object);
	$adultCount = $NoOfPax->NoOfAdults; 
	$childCount = $NoOfPax->NoOfChild;
	$result = 0;
	
	if (isset($object->Sic->Price->Adult) && isset($object->Sic->Price->Child)) {
		$result = ($object->Sic->Price->Adult*$adultCount)
						+ ($object->Sic->Price->Child*$childCount);
	}
	elseif (isset($object->Private->Price->Adult) && isset($object->Private->Price->Child) && isset($object->Private->Cars[0])) {
		$result =  ($object->Private->Price->Adult*$adultCount)
							+($object->Private->Price->Child*$childCount)
							+(2*requireCar($NoOfPax)*(ifset($object->Private->Cars[0]->Price)));
	}

	return $result;
}
// this is function is for checking how many car need for travel on pax count
function requireCar($NoOfPax){
	return ceil(($NoOfPax->NoOfAdults+$NoOfPax->NoOfChild)/4);
}

function sortById($x, $y) {
	return $x['id'] - $y['id'];
}

function sortBySeatingCapacity($x, $y) {
	return $x['SeatingCapacity'] - $y['SeatingCapacity'];
}




// =====================common asset url=====================

function commonAsset($path){
	return asset('common/'.$path);
}


function statusCss($id)
{
	$css = '';
	if ($id == 0) {
		$css = 'red';
	}
	elseif($id == 1) {
		$css = 'green';
	}
	elseif($id == 2) {
		$css = 'font-orenge';
	}
	elseif($id == 3) {
		$css = 'font-orenge';
	}
	return $css;
}


function statusCssBorder($id)
{
	$css = '';
	if ($id == 0) {
		$css = 'red';
	}
	elseif($id == 1) {
		$css = 'green';
	}
	elseif($id == 2) {
		$css = 'font-orenge';
	}
	elseif($id == 3) {
		$css = 'font-orenge';
	}
	return $css;
}