<?php
spl_autoload_register(function ($class) {
    include "Course.php";
	include "Schedule.php";
	include "Section.php";
});
$get = json_decode(urldecode($_GET['i']), true);

$sections = array();
foreach($get as $a){
	if($a["Course Name"] != ""){
		if($a["course number"] == ""){
			$a["course number"] = "0";
		}
		foreach($a["sections"] as $k=>$b){
			$crn = $k;
			if(isset($b["crn"])){
				$crn = $b["crn"];
				unset($b["crn"]);
			}
			$temp = new Section($a["Course Name"], $a["Field of Study"], $a["course number"], floatval($a["Units"]), $crn);
			foreach($b as $c){
				$temp->addTime($c["day"], $c["from"], $c["to"]);
			}
			$temp->setColor(generateColor(array(127, 127, 127)));
			array_push($sections, $temp);
		}
	}
}

$GLOBALS['schedules'] = array();
$temp = $sections;

foreach($temp as $k=>$v){
	unset($temp[$k]);
	$curr = array();
	run($temp, $curr, $v);
}

usort($GLOBALS['schedules'], "sortSched");

$finalSchedules = $GLOBALS['schedules'];

function run($sections, $curr, $pick){
	array_push($curr, $pick);
	$temp = $sections;
	foreach($temp as $k=>$v){
		if($v->conflictsWith($pick)){
			unset($temp[$k]);
		}
	}
	if(count($temp)==0){
		$a = new Schedule();
		foreach($curr as $b){
			$a->addSection($b);
		}
		$a->setScore();
		array_push($GLOBALS['schedules'], $a);
	}
	else{
		foreach($temp as $k=>$v){
			unset($temp[$k]);
			run($temp, $curr, $v);
		}
	}
}

function sortSched($a, $b){
	if($a==$b){
		return 0;
	}
	return ($a->getScore() > $b->getScore()) ? -1 : 1;
}

function makeColorString($color){
	 return $color[0].", ".$color[1].", ".$color[2];
}

function printWeek($a){
	$numDays = $a->getLastTime()[0] - $a->getFirstTime()[0] + 1;
	echo "<table class='table table-responsive table-bordered'>";
	echo "<tr>";
	echo "<td></td>";
		for($i = $a->getFirstTime()[0]; $i<($numDays+$a->getFirstTime()[0]); $i++){
			echo "<td>";
			echo $a->intToDay($i);
			echo "</td>";
		}
	echo "</tr>";
	
	$timeArray = array();
	foreach($a->getSchedule() as $k=>$b){
		foreach($b->meetingTime as $day=>$times){
			$timeArray[$times["from"]][$day] = $b;
		}
	}
	ksort($timeArray);
	
	foreach($timeArray as $k=>$v){
		echo "<tr>";
		echo "<td>";
		echo date("g:i a", $k);
		echo "</td>";
		for($i = $a->getFirstTime()[0]; $i<($numDays+$a->getFirstTime()[0]); $i++){
			if(isset($v[$a->intToDay($i)])){
				echo "<td style='color: #000000; background:rgba(".makeColorString($v[$a->intToDay($i)]->getColor()).", .60)'>";
				echo $v[$a->intToDay($i)]->getCourseTitle();
			}
			else{
				echo "<td>";
			}
			echo "</td>";
		}
		echo "</tr>";
	}
	
	echo "</table>";
}

function generateColor($c){
	$red = rand(0, 255);
	$green = rand(0, 255);
	$blue = rand(0, 255);
	$red = ($red + $c[0]) / 2;
	$green = ($green + $c[1]) / 2;
	$blue = ($blue + $c[2]) / 2;
	return array(intval($red), intval($green), intval($blue));
}
?>

