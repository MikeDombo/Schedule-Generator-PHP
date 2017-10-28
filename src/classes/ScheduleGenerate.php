<?php

class ScheduleGenerate {
	/** @var Ingest $ingest */
	protected $ingest;
	/** @var \LimitedMinHeap $schedules */
	protected $schedules;
	/** @var int $numSchedules */
	protected $numSchedules = 0;
	/** @var int $sectionCount */
	protected $sectionCount = 0;
	/** @var array $conflictMatrix sparse boolean matrix true if a conflict exists */
	protected $conflictMatrix = [];

	/**
	 * ScheduleGenerate constructor.
	 *
	 * @param Ingest $ingest
	 */
	public function __construct(Ingest $ingest){
		$this->ingest = $ingest;
		$this->schedules = new LimitedMinHeap();
	}

	/**
	 * Generate all schedules
	 *
	 * @param array|Section $allSections
	 */
	public function generateSchedules($allSections){
		$this->sectionCount = count($allSections);
		foreach($allSections as $k => $v){
			unset($allSections[$k]);
			if(!isset($v->meetingTime)){
				continue;
			}
			$this->run($allSections, $v);
		}
	}

	protected function sectionsAreCompatible(\Section $a, \Section $b){
		$aId = $a->getId();
		$bId = $b->getId();

		if($aId == $bId){
			return false;
		}
		if(!isset($this->conflictMatrix[$aId]) || !isset($this->conflictMatrix[$aId][$bId])){
			if(!isset($this->conflictMatrix[$aId])){
				$this->conflictMatrix[$aId] = [];
				$this->conflictMatrix[$bId] = [];
			}

			$conflictStatus = !$a->conflictsWith($b);
			// Put it in both ways because there is a reciprocal relationship
			$this->conflictMatrix[$aId][$bId] = $conflictStatus;
			$this->conflictMatrix[$bId][$aId] = $conflictStatus;
		}

		return $this->conflictMatrix[$aId][$bId];
	}

	/**
	 * Generate all schedules recursively
	 *
	 * @param array|Section $sections list of all sections that can be added
	 * @param Section $pick
	 * @param array|Section $curr current list of sections in the schedule being generated
	 */
	private function run($sections, $pick, $curr = []){
		$curr[] = $pick;
		$temp = $sections;

		$temp = array_filter($temp, function(Section $v) use($pick){
			return $this->sectionsAreCompatible($v, $pick);
		});

		if(count($temp) == 0){
			$requiredCourses = 0;
			$a = new Schedule();
			foreach($curr as $b){
				if($b->isRequiredCourse()){
					$requiredCourses++;
				}
				$a->addSection($b);
			}

			if($requiredCourses == $this->ingest->getRequiredCourseNum()){
				$a->setScore($this->ingest->getMorning());
				$this->schedules->insert($a);
				$this->numSchedules++;
			}
		}
		else{
			foreach($temp as $k => $v){
				unset($temp[$k]);
				$this->run($temp, $v, $curr);
			}
		}
	}

