<?php

namespace api\tools;

class Dice {

	const DefaultFaces = 6;

	public static $defaultRule = null;

	public static function Roll ($nbRoll, $faces = Dice::DefaultFaces, DiceRules $rules = null, $verbose = false, \Closure $random = null) {

		if($rules == null) {
			$rules = Dice::$defaultRule;
		}

		$rolls = array();

        $rolls['mode'][1] = DiceRulesRerollAction::Add;
        $rolls['realMode'][1] = DiceRulesRerollAction::Add;
        $rolls['nbPass'] = 1;
        $rolls['nbRoll'][1] = $nbRoll;
        $nbTot = 0;



        for ($pass = 1; $pass <= $rolls['nbPass']; $pass++) {

			for ($i = 1; $i <= $rolls['nbRoll'][$pass]; $i++) {
				//$result = mt_rand(1, $faces);

				if(isset($random)) {
					$result = $random($faces);
				} else {
					/*$result = current($testValues); // Values for testing!!!
					next($testValues);*/		
					$result = mt_rand(1, $faces);			
				}



				if(isset($rules->Reroll[$result])) {


					if($pass != 1 && $rules->Reroll[$result]->Inherits != true) {

					} else {

						$next = 1;

						// The next pass exist & is incompatible, we must create a new one
						if((isset($rolls['nbRoll'][$pass + $next]) && 
							$rolls['mode'][$pass + $next] != $rolls['mode'][$pass])) {
							$next = 2;
						}


						if(!isset($rolls['nbRoll'][$pass + $next])) {
							$rolls['nbPass']++;
							$rolls['nbRoll'][$pass + $next] = 0;
							$rolls['parent'][$pass + $next] = $pass;
						}

						$rolls['nbRoll'][$pass + $next]++;

						if($pass != 1) {
							$rolls['mode'][$pass + $next] = $rolls['mode'][$pass];
						} else {
							$rolls['mode'][$pass + $next] = $rules->Reroll[$result]->Action;
						}
					}
					
				}

				$mode = $rolls['mode'][$pass];

				//$mode = Dice::FindMode($rolls, $pass);
				//$rolls['realMode'][$pass] = $mode;

				if($mode == DiceRulesRerollAction::Substract) {
					$result = 0-$result;
				} 				

				$nbTot++;

				$rolls['result'][$pass][] = $result;
			}

        }

        $return = array('Result' => null, 'Details' => $rolls);
 
		$resultMerged = Dice::Merge($rolls['result']);

 		if($rules->Result->Limit != null) {

			$resultMerged = Dice::ApplyLimit($resultMerged, $rules);

			$nbTot = $rules->Result->Limit;
 		
    	}

 		switch ($rules->Result->Type) {
 			case (DiceRulesResultType::Average):
 				$return['Result'] = (int)round(array_sum($resultMerged) / $nbTot);
 				break;
 			case (DiceRulesResultType::Ordered):
 				$return['Result'] = $resultMerged;
 				break;
 			case (DiceRulesResultType::Sum):
 			default:
 				$return['Result'] = array_sum($resultMerged);
 				break; 				
 		}

 		if($verbose) {
 			return $return;
 		} else {
 			return $return['Result'];
 		}


	}

	private static function ApplyLimit($mergedArray, $rules) {
			$sort = $mergedArray;

			if($rules->Result->LimitIgnoreNegative == false ) {
				$sort = array_map(function($value) {
					return abs($value);
				}, $sort);
			}

			if($rules->Result->LimitType == DiceRulesResultLimit::Higher) {
				rsort($sort, SORT_NUMERIC);
			} else {
				sort($sort, SORT_NUMERIC);
			}
			

			$return['Limit'] = $sort;

			$sort = array_slice($sort, 0, $rules->Result->Limit);

			$return['Slice'] = $sort;

			$sorted = array();

			for($cpt = 0; $cpt < count($mergedArray); $cpt++) {

				$search = ($rules->Result->LimitIgnoreNegative) ? $mergedArray[$cpt] : abs($mergedArray[$cpt]);

				if(in_array($search, $sort)) {
					$sorted[] = $mergedArray[$cpt];
					$k = array_search($search, $sort);
					unset($sort[$k]);
				}
			}

			return $sorted;		
	}

	/*public static function Sum($array) {
		return array_sum(array_map(function($value) {
			return array_sum($value);
		}, $array));
	}*/

    /**
      * Merge a multi-pass array into a single array
      *
      * @param string $array The array to merge
      *
      * @return merged array
      */
	public static function Merge($array) {
		
		$result = array();

		foreach ($array as $pass) {
			$result = array_merge($result, $pass);
		}

		return $result;
	}

	private static function Random() {

	}

}

class DiceRules {

	//private $rules;

	public $Result;
	public $Reroll;

	public function __construct($params = null) {


		$this->Result = (object) array(
		    	"Type" => DiceRulesResultType::Sum,
		    	"Limit" => null,
		    	"LimitType" => DiceRulesResultLimit::Higher,
		    	"LimitIgnoreNegative" => false
		    );
		$this->Reroll = array();

		if($params != null && is_string($params)) {
			// si c'est un string, on tente de parser en array

				$params = json_decode($params, true, 4);


				$result = array_replace_recursive(array('Result' => (array) $this->Result, 'Reroll' => $this->Reroll), $params);
				//$result = (object) array_merge_recursive(array('Result' => (array)$this->Result, 'Reroll' => $this->Reroll), (array) $params);
				

				if(isset($result['Reroll'])) {
					array_walk($result['Reroll'], function(&$rerollLine) {
						$rerollLine = (object) array_merge(array('Action' => DiceRulesRerollAction::Add, 'Inherits' => true), $rerollLine);
					});
				}

				$this->Result = (object) $result['Result'];
				$this->Reroll = $result['Reroll'];

		}
	}

	public function addRuleReroll($on, $action, $rullInInherits = true) {
		$this->Reroll[$on] = (object) array('Action' => $action, 'Inherits' => $rullInInherits);
	}

	public function removeRuleReroll($on) {
		if(isset($this->Reroll[$on])) {
			unset($this->Reroll[$on]);
		}
	}

}

class DiceRulesRerollAction {
	const Add = "ra_add";
	const Substract = "ra_sub";
	//const Inherits = "ra_ext";
}

class DiceRulesResultType {
	const Sum = "rt_sum";
	const Average = "rt_avg";
	const Ordered = "rt_order";
}

class DiceRulesResultLimit {
	const Lower = "rl_low";
	const Higher = "rl_high";
}

if(Dice::$defaultRule == null) {

	Dice::$defaultRule = new DiceRules();

}

?>
