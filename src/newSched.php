<?php
require_once("config.php");

$startTime = microtime(true);
if(isset($_GET["i"])){//check if we received the correct GET request, and redirect back to the input page if not
	$inputData = json_decode(urldecode($_GET["i"]), true);
	if(count($inputData) < 1){
		echo "<script>window.alert('You didn\'t enter any courses!');window.location.assign('" . SUBDIR . "');</script>";
		exit(0);
	}
}
else{
	echo "<script>window.alert('You didn\'t enter any courses!');window.location.assign('" . SUBDIR . "');</script>";
	exit(0);
}

$ingest = new Ingest(urldecode($_GET["i"]));
$ingest->generateSections();

$scheduleGenerator = new ScheduleGenerateBronKerbosch($ingest);

$scheduleGenerator->generateSchedules($ingest->getAllSections());
$numSchedules = $scheduleGenerator->getNumSchedules();

// Get schedules as an array in order of highest score to lowest
$schedules = $scheduleGenerator->getSchedules()->getMaxArray();
$d = $scheduleGenerator->makeDataForCalendarAndList($schedules);

// Generate runtime and max memory used
$runTime = microtime(true) - $startTime;
if($runTime * 1000 < 1000){
	$timeUsed = number_format($runTime * 1000, 0) . " ms";
}
else{
	$timeUsed = number_format($runTime, 3) . " s";
}
$maxMemoryUsed = number_format(memory_get_peak_usage() / 1024, 2);

$options = ["time_used" => $timeUsed, "max_memory_used" => $maxMemoryUsed,
	"numSchedules" => ["num" => $numSchedules, "numStr" => number_format($numSchedules),
		"string" => $scheduleGenerator->plural("Schedule", $numSchedules)],
	"sectionCount" => ["num" => number_format($scheduleGenerator->getSectionCount()),
		"string" => $scheduleGenerator->plural("Section", $scheduleGenerator->getSectionCount())],
	"classCount" => ["num" => number_format($ingest->getClassCount()),
		"string" => $scheduleGenerator->plural("Course", $ingest->getClassCount())],
	"weekSchedule" => $d[0], "listSchedule" => $d[1]];

// Make the schedule view page from the options above
echo generatePug("views/scheduleViewer.pug", "Student Schedule Creator Results", $options);
