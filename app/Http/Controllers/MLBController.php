<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File;
use App\Player;
use App\Mlblineup;

class MLBController extends Controller
{
    //
    public function index() {
    	return "hi mike";
    }

    public function fileUploadNumberFire(Request $request) {
    	$validatedData = $request->validate([
	        'fileToUpload' => 'required|file',
	    ]); 

	    if ($request->file('fileToUpload')->isValid()) {
    		$origFile = File::get($request->fileToUpload);
    		$mlbFile = $origFile;

    		//////////////////////////
    		// Number Fire Fan Duel //
    		//////////////////////////

    		// Player Name Start
    		$playerLocal = strpos($mlbFile, '<a class="full" href=');

    		while($playerLocal > 0) {    		
	    		$mlbFile = substr($mlbFile,$playerLocal+2);
	    		$locStart = strpos($mlbFile, '>');
	    		$locEnd = strpos($mlbFile, '<');
	    		$playerName = trim(substr($mlbFile, $locStart+1, $locEnd-$locStart-1));
	    		echo $playerName . '<br>';
	    		$mlbFile = substr(($mlbFile), $locEnd+3);
	    		$space = strpos($playerName," ");

	    		if($space > 0) {
	    			echo "hey: " . $playerName . "<br>";

	    			$lastName = substr($playerName, $space + 1);
	    			//$lastName = preg_replace("/[^A-z]/", "",$lastName);
	    			$firstName = substr($playerName, 0, $space);
	    			$firstName = preg_replace("/[^A-z]/", "",$firstName);
	    		} else {
	    			echo "ew<br>";
	    			$lastName = $playerName;
	    			$firstName = "";
	    		}
	    		// End Player Name

	    		// Start Position
	    		$locStart = strpos($mlbFile, '<span class="player-info--position');
	    		$mlbFile = substr($mlbFile,$locStart+5);
	    		$locStart = strpos($mlbFile, '>');
	    		$locEnd = strpos($mlbFile, '<');
	    		$playerPosition = substr($mlbFile, $locStart+1, $locEnd-$locStart-1);
	    		echo $playerPosition . '<br>';
	    		// End Position

	    		// Start Fantasy Points
	    		$locStart = strpos($mlbFile, '<td class="fp active">');
	    		$mlbFile = substr($mlbFile,$locStart+10);
	    		$locStart = strpos($mlbFile, '>');
	    		$locEnd = strpos($mlbFile, '<');
	    		$playerPoints = substr($mlbFile, $locStart+1, $locEnd-$locStart-1);
	    		$playerPoints = str_replace(' ', '', $playerPoints);
	    		$playerPoints = str_replace('\n', '', $playerPoints);
	    		if(strpos($playerPoints, '.')) {
	    			$playerPoints = substr($playerPoints, 0, strpos($playerPoints, '.'));
	    		}
	    		$playerPoints = preg_replace("/[^0-9]/", "",$playerPoints);

	    		echo $playerPoints . '<br>';
	    		$mlbFile = substr($mlbFile,$locEnd+2);
	    		// End Fantasy Points

	    		// Start Salary
	    		$locStart = strpos($mlbFile, '<td class="cost"');
	    		$mlbFile = substr($mlbFile,$locStart+2);
	    		$locStart = strpos($mlbFile, '>');
	    		$locEnd = strpos($mlbFile, '<');
	    		$playerFanDuelSalary = substr($mlbFile, $locStart+1, $locEnd-$locStart-1);
	    		/*
	    		if(strlen($playerFanDuelSalary)>0) {
	    			$playerFanDuelSalary = substr($playerFanDuelSalary, 1);
	    		} else {
	    			$playerFanDuelSalary = -1;
	    		} */
	    		$playerFanDuelSalary = substr($playerFanDuelSalary, strpos($playerFanDuelSalary, '$')+1);
	    		$playerFanDuelSalary = str_replace(',', '', $playerFanDuelSalary);
	    		$playerFanDuelSalary = str_replace('\n', '', $playerFanDuelSalary);
	    		if(strpos($playerFanDuelSalary, "K") > 0) {
	    			$playerFanDuelSalary = substr($playerFanDuelSalary, 0, -1);
	    			$playerFanDuelSalary = $playerFanDuelSalary * 1000;
	    		}
	    		echo '$' . $playerFanDuelSalary . '<br>';
	    		// End Salary	    		

	    		$player = Player::where('last_name',$lastName)->where('first_name',$firstName)->
	    					where('position',$playerPosition)->first();
	    		if(count($player) > 0) {
	    			$player->fan_duel_cost = $playerFanDuelSalary;
	    			$player->numberfire_fan_duel_projection = $playerPoints;
	    			$player->save();
	    		} else {
					$player = new Player;
					$player->last_name = $lastName;
					$player->first_name = $firstName;
					$player->position = $playerPosition;
					$player->fan_duel_cost = $playerFanDuelSalary;
					$player->rotogrinders_player_local = 0;
	            	$player->rotogrinders_fan_duel_projection = 0;
	            	$player->numberfire_fan_duel_projection = $playerPoints;
					$player->save();
				}

	    		$playerLocal = strpos($mlbFile, '<a class="full" href=');
			}
			return 'done';
    	}
    }

