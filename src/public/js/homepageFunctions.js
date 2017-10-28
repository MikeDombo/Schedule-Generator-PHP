var $courseTemplate = $(".course-template");
var $sectionTemplate = $(".section-template");
var $timeTemplate = $(".time-template");
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
	if(lastDay === "Monday"){
		nextDay = "Wednesday";
	}
	else if(lastDay === "Wednesday" || lastDay === "Thursday"){
		nextDay = "Friday";
	}
	else if(lastDay === "Tuesday"){
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
		window.location.assign("makeSchedule.php?i="+encodeURIComponent(json));
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
