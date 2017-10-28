<?php
spl_autoload_register(function ($class) { //load all external classes to run the algorithm
	if(file_exists(__DIR__."/classes/".$class.".php")){
		require_once(__DIR__."/classes/".$class.".php");
	}
});
require_once(__DIR__ . '/vendor/autoload.php');

define("SUBDIR", "/Schedule-Generator-PHP/src");

/**
 * Returns Pug (Jade) rendered HTML for a given view and options
 * @param $view string Name of Pug view to be rendered
 * @param $title string Title of the webpage
 * @param array $options Additional options needed to render the view
 * @param bool $prettyPrint If prettyPrint is false, all HTML is on a single line
 * @return string Pug generated HTML
 */
function generatePug($view, $title, $options = [], $prettyPrint = true){
		$initialOptions = [
		'title' => $title,
		'subdir' => SUBDIR,
	];

	$options = array_merge($initialOptions, $options);

	$pug = new \Pug\Pug(['pretty' => $prettyPrint, 'expressionLanguage' => 'js', "pugjs" => true, 'localsJsonFile' => true]);
	return $pug->renderFile($view, $options);
}

function SRIChecksum($input){
	$hash = hash('sha256', $input, true);
	$hashBase64 = base64_encode($hash);

	return "sha256-$hashBase64";
}