    public function fileUploadRotoGrinders(Request $request) {
    	$validatedData = $request->validate([
	        'fileToUpload' => 'required|file',
	    ]); 

	    if ($request->file('fileToUpload')->isValid()) {
    		$origFile = File::get($request->fileToUpload);
    		$mlbFile = $origFile;

    		////////////////////////////
    		// Roto Grinders Fan Duel //
    		////////////////////////////

    		// Player Name Start
    		$playerLocal = strpos($mlbFile, '<a class="player-popup" data-url="https://rotogrinders.com');

    		while($playerLocal > 0) {
	    		$mlbFile = substr($mlbFile,$playerLocal+2);
	    		$locStart = strpos($mlbFile, '>');
	    		$locEnd = strpos($mlbFile, '<');
	    		$playerName = substr($mlbFile, $locStart+1, $locEnd-$locStart-1);
	    		echo $playerName . '<br>';
	    		$mlbFile = substr(($mlbFile), $locEnd+3);
	    		$space = strpos($playerName," ");
	    		if($space > 0) {
	    			$lastName = substr($playerName, $space + 1);
	    			$firstName = substr($playerName, 0, $space);
	    		} else {
	    			$lastName = $playerName;
	    			$firstName = "";
	    		}
	    		// End Player Name

	    		// Start Position
	    		$locStart = strpos($mlbFile, '<span class="position">');
	    		$mlbFile = substr($mlbFile,$locStart+2);
	    		$locStart = strpos($mlbFile, '>');
	    		$locEnd = strpos($mlbFile, '</span>');
	    		$playerPosition = substr($mlbFile, $locStart+1, $locEnd-$locStart-1);
	    		$playerPosition = str_replace(' ', '', $playerPosition);
	    		echo $playerPosition . '<br>';
	    		// End Position

	    		// Start Salary
	    		$locStart = strpos($mlbFile, 'data-salary="');
	    		$mlbFile = substr($mlbFile,$locStart+2);
	    		$locStart = strpos($mlbFile, '>');
	    		$locEnd = strpos($mlbFile, '<');
	    		$playerFanDuelSalary = substr($mlbFile, $locStart+1, $locEnd-$locStart-1);
	    		if(strlen($playerFanDuelSalary)>0) {
	    			$playerFanDuelSalary = substr($playerFanDuelSalary, 1);
	    		} else {
	    			$playerFanDuelSalary = -1;
	    		}
	    		if(strpos($playerFanDuelSalary, "K") > 0) {
	    			$playerFanDuelSalary = substr($playerFanDuelSalary, 0, -1);
	    			$playerFanDuelSalary = $playerFanDuelSalary * 1000;
	    		}
	    		echo '$' . $playerFanDuelSalary . '<br>';
	    		// End Salary

	    		// Start Fantasy Points
	    		$locStart = strpos($mlbFile, 'data-fpts=');
	    		$mlbFile = substr($mlbFile,$locStart+10);
	    		$locStart = 0;
	    		$locEnd = strpos($mlbFile, '">');
	    		$playerPoints = substr($mlbFile, $locStart+1, $locEnd-$locStart-1);

	    		echo $playerPoints . '<br>';
	    		$mlbFile = substr($mlbFile,$locEnd+2);
	    		// End Fantasy Points

	    		$dashCount = substr_count($playerPosition,"-");
	    		for($i=0;$i<$dashCount+1;$i++) {
	    			echo "playerPos: " . $playerPosition . '</br>';
	    			$dashPos = strpos($playerPosition,'-');
	    			if($dashPos > 0) {
	    				$tempPos = substr($playerPosition, 0, $dashPos);
	    				$tempPos = preg_replace("/[^1-z]/", "",$tempPos);
	    				$playerPosition = substr($playerPosition, $dashPos+1);
	    			}
	    			else {
	    				$tempPos = $playerPosition;
	    				$tempPos = preg_replace("/[^1-z]/", "",$tempPos);
	    			}
					$player = new Player;
					$player->last_name = $lastName;
					$player->first_name = $firstName;
					$player->position = $tempPos;
					$player->fan_duel_cost = $playerFanDuelSalary;
					$player->rotogrinders_player_local = 0;
	            	$player->rotogrinders_fan_duel_projection = $playerPoints;
	            	$player->numberfire_fan_duel_projection = 0;
					$player->save();
				}

	    		$playerLocal = strpos($mlbFile, '<a class="player-popup" data-url="https://rotogrinders.com');
	    	}

    		return 'done';
    		//return strpos($mlbFile, '<span class="position">');
		}
    }