<html>
	<head>
		<title>Student Schedule Creator</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="css/bootstrap.min.css"></link>
        <script type="text/javascript" src="js/jquery.min.js"></script>
        <script type="text/javascript" src="js/bootstrap.min.js"></script>
		<script>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
		  ga('create', 'UA-69105822-1', 'auto');
		  ga('send', 'pageview');
		</script>
	</head>
	<body>
	<script>
		$(document).ready(function(){
			$('[data-toggle="popover"]').popover();   
		});
		
		$(document).on("click", ".btn-expand", function (e) {
			$('.collapse:not(.in)').each(function (index) {
				$(this).addClass("in");
			});
			$(this).text(' Collapse All Schedules');
			$(this).removeClass("glyphicon-collapse-down btn-expand");
			$(this).addClass("glyphicon-collapse-up btn-collapse");
		});
		
		$(document).on("click", ".btn-collapse", function (e) {
			$('.collapse:not(.in)').each(function (index) {
				$(this).collapse("toggle");
			});
			$('.collapse.in').each(function (index) {
				$(this).removeClass("in");
			});
			$(this).text(' Expand All Schedules');
			$(this).addClass("glyphicon-collapse-down btn-expand");
			$(this).removeClass("glyphicon-collapse-up btn-collapse");
		});
		
		$(document).on("click", ".btn-calview", function (e) {
			$(this).text(' List View');
			$(this).addClass("glyphicon-list btn-listview");
			$(this).removeClass("glyphicon-calendar btn-calview");
			$('#list-view').addClass("hide");
			$('#calendar-view').removeClass("hide");
		});
		
		$(document).on("click", ".btn-listview", function (e) {
			$(this).text(' Calendar View');
			$(this).removeClass("glyphicon-list btn-listview");
			$(this).addClass("glyphicon-calendar btn-calview");
			$('#list-view').removeClass("hide");
			$('#calendar-view').addClass("hide");
		});
	</script>
		<div class="container-fluid">
			<div class="row col-md-12">				
				<div class="row col-sm-12"><div class="col-sm-6"><?php echo "<h1><strong>".count($finalSchedules)."</strong>&nbsp;Schedules Generated</h1>";?>
				</div><div class="col-sm-6"><h1 class="pull-right"><a style="color:black;" href="/sched/?i=<?php echo urlencode(json_encode($get));?>"><button class="btn btn-sucess" type="button">Edit Sections</button></a>
				&nbsp;&nbsp;<button class="btn btn-success btn-expand glyphicon glyphicon-collapse-down" type="button"> Expand All Schedules</button>
				&nbsp;&nbsp;<button class="btn-listview btn glyphicon glyphicon-list" type="button"> List View</button></h1></div></div>
				<hr width="100%" />
				
				<div class="panel-group" id="calendar-view">
					<?php 
					$num = 0;
					foreach($finalSchedules as $k=>$a){
						if($num%2==0){
							echo "<div class='row' style='margin:2px;'>";
						}
						echo "<div class='col-md-6'>";
						echo "<div class='panel panel-default'>";
						echo "<div class='panel-heading panel-title'>";
						echo "<h4 style='color: #000000;'>".$a->getNumClasses()." classes, ".$a->getNumUnits()." units, with ".reset($a->getCPD())." classes every ".key($a->getCPD())."</h4></div>";
						echo "<div class='panel-body table-condensed' id='calendar".$num."'>";
						
						printWeek($a);
						
						echo "</div></div></div>";
						if($num%2==1){
							echo "</div>";
						}
						$num+=1;
					}
					?>
				</div>
				</div>
				
				<div class="panel-group hide" id="list-view">
					<?php 
					$num = 0;
					foreach($finalSchedules as $k=>$a){
						if($num%4==0){
							echo "<div class='row' style='margin:2px;'>";
						}
						$in = "";
						if($num<4){
							$in = " in";
						}
						echo "<div class='col-md-3'>";
						echo "<div class='panel panel-default'>";
						echo "<div class='panel-heading panel-title' data-toggle='collapse' data-target='#collapse".$num."' style='cursor: pointer;'>";
						echo "<a data-toggle='collapse' href='#collapse".$num."'>".$a->getNumClasses()." classes, ".$a->getNumUnits()." units, with ".reset($a->getCPD())." classes every ".key($a->getCPD())."</a></div>";
						echo "<div class='panel-collapse collapse panel-body".$in."' id='collapse".$num."'>";
						echo "<table class='table table-condensed table-responsive'>";
						foreach($a->getSchedule() as $b){
							echo "<tr><td style='background:rgba(".makeColorString($b->getColor()).", .65)'>";
							echo $b;
							echo "</tr></td>";
						}
						echo "</table></div></div></div>";
						if($num%4==3 || $k==count($GLOBALS['schedules'])){
							echo "</div>";
						}
						$num += 1;
					}
					?>
				</div>
				</div>
			</div>
		</div>
	</body>
</html>
