<?php
//////////////////////////
//PHP Functions List	//
//////////////////////////
//Deus					//
//////////////////////////

/*function smartquoteset($smartstring) {
	replace = array("\\" => "\\\\", "'" => "\\'", '"' => '\"');
	$smartstring = str_replace(array_keys($replace), array_values($replace), $smartstring);

	return $smartstring;
}

function smartquoteread($smartstring) {
	replace = array("'" => "\'", "\\\\" => "\\");
	$smartstring = str_replace(array_keys($replace), array_values($replace), $smartstring);

	return $smartstring;
}*/

function carriage($smartstring) {
	$replace = array("\r\n" => "[br]", "\r" => "[br]", "\n" => "[br]");
	$smartstring = str_replace(array_keys($replace), array_values($replace), $smartstring);

	return $smartstring;
}

function smartcode($smartstring) {
	$replace = array("<" => "&lt;", ">" => "&gt;", "\r\n" => "<br />", "\r" => "<br />", "\n" => "<br />", "[br]" => "<br />", "[center]" => "<center>", "[/center]" => "</center>", "[b]" => "<strong>", "[/b]" => "</strong>", "[i]" => "<em>", "[/i]" => "</em>", "[u]" => "<u>", "[/u]" => "</u>", "[colour]" => "<font color=\'#", "[/colour]" => "</font>", "]" => "\'>");
	$smartstring = str_replace(array_keys($replace), array_values($replace), $smartstring);

	return $smartstring;
}

function formatBlood($oz) {
	$oz = floor($oz);
	$gal = floor($oz / 128);
	$remainder = $oz % 128;
	$pt = floor($remainder / 16);
	$oz = $remainder % 16;
	return number_format($gal)." gal ".number_format($pt)." pt ".number_format($oz)." oz.";
}

function collidesWithBox($relLoc, $boxes) {
	foreach($boxes as $box){
		if($box[1] >= $relLoc[0] && $box[0] <= $relLoc[0] && $box[3] >= $relLoc[1] && $box[2] <= $relLoc[1]){
			return true;
		}
	}
	return false;
}
?>