	/**
	 * Makes array of two arrays. The first is for the calendar view and the second is for the list view
	 *
	 * @param array|Schedule $schedules
	 * @return array
	 */
	public function makeDataForCalendarAndList($schedules){
		$weekSchedule = [];
		$listSchedule = [];
		$num = 0;
		/** @var \Schedule $a */
		foreach($schedules as $a){
			$a->generateFirstLastTimes();
			$cpd = $a->getCPD();
			$daysString = [];

			$numDays = $a->getLastTime()[0] - $a->getFirstTime()[0] + 1;
			for($i = $a->getFirstTime()[0]; $i < ($numDays + $a->getFirstTime()[0]); $i++){
				$daysString[] = Schedule::intToDay($i);
			}

			$crnList = "";
			$listRows = [];
			foreach($a->getSchedule() as $b){
				/** @var Section $b */
				foreach($b->getCRN() as $crn){
					if($b->preregistered){
						$crn = "<em>" . $crn . "</em>";
					}
					$crnList = $crnList . ", " . $crn;
				}
				$crns = $b->getCRN()[0];
				foreach($b->getCRN() as $j => $crn){
					if($j == 0){
						continue;
					}
					$crns = $crns . ", " . $crn;
				}
				$listRows[] = ["color" => $this->makeColorString($b->getColor()), "crns" => $crns,
					"coursenum" => $b->getCourseNumber(), "fos" => $b->getFieldOfStudy(),
					"preregistered" => $b->preregistered, "prof" => $b->getProf(),
					"title" => $b->getCourseTitle(), "titleWithDate" => $b->__toString()];
			}

			$listSchedule[intval($num / 4)][] = ["rows" => $listRows, "collapse" => $num >= 4, "num" => $num,
				"hasAllClasses" => $this->ingest->getClassCount() == $a->getNumClasses(),
				"numUnits" => ["num" => $a->getNumUnits(), "string" => $this->plural("unit", $a->getNumUnits())],
				"numClasses" => ["num" => $a->getNumClasses(), "string" => $this->plural("class", $a->getNumClasses())],
				"numCPD" => ["num" => reset($cpd), "string" => $this->plural("class", reset($cpd))],
				"dayCPD" => key($cpd), "crnList" => substr($crnList, 2)
			];

			$timeArray = [];
			foreach($a->getSchedule() as $b){
				foreach($b->meetingTime as $day => $times){
					foreach($times as $time){
						$timeArray[$time["from"]][$day] = $b;
					}
				}
			}
			ksort($timeArray);

			$rows = [];
			$rowCount = 0;
			foreach($timeArray as $k2 => $v){
				$rows[$rowCount] = [];
				$rows[$rowCount]["timestamp"] = date("g:i a", $k2);
				$rows[$rowCount]["rowData"] = [];

				for($i = $a->getFirstTime()[0]; $i < ($numDays + $a->getFirstTime()[0]); $i++){
					/** @var array|\Section $v */
					if(isset($v[Schedule::intToDay($i)])){
						$crns = $v[Schedule::intToDay($i)]->getCRN()[0];
						foreach($v[Schedule::intToDay($i)]->getCRN() as $j => $crn){
							if($j == 0){
								continue;
							}
							$crns = $crns . ", " . $crn;
						}
						$rows[$rowCount]["rowData"][] = ["color" => $this->makeColorString($v[Schedule::intToDay($i)]->getColor()),
							"crns" => $crns, "coursenum" => $v[Schedule::intToDay($i)]->getCourseNumber(),
							"fos" => $v[Schedule::intToDay($i)]->getFieldOfStudy(),
							"preregistered" => $v[Schedule::intToDay($i)]->preregistered,
							"title" => $v[Schedule::intToDay($i)]->getCourseTitle(), "prof" => $v[Schedule::intToDay($i)]->getProf()];
					}
					else{
						$rows[$rowCount]["rowData"][] = ["empty" => true];
					}
				}
				$rowCount += 1;
			}

			$weekSchedule[intval($num / 2)][] = ["rows" => $rows, "daysString" => $daysString,
				"hasAllClasses" => $this->ingest->getClassCount() == $a->getNumClasses(),
				"numUnits" => ["num" => $a->getNumUnits(), "string" => $this->plural("unit", $a->getNumUnits())],
				"numClasses" => ["num" => $a->getNumClasses(), "string" => $this->plural("class", $a->getNumClasses())],
				"numCPD" => ["num" => reset($cpd), "string" => $this->plural("class", reset($cpd))],
				"dayCPD" => key($cpd), "num" => $num, "crnList" => substr($crnList, 2)
			];

			$num += 1;
		}

		return [$weekSchedule, $listSchedule];
	}

	/**
	 * Generates an RGB string from a given array of color values
	 *
	 * @param array|int $color
	 * @return string
	 */
	private function makeColorString($color){
		return $color[0] . ", " . $color[1] . ", " . $color[2];
	}

	/**
	 * Returns the correctly pluralized version of the given word
	 *
	 * @param string $word singular form of the word to be pluralized (or kept singular)
	 * @param int $num number that the word is referring to
	 * @return string
	 */
	public static function plural($word, $num){
		$vowels = ["a", "e", "i", "o", "u"];
		if($num == 1){
			return $word;
		}
		if(mb_substr($word, -1, 1) == "y" && !in_array(mb_substr($word, -2, 1), $vowels, true)){
			return mb_substr($word, 0, mb_strlen($word) - 1) . "ies";
		}
		else if(mb_substr($word, -1, 1) == "s" || mb_substr($word, -1, 1) == "o"){
			return $word . "es";
		}
		else{
			return $word . "s";
		}
	}

	/**
	 * @return \LimitedMinHeap
	 */
	public function getSchedules(){
		return $this->schedules;
	}

	/**
	 * @return int
	 */
	public function getNumSchedules(){
		return $this->numSchedules;
	}

	/**
	 * @return int
	 */
	public function getSectionCount(){
		return $this->sectionCount;
	}

}