    public function generateLineup(Request $request) {
    	$players = Player::where('position','<>','P')->get();
    	$firstBasemen = Player::where('position','1B')->orwhere('position','C')->get();
    	$secondBasemen = Player::where('position','2B')->get();
    	$thirdBasemen = Player::where('position','3B')->get();
    	$shortStops = Player::where('position','SS')->get();
    	$pitchers = Player::where('position','P')->get();
    	$outFielders = Player::where('position','OF')->get();

    	foreach ($players as $player) {
    		foreach ($firstBasemen as $firstBaseman) {
    			foreach ($secondBasemen as $secondBaseman) {
    				foreach ($thirdBasemen as $thirdBaseman) {
    					foreach ($shortStops as $shortStop) {
    						foreach ($pitchers as $pitcher) {
    							foreach ($outFielders as $of1) {
    								foreach ($outFielders as $of2) {
    									foreach ($outFielders as $of3) {
    										$salary = $player->fan_duel_cost + $firstBaseman->fan_duel_cost + $secondBaseman->fan_duel_cost + 
    											$thirdBaseman->fan_duel_cost + $shortStop->fan_duel_cost + $pitcher->fan_duel_cost +
    											$of1->fan_duel_cost + $of2->fan_duel_cost + $of3->fan_duel_cost;

    										$points = $player->fanDuelPoints()+ $firstBaseman->fanDuelPoints() + $secondBaseman->fanDuelPoints() + 
    											$thirdBaseman->fanDuelPoints() + $shortStop->fanDuelPoints() + $pitcher->fanDuelPoints() +
    											$of1->fanDuelPoints() + $of2->fanDuelPoints() + $of3->fanDuelPoints();

    										$mlbLineup = new Mlblineup;
								    		$mlbLineup->fan_duel_points = $salary;
								            $mlbLineup->fan_duel_cost = $points;
								            $mlbLineup->first_base = $firstBaseman->id;
								            $mlbLineup->second_base = $secondBaseman->id;
								            $mlbLineup->third_base = $thirdBaseman->id;
								            $mlbLineup->short_stop = $shortStop->id;
								            $mlbLineup->of1 = $of1->id;
								            $mlbLineup->of2 = $of2->id;
								            $mlbLineup->of3 = $of3->id;
								            $mlbLineup->util = $player->id;
								            $mlbLineup->save(); 
    									}
    								}
    							}
    						}
    					}
    				}
    			}
    		}
		}

    	return 'done';
    }
}
