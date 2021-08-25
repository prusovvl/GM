<?php


function err_log ($dir, $data, $page_info)
 {

	$f = fopen(str_replace("\\","/",PATH)."logs/".$dir."/".date("Y-m-d").".log", "a+");
	fwrite($f, $data."; from ".$page_info." [".date("H:i:s")."]\n"); 
	fclose($f);

 }
 

 
 function SaltGen($length = 10){
  $chars = 'abdefhiknrstyzABDEFGHKNQRSTYZ23456789';
  $numChars = strlen($chars);
  $string = '';
  for ($i = 0; $i < $length; $i++) {
    $string .= substr($chars, rand(1, $numChars) - 1, 1);
  }
  return $string;
}

function ParsParam ($ParamLine)
{
	$ResGet = explode ("&", $ParamLine);
	for ($i=0; $i<=count($ResGet)-1;  $i++)
	{
		$GetByParam = explode ("=", $ResGet [$i]);
		$Result [$GetByParam [0]] = $GetByParam [1];
	}
	
	return $Result;
	
}

function isMobileDevice() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

function redirect($url) {
    if (headers_sent()){
      die('<script type="text/javascript">window.location=\''.$url.'\';</script>');
    }else{
      header('Location: ' . $url);
      die();
    }    
}

function generate_string($strength = 16) {
	
	$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	
    $input_length = strlen($permitted_chars);
    $random_string = '';
    for($i = 0; $i < $strength; $i++) {
        $random_character = $permitted_chars[mt_rand(0, $input_length - 1)];
        $random_string .= $random_character;
    }
 
    return $random_string;
}
 
 
 ?>