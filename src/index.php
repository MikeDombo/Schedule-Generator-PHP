<?php
require_once("config.php");

$options = ["editMode" => false];
$courses = [];
if(isset($_GET["i"])){
	$get = $_GET["i"];
	$get = json_decode($get, true);
	foreach($get as $k=>$v){
		if($v["Course Name"] != ""){
			$courses[] = $v;
		}
	}

	if(count($courses) > 0){
		$options["editMode"] = true;
		$options["numCourse"] = count($courses);
		$numSection = 0;
		$courseData = [];

		foreach($courses as $k => $v){
			$course = ["name" => $v["Course Name"], "FOS" => $v["Field of Study"], "num" => $v["course number"],
				"units" => $v["Units"], "ID" => $k];

			$numSection += count($v["sections"]);
			$sections = [];
			foreach($v["sections"] as $k2 => $section){
				$tempSection = [];
				$tempSection["dayTimes"] = [];

				if(isset($section["crn"])){
					$tempSection["crn"] = $section["crn"];
					unset($section["crn"]);
				}

				foreach($section as $k3 => $dayTimes){
					$tempSection["dayTimes"][] = ["day" => $dayTimes["day"], "fromTime" => $dayTimes["from"],
						"toTime" => $dayTimes["to"]];
				}

				$sections[] = $tempSection;
			}

			$course["sections"] = $sections;
			$courseData[] = $course;
		}

		$options["numSection"] = $numSection;
		$options["courseEdit"] = $courseData;
	}
}


echo generatePug("views/home.pug", "Student Schedule Creator", $options);
