<?php
$courses = array();
if(isset($_GET["i"])){
	$get = json_decode(urldecode($_GET["i"]), true);
	foreach($get as $k=>$v){
		if($v["Course Name"] != ""){
			array_push($courses, $v);
		}
	}
}
$get = $courses;
unset($courses);
$sections = 1;
?>

<html>
	<head>
		<title>Student Schedule Creator</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="css/bootstrap.min.css"></link>
        <script type="text/javascript" src="js/jquery.min.js"></script>
        <script type="text/javascript" src="js/bootstrap.min.js"></script>
		<script src="//cdn.jsdelivr.net/jquery.scrollto/2.1.2/jquery.scrollTo.min.js"></script>
		<script>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
		  ga('create', 'UA-69105822-1', 'auto');
		  ga('send', 'pageview');
		  window.alert('If you are a University of Richmond student, there is a specialized version of this website available at http://mikedombrowski.com/ur');
		</script>
	</head>
	<body>
		<div class="container-fluid">
			<h2>Make a Schedule</h2>
			<div class="row">
				<div class="panel-group" id="all-courses">
					<?php
						if(count($get)<1){
							$sections += 1;
							echo '<div class="panel panel-default" style="margin-bottom:5px;">
									<div class="panel-heading">
									<h1 class="panel-title pull-left" id="course1"></h1>
									<form class = "form-inline pull-left course-control" role="form" autocomplete="off">
										<div class="entry form-group course-control has-error">
											 Name<label style="color:red;">*</label>: <input id="name1" class="form-control name" name="fields[]" type="text" placeholder="Enter Course Name"/>
											 Field of Study: <input class="form-control fos" name="fields[]" type="text" placeholder="ex. CMSC" style="text-transform: uppercase" maxlength="4"/>
											 Course Number: <input class="form-control cn" name="fields[]" type="text" placeholder="ex. 101" maxlength="3"/>
											 Number of Units: <input class="form-control units" name="fields[]" type="text" placeholder="ex. 1" maxlength="3"/>
										</div>
									</form>
									<button class="btn btn-success btn-add pull-right btn-add-course" type="button">
										<span class="glyphicon glyphicon-plus"></span>
									</button>
									<button class="btn btn-danger btn-remove pull-right" type="button">
										<span class="glyphicon glyphicon-minus"></span>
									</button>
									<div class="clearfix"></div>
								</div>
								
								<div class="panel-body">
									<h3>Enter Section Details</h3>
									<div class="col-md-4">
										<div class="panel panel-default">
											<div class="panel-heading">
												<h1 class="panel-title pull-left new-course" id="course1">Section 1</h1>
												<button class="btn btn-success btn-add pull-right btn-add-section glyphicon glyphicon-plus" type="button" style="line-height: 1!important;">
												</button>
												<button class="btn btn-danger btn-remove-section pull-right" type="button" style="line-height: 1!important;">
													<span class="glyphicon glyphicon-minus"></span>
												</button>
												<div class="clearfix"></div>
											</div>
											<div class="panel-body">
												<form class = "form section-control" role="form" autocomplete="off">
													<input type="text" placeholder="CRN Number" class="form-control crn" style="margin:1px;"/>
													<div>
														<div class="row col-md-12 input-group" style="margin:1px;">
															<select class="form-control day">
																<option>Monday</option>
																<option>Tuesday</option>
																<option>Wednesday</option>
																<option>Thursday</option>
																<option>Friday</option>
																<option>Saturday</option>
																<option>Sunday</option>
															</select>
															<span class="input-group-btn">
																<button class="btn btn-danger btn-remove-time glyphicon glyphicon-minus" type="button" style="line-height: 1!important;">
																</button>
																<button class="btn btn-success btn-add btn-add-time glyphicon glyphicon-plus" type="button" style="line-height: 1!important;">
																</button>
														   </span>
														</div>
														<div class="row col-md-12">
															<div class="col-md-6 bootstrap-timepicker timepicker">
																<label class="control-label">From:</label>
																<input id="timepicker1" type="text" class="form-control input-small from time">
																<span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
															</div>
															<div class="col-md-6 bootstrap-timepicker timepicker">
																<label class="control-label">To:</label>
																<input id="timepicker2" type="text" class="form-control input-small to time">
																<span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
															</div>
														</div>
													</div>
												</form>
											</div>
										</div>
									</div>
								</div>
							</div>
							';
						}
						foreach($get as $k=>$v){
							echo '<div class="panel panel-default" style="margin-bottom:5px;">
									<div class="panel-heading">
								<h1 class="panel-title pull-left" id="course'.$k.'">'.$v["Course Name"].'</h1><br/><hr id="hr'.$k.'" style="width:100%; border-top:1px solid #FFFFFF;">
									<form class = "form-inline pull-left course-control" role="form" autocomplete="off">
										<div class="entry form-group course-control">
											 Name<label style="color:red;">*</label>: <input id="name'.$k.'" class="form-control name" name="fields[]" type="text" placeholder="Enter Course Name" value="'.$v["Course Name"].'"/>
											 Field of Study: <input class="form-control fos" name="fields[]" type="text" placeholder="ex. CMSC" style="text-transform: uppercase" maxlength="4" value="'.$v["Field of Study"].'"/>
											 Course Number: <input class="form-control cn" name="fields[]" type="text" placeholder="ex. 101" maxlength="3" value="'.$v["course number"].'"/>
											 Number of Units: <input class="form-control units" name="fields[]" type="text" placeholder="ex. 1" maxlength="3" value="'.$v["Units"].'"/>
										</div>
									</form>
									<button class="btn btn-success btn-add pull-right btn-add-course" type="button">
										<span class="glyphicon glyphicon-plus"></span>
									</button>
									<button class="btn btn-danger btn-remove pull-right" type="button">
										<span class="glyphicon glyphicon-minus"></span>
									</button>
									<div class="clearfix"></div>
								</div>
								
								<div class="panel-body">
									<h3>Enter Section Details</h3>';
							foreach($v["sections"] as $k2=>$v2){
								if(!isset($v2["crn"])){
									$v2["crn"] = "";
								}
								echo '
									<div class="col-md-4">
										<div class="panel panel-default">
											<div class="panel-heading">
												<h1 class="panel-title pull-left new-course" id="course'.$k.'">Section '.$sections.'</h1>
												<button class="btn btn-success btn-add pull-right btn-add-section glyphicon glyphicon-plus" type="button" style="line-height: 1!important;">
												</button>
												<button class="btn btn-danger btn-remove-section pull-right" type="button" style="line-height: 1!important;">
													<span class="glyphicon glyphicon-minus"></span>
												</button>
												<div class="clearfix"></div>
											</div>
											<div class="panel-body">
												<form class = "form section-control" role="form" autocomplete="off">
													<input type="text" placeholder="CRN Number" class="form-control crn" style="margin:1px;" value="'.$v2["crn"].'"/>';
								foreach($v2 as $k3=>$v3){
									if(isset($v3["day"]) && isset($v3["to"]) && isset($v3["from"])){
										echo '
										<div>
											<div class="row col-md-12 input-group" style="margin:1px;">
												<select class="form-control day">
													<option ';
													if($v3["day"] == "Monday"){
														echo "selected=\"selected\"";
													}
													echo '>Monday</option>
													<option ';
													if($v3["day"] == "Tuesday"){
														echo "selected=\"selected\"";
													}
													echo '>Tuesday</option>
													<option ';
													if($v3["day"] == "Wednesday"){
														echo "selected=\"selected\"";
													}
													echo '>Wednesday</option>
													<option ';
													if($v3["day"] == "Thursday"){
														echo "selected=\"selected\"";
													}
													echo '>Thursday</option>
													<option ';
													if($v3["day"] == "Friday"){
														echo "selected=\"selected\"";
													}
													echo '>Friday</option>
													<option ';
													if($v3["day"] == "Saturday"){
														echo "selected=\"selected\"";
													}
													echo '>Saturday</option>
													<option ';
													if($v3["day"] == "Sunday"){
														echo "selected=\"selected\"";
													}
													echo '>Sunday</option>
												</select>
												<span class="input-group-btn">
													<button class="btn btn-danger btn-remove-time glyphicon glyphicon-minus" type="button" style="line-height: 1!important;">
													</button>
													<button class="btn btn-success btn-add btn-add-time glyphicon glyphicon-plus" type="button" style="line-height: 1!important;">
													</button>
											   </span>
											</div>
											<div class="row col-md-12">
												<div class="col-md-6 bootstrap-timepicker timepicker">
													<label class="control-label">From:</label>
													<input id="timepicker1" type="text" class="form-control input-small from time" value="'.$v3["from"].'">
													<span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
												</div>
												<div class="col-md-6 bootstrap-timepicker timepicker">
													<label class="control-label">To:</label>
													<input id="timepicker2" type="text" class="form-control input-small to time" value="'.$v3["to"].'">
													<span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
												</div>
											</div>
										</div>';
									}
								}
								echo '
										</form>
									</div>
								</div>
							</div>';
								$sections += 1;
							}
							echo '
							</div>
						</div>';
						}
					?>
				</div>
			</div>
			<div class="row col-sm-6">
				<button class="btn btn-success btn-submit glyphicon glyphicon-check">  Submit</button>
			</div>
			
			<div class="hide">
				<div class="panel panel-default course-template">
					<div class="panel-heading">
						<h1 class="panel-title pull-left" id="course1"></h1>
						<form class = "form-inline pull-left course-control" role="form" autocomplete="off">
							<div class="entry form-group course-control has-error">
								 Name<label style="color:red;">*</label>: <input id="name1" class="form-control name" name="fields[]" type="text" placeholder="Enter Course Name" required="required"/>
								 Field of Study: <input class="form-control fos" name="fields[]" type="text" placeholder="ex. CMSC" style="text-transform: uppercase" maxlength="4"/>
								 Course Number: <input class="form-control cn" name="fields[]" type="text" placeholder="ex. 101" maxlength="3"/>
								 Number of Units: <input class="form-control units" name="fields[]" type="text" placeholder="ex. 1" maxlength="3"/>
							</div>
						</form>
						<button class="btn btn-success btn-add pull-right btn-add-course" type="button">
							<span class="glyphicon glyphicon-plus"></span>
						</button>
						<button class="btn btn-danger btn-remove pull-right" type="button">
							<span class="glyphicon glyphicon-minus"></span>
						</button>
						<div class="clearfix"></div>
					</div>
					
					<div class="panel-body">
						<h3>Enter Section Details</h3>
						<div class="col-md-4 section-template">
							<div class="panel panel-default">
								<div class="panel-heading">
									<h1 class="panel-title pull-left new-course" id="course1">Section 1</h1>
									<button class="btn btn-success btn-add pull-right btn-add-section glyphicon glyphicon-plus" type="button" style="line-height: 1!important;">
									</button>
									<button class="btn btn-danger btn-remove-section pull-right" type="button" style="line-height: 1!important;">
										<span class="glyphicon glyphicon-minus"></span>
									</button>
									<div class="clearfix"></div>
								</div>
								<div class="panel-body">
									<form class = "form section-control" role="form" autocomplete="off">
										<input type="text" placeholder="CRN Number" class="form-control crn" style="margin:1px;"/>
										<div class="time-template">
											<div class="row col-md-12 input-group" style="margin:1px;">
												<select class="form-control day">
													<option>Monday</option>
													<option>Tuesday</option>
													<option>Wednesday</option>
													<option>Thursday</option>
													<option>Friday</option>
													<option>Saturday</option>
													<option>Sunday</option>
												</select>
												<span class="input-group-btn">
													<button class="btn btn-danger btn-remove-time glyphicon glyphicon-minus" type="button" style="line-height: 1!important;">
													</button>
													<button class="btn btn-success btn-add btn-add-time glyphicon glyphicon-plus" type="button" style="line-height: 1!important;">
													</button>
											   </span>
											</div>
											<div class="row col-md-12">
												<div class="col-md-6 bootstrap-timepicker timepicker">
													<label class="control-label">From:</label>
													<input id="timepicker1" type="text" class="form-control input-small from time">
													<span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
												</div>
												<div class="col-md-6 bootstrap-timepicker timepicker">
													<label class="control-label">To:</label>
													<input id="timepicker2" type="text" class="form-control input-small to time">
													<span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="no-name-error" class="modal fade" tabindex="-1" role="dialog">
			<div class="modal-dialog has-error">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">×</button>
						<h3>Error: No Course Name Entered!</h3>
					</div>
				</div>
			</div>
		</div>
		<div id="bad-time-error" class="modal fade" tabindex="-1" role="dialog">
			<div class="modal-dialog has-error">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">×</button>
						<h3>Error: Incorrect Time Entered!</h3>
					</div>
				</div>
			</div>
		</div>
			<div class="alert alert-warning alert-dismissible hide" id="course-delete" role="alert" style="position:fixed; z-index:500; width:50%; margin:auto;  left:0; right:0; top:20px;">
				<button type="button" class="close" href="#">&times;</button>
				<strong>Course Deleted!</strong>&nbsp;<a href="#" class="alert-link pull-right undo">Undo</a>
			</div>
			<div class="alert alert-warning alert-dismissible hide" id="section-delete" role="alert" style="position:fixed; z-index:500; width:50%; margin:auto;  left:0; right:0; top:20px;">
				<button type="button" class="close" href="#">&times;</button>
				<strong>Section Deleted!</strong>&nbsp;<a href="#" class="alert-link pull-right undo">Undo</a>
			</div>
			<div class="alert alert-warning alert-dismissible hide" id="time-delete" role="alert" style="position:fixed; z-index:500; width:50%; margin:auto;  left:0; right:0; top:20px;">
				<button type="button" class="close" href="#">&times;</button>
				<strong>Time Deleted!</strong>&nbsp;<a href="#" class="alert-link pull-right undo">Undo</a>
			</div>
		</div>
		<script>
			var $courseTemplate = $(".course-template");
			var $sectionTemplate = $(".section-template");
			var $timeTemplate = $(".time-template");
			var numCourse = <?php if(isset($get)){echo count($get);} else{echo "1";}?>;
			var numSection = <?php echo $sections-1;?>;
			var $undo;
			var $undoParent;
			$(document).ready(function(){
				$("#course-delete").hide();
				$("#course-delete").removeClass("hide");
				$("#section-delete").hide();
				$("#section-delete").removeClass("hide");
				$("#time-delete").hide();
				$("#time-delete").removeClass("hide");
				
				$("input.from").focus();
				$("input.from").blur();
				$("input.to").focus();
				$("input.to").blur();
			});
			
			$(document).on("click", ".btn-remove", function(e){
				var $hider = $(e.target).parent().parent();
				while($hider.attr("class").indexOf("panel-default") < 0){
					$hider = $hider.parent();
				}
				$undo = $hider;
				$hider.remove();
				numCourse--;
				$("#course-delete").alert();
				$("#course-delete").find(".undo").on("click", function(f){
					f.stopImmediatePropagation();
					$("#all-courses").append($undo);
					$('body').scrollTo($undo, 500);
					$("#course-delete").hide();
					numCourse++;
				});
				$('#course-delete').find(".close").on('click', function () {
					$("#course-delete").hide();
				});
				$("#course-delete").fadeTo(10000, 500).slideUp(500, function(){
				});
			});
			
			$(document).on("click", ".btn-remove-section", function(e){
				var $hider = $(e.target).parent().parent();
				while($hider.attr("class").indexOf("col-md-4") < 0){
					$hider = $hider.parent();
				}
				$undo = $hider;
				$undoParent = $hider.parent();
				$hider.remove();
				numSection--;
				$("#section-delete").alert();
				$("#section-delete").find(".undo").on("click", function(f){
					f.stopImmediatePropagation();
					$undoParent.append($undo);
					$('body').scrollTo($undo, 500);
					numSection++;
					$("#section-delete").hide();
				});
				$('#section-delete').find(".close").on('click', function () {
					$("#section-delete").hide();
				});
				$("#section-delete").fadeTo(10000, 500).slideUp(500, function(){
				});
			});
			
			$(document).on("click", ".btn-remove-time", function(e){
				var $hider = $(e.target);
				while($hider.attr("class").indexOf("row col-md-12 input-group") < 0){
					$hider = $hider.parent();
				}
				$hider = $hider.parent();
				$undo = $hider;
				$undoParent = $hider.parent();
				$hider.remove();
				$("#time-delete").alert();
				$("#time-delete").find(".undo").on("click", function(f){
					f.stopImmediatePropagation();
					$undoParent.append($undo);
					$('body').scrollTo($undo, 500);
					$("#time-delete").hide();
				});
				$('#time-delete').find(".close").on('click', function () {
					$("#time-delete").hide();
				});
				$("#time-delete").fadeTo(10000, 500).slideUp(500, function(){
				});
			});
			
			$(document).on("click", ".btn-add-course", function (e) {
				var $newPanel = $courseTemplate.clone();
				numCourse++;
				$newPanel.find("h1").attr("id", "course"+numCourse);
				$newPanel.find("#name1").attr("id", "name"+numCourse);
				$newPanel.find("h1").each(function(){
						if($(this).attr("class").indexOf("new-course")>-1){
							$(this).text("Section "+(++numSection));
						}
				});
				$newPanel.removeClass("course-template");
				$("#all-courses").append($newPanel);
				$('body').scrollTo($newPanel, 500);
				
				$newPanel.find("input.from").focus();
				$newPanel.find("input.from").blur();
				$newPanel.find("input.to").focus();
				$newPanel.find("input.to").blur();
			});
			
			$(document).on("click", ".btn-add-section", function (e) {
				var $newPanel = $sectionTemplate.clone();
				$newPanel.find("h1").text("Section "+(++numSection));
				$(e.target).parent().parent().parent().parent().append($newPanel);
				$('body').scrollTo($newPanel, 500);
				
				$newPanel.find("input.from").focus();
				$newPanel.find("input.from").blur();
				$newPanel.find("input.to").focus();
				$newPanel.find("input.to").blur();
			});
			
			$(document).on("click", ".btn-add-time", function (e) {
				var $newPanel = $timeTemplate.clone();
				var lastDay = $(e.target).parent().parent().parent().parent().last().find("select").last().val();
				var fromTime = $(e.target).parent().parent().parent().last().find("input").first().val();
				var toTime = $(e.target).parent().parent().parent().find("input").last().val();
				var nextDay = lastDay;
				if(lastDay == "Monday"){
					nextDay = "Wednesday";
				}
				else if(lastDay == "Wednesday" || lastDay == "Thursday"){
					nextDay = "Friday";
				}
				else if(lastDay == "Tuesday"){
					nextDay = "Thursday"
				}
				$newPanel.find("input.from").val(fromTime);
				$newPanel.find("input.to").val(toTime);
				$newPanel.find("select").val(nextDay);
				$newPanel.removeClass("time-template");
				
				$(e.target).parent().parent().parent().parent().append($newPanel);
				
				$newPanel.find("input.from").focus();
				$newPanel.find("input.from").blur();
				$newPanel.find("input.to").focus();
				$newPanel.find("input.to").blur();
			});
			
			$(document).on("click", ".btn-submit", function (e) {
				$("input.from").focus();
				$("input.from").blur();
				$("input.to").focus();
				$("input.to").blur();
				
				var name = true;
				var time = true;
				var $form = $("form");
				var output = {};
				var courseNum = -1;
				var numSection = 0;
				var numDay = 0;
				$form.each(function(){
					var c = $(this).attr("class");
					if(c.indexOf("course-control")>-1){
						courseNum++;
						numSection = 0;
						if(output[courseNum] == undefined){
							output[courseNum] = {};
						}
						
						$(this).find("input, select").each(function(){
							var d = $(this).attr("class");
							if(d.indexOf("name")>-1){
								if($(this).val() == "" && !($(this).parent().parent().parent().parent().attr("class").indexOf("template")>-1)){
									name = false;
								}
								output[courseNum]["Course Name"] = $(this).val();
							}
							if(d.indexOf("fos")>-1){
								output[courseNum]["Field of Study"] = $(this).val();
							}
							if(d.indexOf("cn")>-1){
								output[courseNum]["course number"] = $(this).val();
							}
							if(d.indexOf("units")>-1){
								output[courseNum]["Units"] = $(this).val();
							}
						});
					}
					
					if(c.indexOf("section-control")>-1){
						if(output[courseNum] == undefined){
							output[courseNum] = {};
						}
						if(output[courseNum]["sections"] == undefined){
							output[courseNum]["sections"] = {};
						}
						
						$(this).find("input, select").each(function(){
							var d = $(this).attr("class");
							
							if(output[courseNum]["sections"][numSection] == undefined){
								output[courseNum]["sections"][numSection] = {};
							}
							if(output[courseNum]["sections"][numSection][numDay] == undefined){
								output[courseNum]["sections"][numSection][numDay] = {};
							}
							
							if(d.indexOf("crn")>-1){
								output[courseNum]["sections"][numSection]["crn"] = $(this).val();
							}
							if(d.indexOf("day")>-1){
								output[courseNum]["sections"][numSection][numDay]["day"] = $(this).val();
							}
							if(d.indexOf("to")>-1){
								if($(this).parent().attr("class").indexOf("has-error")>-1){
									time = false;
								}
								else{
									output[courseNum]["sections"][numSection][numDay]["to"] = $(this).val();
								}
							}
							if(d.indexOf("from")>-1){
								if($(this).parent().attr("class").indexOf("has-error")>-1){
									time = false;
								}
								else{
									output[courseNum]["sections"][numSection][numDay]["from"] = $(this).val();
								}
							}
							var a = output[courseNum]["sections"][numSection][numDay];
							if(a["day"] != undefined && a["to"] != undefined && a["from"] != undefined){
								numDay++;
							}
						});
						numDay = 0;
						numSection++;
					}
				});
				if(!name){
					$('#no-name-error').modal({
						show: true
					});
					$('#no-name-error').on('hidden', function(){
						$('body').scrollTo($(".has-error").first(), 500);
					});
				}
				else if(!time){
					$('#bad-time-error').modal({
						show: true
					});
				}
				else{
					var json = JSON.stringify(output);
					window.location.assign("/sched/makeSched.php?i="+encodeURIComponent(json));
				}
			});
			
			$(document).on('keyup', ".name", function(e){
				var id = e.target.getAttribute("id");
				id = id.substring(5, id.length-1);
				$('#course'+id).html($('#name'+id).val());
				if($('#name'+id).val() == ""){
					$('#name'+id).parent().addClass("has-error");
				}
				else if ($('#name'+id).parent().attr("class").indexOf("has-error")>-1){
					$('#name'+id).parent().removeClass("has-error");
				}
				if(!$("#hr"+id).length){
					$("<br/><hr id=\"hr"+id+"\" style=\"width:100%; border-top:1px solid #FFFFFF;\"/>").insertAfter("#course"+id);
				}
			  });
			  
			$(document).on('blur', ".time", function(e){
				timeCheck(e);
			});
			
			function timeCheck(e){
				var val = $(e.target).val();
				var error = false;
				val = val.replace(/\s+/g, '');
				val = val.replace(/\:/g, '');
				var finalTime = "";
				if(val.substring(val.length-2, val.length).toLowerCase() == "am"){
					if(val.length-2 == 3){
						if(parseInt(val.substring(0, 1))<1 || parseInt(val.substring(1, 3))>59){
							$(e.target).parent().addClass("has-error");
							error = true;
							finalTime = $(e.target).val();
						}
						else{
							finalTime = val.substring(0, 1)+":"+val.substring(1, 3)+" AM";
						}
				  }
				  else if(val.length-2 == 4){
					  if(parseInt(val.substring(0, 1))<1 || parseInt(val.substring(0, 2))>12 || parseInt(val.substring(2, 4))>59){
						  $(e.target).parent().addClass("has-error");
						  error = true;
						  finalTime = $(e.target).val();
					  }
					  else{
						finalTime = val.substring(0, 2)+":"+val.substring(2, 4)+" AM";
					  }
				  }
				  else{
					  $(e.target).parent().addClass("has-error");
					  error = true;
				  }
				}
				else if(val.substring(val.length-2, val.length).toLowerCase() == "pm"){
				  if(val.length-2 == 3){
					  if(parseInt(val.substring(0, 1))<1 || parseInt(val.substring(1, 3))>59){
						  $(e.target).parent().addClass("has-error");
						  error = true;
						  finalTime = $(e.target).val();
					  }
					  else{
						finalTime = val.substring(0, 1)+":"+val.substring(1, 3)+" PM";
					  }
				  }
				  else if(val.length-2 == 4){
					  if(parseInt(val.substring(0, 1))<1 || parseInt(val.substring(0, 2))>12 || parseInt(val.substring(2, 4))>59){
						  $(e.target).parent().addClass("has-error");
						  error = true;
						  finalTime = $(e.target).val();
					  }
					  else{
						finalTime = val.substring(0, 2)+":"+val.substring(2, 4)+" PM";
					  }
				  }
				  else{
					  $(e.target).parent().addClass("has-error");
					  error = true;
				  }
				}
				else{
				  if(val.length == 3){
					  if(parseInt(val.substring(1, 3))>59){
						$(e.target).parent().addClass("has-error");
						error = true;
						finalTime = $(e.target).val();
					  }
					  else{
						finalTime = val.substring(0, 1)+":"+val.substring(1, 3)+" AM";
					  }
				  }
				  else if(val.length == 4){
					  if(parseInt(val.substring(2, 4))>59){
						$(e.target).parent().addClass("has-error");
						error = true;
						finalTime = $(e.target).val();
					  }
					  else{
						  var ampm = (parseInt(val.substring(0, 2))<=12) ? " AM" : " PM";
						  var hour = parseInt(val.substring(0, 2))<=12 ? parseInt(val.substring(0, 2)) : parseInt(val.substring(0, 2))-12;
						  finalTime = hour+":"+val.substring(2, 4)+ampm;
					  }
				  }
				  else{
					  $(e.target).parent().addClass("has-error");
					  error = true;
				  }
				}
				
				var parentEls = $(e.target).parents().map(function() {return this.className;}).get().join( " " );
				if(parentEls.indexOf("hide")>-1){
					error = false;
				}
				
				if(!error){
					$(e.target).parent().removeClass("has-error");
				}
				$(e.target).val(finalTime);
			}
			
			$(document).on('hidden.bs.modal', '#bad-time-error', function(){
				$('body').scrollTo($(".has-error").first(), 500);
			});
			$(document).on('hidden.bs.modal', '#no-name-error', function(){
				$('body').scrollTo($(".has-error").first(), 500);
			});
		</script>
	</body>
</html>