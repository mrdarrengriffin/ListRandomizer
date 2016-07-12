<?php
	
	//===================
	// List creation
	//===================
	
	// Parses the config file to obtain settings
	$settings = parse_ini_file('config.ini',1);
	
	// Reads file for list seperated by line breaks
	$listItems = file_get_contents("list.txt");
	
	// Converts the list into an array
	$listArray = parseItemList($listItems);
	
	// Checks config to see if the list should be randomized or repeated
	if($settings['Settings']['randomize']){
		$randomList = createRandomList($listArray,$settings['Settings']['lines'],($settings['Settings']['output_to_file'] == true) ? "\r\n" : "<br>",$settings['Settings']['prevent_duplicate_lines']);
		}else{
		$randomList = createList($listArray,$settings['Settings']['lines'],($settings['Settings']['output_to_file'] == true) ? "\r\n" : "<br>");
	}
	
	// If config is set to output to a file, the list will be outputted to the corresponding file with the specified seperator
	if($settings['Settings']['output_to_file']){
		if(outputToFile($randomList,$settings['Settings']['output_file_name'])){
			echo "Random lines created successfully. Outputted to ".$settings['Settings']['output_file_name'];
			}else{
			echo "Error";
		}
		}else{
		echo $randomList;	
	}
	
	//===================
	// Functions
	//===================
	
	// Converts a text list into an array
	function parseItemList($txtlist){
		return explode("\r\n",$txtlist);
	}
	
	// Creates a randomized list based on predefined values and lines to create
	function createRandomList($arraylist,$lines = 10,$seperator,$preventDuplicates){
		$randomNumber = 0;
		for ($x = 0; $x <= $lines; $x++) {
			$randomNumber = generateRandomNumber($randomNumber,0,count($arraylist)-1,$preventDuplicates); 
			$list[] = $arraylist[$randomNumber];
		}
		return implode($seperator,$list);
	}
	
	// Creates a repeated list based on predefined values and lines to create
	function createList($arraylist,$lines = 10,$seperator){
		$currentIndex = 0;
		for ($x = 0; $x <= $lines; $x++) {
			if($currentIndex == count($arraylist)){$currentIndex = 0;}
			$list[] = $arraylist[$currentIndex];
			$currentIndex++;
		}
		return implode($seperator,$list);
	}
	
	// Generates a random number with duplication optional detection
	function generateRandomNumber($old,$min,$max,$preventDuplicates){
		$num = rand($min,$max);
		if($num == $old and $preventDuplicates == true){
			return generateRandomNumber($old,$min,$max,$preventDuplicates);
			}else{
			return $num;
		}
	}
	
	// Outputs text to a file specified
	function outputToFile($list,$filename){
		return file_put_contents($filename,$list);
	}				