<?php
spl_autoload_register(function ($class) { //load all external classes to run the algorithm
	if(file_exists(__DIR__."/classes/".$class.".php")){
		require_once(__DIR__."/classes/".$class.".php");
	}
});
require_once(__DIR__ . '/vendor/autoload.php');

// Figure out what subdirectory we are in
function getRootSubdirectory(){
	$path = explode("/", $_SERVER["PHP_SELF"]);
	array_pop($path); // Remove PHP file from path
	array_shift($path); // Removing beginning slash
	$subdir =  implode("/", $path);
	// If we're in a subdirectory, end with a trailing slash
	if(!empty($subdir)){
		$subdir .= "/";
	}
	return $subdir;
}

define("SUBDIR", getRootSubdirectory());

/**
 * Returns Pug (Jade) rendered HTML for a given view and options
 *
 * @param $view string Name of Pug view to be rendered
 * @param $title string Title of the webpage
 * @param array $options Additional options needed to render the view
 * @param bool $prettyPrint If prettyPrint is false, all HTML is on a single line
 * @return string Pug generated HTML
 * @throws \Exception
 */
function generatePug($view, $title, $options = [], $prettyPrint = false){
		$initialOptions = [
		'title' => $title,
		'subdir' => SUBDIR,
	];

	$options = array_merge($initialOptions, $options);
	$cacheDir = getcwd()."/pug-cache";
	if(!file_exists($cacheDir)){
		mkdir($cacheDir);
	}

	$pug = new \Pug\Pug(['pretty' => $prettyPrint, 'strict' => true, "expressionLanguage" => "js",
		"pugjs" => true, "localsJsonFile" => true,
		"cache" => $cacheDir, "upToDateCheck" => true
	]);
	return $pug->renderFile($view, $options);
}

function SRIChecksum($input){
	$hash = hash('sha256', $input, true);
	$hashBase64 = base64_encode($hash);

	return "sha256-$hashBase64";
}
