<?php
if (!isset($_SESSION)) 
{ 
     session_start(); 
} 

# Define program-wide constanats
define("FRENCH", "fr");
define("ENGLISH", "en");
define("BLOGUE", "BLOGUE_");

// For now, set the locale every time.  May get smarter later
function setLanguage() {
	$defaultLang = "fr_FR";

	$language = defaultVal($_SESSION, "language", $defaultLang);

	if (isset($_GET["lang"]))
	{
	    $lang = filter_input(INPUT_GET, 'lang', FILTER_SANITIZE_STRING);
	    if (strpos($lang, "en") === 0) {
	        $language = "en_US";
	    }
	    else {
	    	$language = $defaultLang;
	    }
	}

	putenv("LANG=" . $language);
	setlocale(LC_ALL, $language);
	//echo "Setting language to " . $language;

	// Set the text domain as "messages"
	$domain = "messages";
	bindtextdomain($domain, "locale");

	//bind_textdomain_codeset($domain, 'UTF-8');
	textdomain($domain);

	// Just return fr or en
	return substr($language, 0, 2) === "en" ? ENGLISH : FRENCH;
}

function defaultVal($array, $key, $default) {
    return isset($array[$key]) ? $array[$key] : $default;
}


function getBlogId($postId) {
    return BLOGUE . $postId;
}

// Is this needed?  Or is filter_input() sufficient ?
function cleanInput($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

?>