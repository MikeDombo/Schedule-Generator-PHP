<?php

class Ingest {
	/** @var int $sectionId ID for the next section */
	private $sectionId = 0;
	/** @var array|string $requestData Store the GET request */
	private $requestData;
	/** @var array|Section $allSections */
	private $allSections = [];
	/** @var int $classCount */
	private $classCount = 0;


	/**
	 * Ingest constructor.
	 *
	 * @param \MySQLDAL $dal
	 * @param string $data
	 * @internal param \PDO $pdo
	 */
	public function __construct($data){
		$this->requestData = json_decode($data, true);
	}

	public function getAllSections(){
		return $this->allSections;
	}

	public function getClassCount(){
		return $this->classCount;
	}

	/**
	 * Generates entries in the private variable $allSections
	 */
	public function generateSections(){
		foreach($this->requestData as $course){
			if($course["Course Name"] != ""){
				if($course["course number"] == ""){
					$course["course number"] = "0";
				}
				$this->classCount++;
				$classColor = $this->generateColor([255, 255, 255]);

				foreach($course["sections"] as $k=>$b){
					$crn = $k;
					if(isset($b["crn"])){
						$crn = $b["crn"];
						unset($b["crn"]);
					}
					$temp = new Section($this->sectionId, $course["Course Name"], $course["Field of Study"], $course["course number"],
						floatval($course["Units"]), [$crn]);
					foreach($b as $c){
						$temp->addTime($c["day"], $c["from"], $c["to"]);
					}
					$temp->setColor($classColor);
					$this->allSections[] = $temp;
					$this->sectionId++;
				}
			}
		}
	}

	/**
	 * Generates a random RGB array
	 *
	 * @param array|int $c array of 3 offsets
	 * @return array|int
	 */
	private function generateColor($c){
		$red = rand(0, 255);
		$green = rand(0, 255);
		$blue = rand(0, 255);
		$red = ($red + $c[0]) / 2;
		$green = ($green + $c[1]) / 2;
		$blue = ($blue + $c[2]) / 2;

		return [intval($red), intval($green), intval($blue)];
	}

}
