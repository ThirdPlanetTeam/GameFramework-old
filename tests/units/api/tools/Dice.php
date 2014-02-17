<?php

namespace tests\units\api\tools;


define("SERVER_ROOT", substr(__DIR__, 0, (0-strlen(__NAMESPACE__))));

//$filename = SERVER_ROOT . substr(__NAMESPACE__, 12) . '/GameplayElement.php';
$filename = SERVER_ROOT . 'api/tools/Dice.php';

require_once SERVER_ROOT . 'tests/mageekguy.atoum.phar';

include $filename ;

use \mageekguy\atoum;
use \api\tools\Dice as TestedDice;
use \api\tools\DiceRules as TestedDiceRules;
use \api\tools\DiceRulesRerollAction as TestedDiceRulesRerollAction;
use \api\tools\DiceRulesResultType as TestedDiceRulesResultType;
use \api\tools\DiceRulesResultLimit as TestedDiceRulesResultLimit;

class Dice extends atoum
{

    public $randClosure = null;
    public static $falseRandom;
    public $rule;


    public function beforeTestMethod($method)
    {



        if($this->randClosure == null) {


            Dice::$falseRandom = array(1,3,6,6,3,6,1,6,20,8,1,9);

            $this->randClosure = function ($nbFace) {

                $result = current(Dice::$falseRandom);

                next(Dice::$falseRandom);

                return $result;
            };
        } else {
            reset(Dice::$falseRandom);
        }

        $rule = null;
        
        switch ($method) {
            case 'testClassicAverage':
                $rule = '{"Result":{"Type":"'.TestedDiceRulesResultType::Average.'"}}';
                break;
            case 'testClassicOrdered':
                $rule = '{"Result":{"Type":"'.TestedDiceRulesResultType::Ordered.'"}}';
                break;
            case 'testRerollAdd':
                $rule = '{"Result":{"Type":"'.TestedDiceRulesResultType::Ordered.'"},
                        "Reroll":{"6":{"Action": "'.TestedDiceRulesRerollAction::Add.'"}}}';
                break;            
            case 'testRerollSubstract':
                $rule = '{"Result":{"Type":"'.TestedDiceRulesResultType::Ordered.'"},
                        "Reroll":{"6":{"Action": "'.TestedDiceRulesRerollAction::Add.'"},
                        "1":{"Action": "'.TestedDiceRulesRerollAction::Substract.'"}}}';
                break;                
            case 'testRerollInheritance':
                $rule = '{"Result":{"Type":"'.TestedDiceRulesResultType::Ordered.'"},
                        "Reroll":{"6":{"Action": "'.TestedDiceRulesRerollAction::Add.'"},
                        "1":{"Action": "'.TestedDiceRulesRerollAction::Substract.'", "Inherits": false}}}';
                break;                   
            case 'testRerollLimits':
                $rule = '{"Result":{"Limit":"3"}}';
                break;
            default:
                # code...
                break;
        }
        $this->rule = new TestedDiceRules($rule);
    }

    public function testClosure()
    {
        
        $rand = $this->randClosure;

 		$this
            ->integer($rand(2))->isEqualTo(1)
            ->integer($rand(2))->isEqualTo(3)
            ->integer($rand(2))->isEqualTo(6)
            ->integer($rand(2))->isEqualTo(6)
            ->integer($rand(2))->isEqualTo(3)
        ;

    }   

    public function testClassicSum()
    {
        $rand = $this->randClosure;

        $this->integer(TestedDice::Roll(1, 6, null, false, $rand))->isEqualTo(1);
        reset(Dice::$falseRandom);

        $this->integer(TestedDice::Roll(2, 6, null, false, $rand))->isEqualTo(4);
        reset(Dice::$falseRandom);

        $this ->integer(TestedDice::Roll(3, 6, null, false, $rand))->isEqualTo(10);
        reset(Dice::$falseRandom);

        $this->integer(TestedDice::Roll(4, 6, null, false, $rand))->isEqualTo(16);                        
        reset(Dice::$falseRandom);
    }

    public function testClassicAverage()
    {
        $rand = $this->randClosure;

        $this->integer(TestedDice::Roll(1, 6, $this->rule, false, $rand))->isEqualTo(1);
        reset(Dice::$falseRandom);

        $this->integer(TestedDice::Roll(2, 6, $this->rule, false, $rand))->isEqualTo(2);
        reset(Dice::$falseRandom);

        $this ->integer(TestedDice::Roll(3, 6, $this->rule, false, $rand))->isEqualTo(3);
        reset(Dice::$falseRandom);

        $this->integer(TestedDice::Roll(4, 6, $this->rule, false, $rand))->isEqualTo(4);                        
        reset(Dice::$falseRandom);
    }    

    public function testClassicOrdered()
    {
        $rand = $this->randClosure;
//1,3,6,6
        $this->array(TestedDice::Roll(1, 6, $this->rule, false, $rand))
            ->contains(1)
            ->strictlyContains(1)
            ->size->isEqualTo(1);
        reset(Dice::$falseRandom);

        $this->array(TestedDice::Roll(2, 6, $this->rule, false, $rand))
            ->containsValues(array(1,3))
            ->strictlyContainsValues(array(1,3))
            ->size->isEqualTo(2);            
        reset(Dice::$falseRandom);

        $this ->array(TestedDice::Roll(3, 6, $this->rule, false, $rand))
            ->containsValues(array(1,3,6))
            ->strictlyContainsValues(array(1,3,6))
            ->size->isEqualTo(3);            
        reset(Dice::$falseRandom);

        $this->array(TestedDice::Roll(4, 6, $this->rule, false, $rand))
            ->containsValues(array(1,3,6))
            ->strictlyContainsValues(array(1,3,6))
            ->size->isEqualTo(4);                     
        reset(Dice::$falseRandom);
    }  

    public function testRerollAdd() {
        $rand = $this->randClosure;

        $this->array(TestedDice::Roll(2, 6, $this->rule, false, $rand))
            ->containsValues(array(1,3))
            ->strictlyContainsValues(array(1,3))
            ->size->isEqualTo(2);            
        reset(Dice::$falseRandom);

        $this->integer(array_sum(TestedDice::Roll(2, 6, $this->rule, false, $rand)))
            ->isEqualTo(4);

        reset(Dice::$falseRandom);            

        $this->array(TestedDice::Roll(3, 6, $this->rule, false, $rand))
            ->containsValues(array(1,3,6))
            ->strictlyContainsValues(array(1,3,6))
            ->size->isEqualTo(5);            
        reset(Dice::$falseRandom);      

        $this->integer(array_sum(TestedDice::Roll(3, 6, $this->rule, false, $rand)))
            ->isEqualTo(19);                
    }  

    public function testRerollSubstract() {
        $rand = $this->randClosure;

        $this->array(TestedDice::Roll(2, 6, $this->rule, false, $rand))
            ->containsValues(array(1,3,-6,-3))
            ->strictlyContainsValues(array(1,3,-6,-3))
            ->size->isEqualTo(5);            
        reset(Dice::$falseRandom);

        $this->integer(array_sum(TestedDice::Roll(2, 6, $this->rule, false, $rand)))
            ->isEqualTo(-11);

        reset(Dice::$falseRandom);            

        $this->array(TestedDice::Roll(3, 6, $this->rule, false, $rand))
            ->containsValues(array(1,3,6,-6,-1,-20))
            ->strictlyContainsValues(array(1,3,6,-6,-1,-20))
            ->size->isEqualTo(9);            
        reset(Dice::$falseRandom);      

        $this->integer(array_sum(TestedDice::Roll(3, 6, $this->rule, false, $rand)))
            ->isEqualTo(-26);
    }  

    public function testRerollInheritance() {
        $rand = $this->randClosure;


        $this->array(TestedDice::Roll(2, 6, $this->rule, false, $rand))
            ->containsValues(array(1,3,-6,-3))
            ->strictlyContainsValues(array(1,3,-6,-3))
            ->size->isEqualTo(5);            
        reset(Dice::$falseRandom);        

        $this->array(TestedDice::Roll(3, 6, $this->rule, false, $rand))
            ->containsValues(array(1,3,6,-6,-1))
            ->strictlyContainsValues(array(1,3,6,-6,-1))
            ->size->isEqualTo(7);            
        reset(Dice::$falseRandom);      

        $this->integer(array_sum(TestedDice::Roll(3, 6, $this->rule, false, $rand)))
            ->isEqualTo(0);  


       
    }      
    
    public function testRerollLimits() {
        $rand = $this->randClosure;

        $this->integer(TestedDice::Roll(3, 6, $this->rule, false, $rand))->isEqualTo(10);
        reset(Dice::$falseRandom);      

        $this->integer(TestedDice::Roll(5, 6, $this->rule, false, $rand))->isEqualTo(15);
        reset(Dice::$falseRandom);                 

        $this->rule->Result->LimitType = TestedDiceRulesResultLimit::Lower;

        $this->integer(TestedDice::Roll(5, 6, $this->rule, false, $rand))->isEqualTo(7);
        reset(Dice::$falseRandom);                 


        $this->rule->addRuleReroll(6, TestedDiceRulesRerollAction::Add);

        $this->integer(TestedDice::Roll(5, 6, $this->rule, false, $rand))->isEqualTo(5);
        reset(Dice::$falseRandom);          

        $this->rule->removeRuleReroll(6);  
        $this->rule->addRuleReroll(1, TestedDiceRulesRerollAction::Substract);

        $this->integer(TestedDice::Roll(5, 6, $this->rule, false, $rand))->isEqualTo(7);
        reset(Dice::$falseRandom);

        $this->rule->Result->LimitIgnoreNegative = true;

        $this->integer(TestedDice::Roll(5, 6, $this->rule, false, $rand))->isEqualTo(-2);

    }
}


class DiceRules extends atoum 
{
    public $reflection;

    public function beforeTestMethod($method) {        
        //$reflectionClass = new ReflectionClass('Foo');
    }

    public function testInternalValue() {

        $rule = new TestedDiceRules();

        /*$reflectionClass = new \ReflectionClass(get_class($rule));
        $reflectionProperty = $reflectionClass->getProperty('rules');
        $reflectionProperty->setAccessible(true);

        $internal = $reflectionProperty->getValue($rule);*/

        //print_r($internal);

        $this->
            object($rule->Result)
                ->isInstanceOf('stdClass');

        $this->
            array(get_object_vars($rule->Result))
                ->hasSize(4);
                //->atKey('Result')->isEmpty();
        $this->
            array($rule->Reroll)
                ->hasSize(0);
        /*

            "Result" => (object) array(
                "Type" => DiceRulesResultType::Sum,
                "Limit" => null,
                "LimitType" => DiceRulesResultLimit::Higher,
                "LimitIgnoreNegative" => false,
            ),
            "Reroll" => array()

            */
    }

    public function testConstructor() {

        $jsonString = '{"Result":{"Type":"'.TestedDiceRulesResultType::Average.'","LimitIgnoreNegative":true},
        "Reroll":{"3":{"Action": "'.TestedDiceRulesRerollAction::Substract.'"}}}';

        $rule = new TestedDiceRules();

        $this->
            array($rule->Reroll)
                ->hasSize(0);

        $this->
            object($rule->Result)
                ->isInstanceOf('stdClass');
        $this->
            string($rule->Result->Type)->isEqualTo(TestedDiceRulesResultType::Sum);
        $this->
            variable($rule->Result->Limit)->isNull();               
        $this->
            string($rule->Result->LimitType)->isEqualTo(TestedDiceRulesResultLimit::Higher);            
        $this->
            boolean($rule->Result->LimitIgnoreNegative)->isFalse();               


        $rule = new TestedDiceRules($jsonString);     
                
        $this->
            array($rule->Reroll)
                ->hasSize(1)
                ->hasKey(3);                   
        $this->
            string($rule->Reroll[3]->Action)->isEqualTo(TestedDiceRulesRerollAction::Substract);  

        $this->
            object($rule->Result)
                ->isInstanceOf('stdClass');
        $this->
            string($rule->Result->Type)->isEqualTo(TestedDiceRulesResultType::Average);
        $this->
            variable($rule->Result->Limit)->isNull();               
        $this->
            string($rule->Result->LimitType)->isEqualTo(TestedDiceRulesResultLimit::Higher);            
        $this->
            boolean($rule->Result->LimitIgnoreNegative)->isTrue();               

        
    }

    public function testRerollRules() {
        $rule = new TestedDiceRules();

        $rule->addRuleReroll(6, TestedDiceRulesRerollAction::Add);

        $this->
            array($rule->Reroll)
                ->hasSize(1)
                ->hasKey(6);
        $this->
            string($rule->Reroll[6]->Action)->isEqualTo(TestedDiceRulesRerollAction::Add);

        $this->
            boolean($rule->Reroll[6]->Inherits)->isTrue();

        $rule->addRuleReroll(1, TestedDiceRulesRerollAction::Substract, false);

        $this->
            array($rule->Reroll)
                ->hasSize(2)
                ->hasKeys(array(1,6));

        $this->string($rule->Reroll[1]->Action)->isEqualTo(TestedDiceRulesRerollAction::Substract);
        $this->string($rule->Reroll[6]->Action)->isEqualTo(TestedDiceRulesRerollAction::Add);

        $this->boolean($rule->Reroll[1]->Inherits)->isFalse();
        $this->boolean($rule->Reroll[6]->Inherits)->isTrue();


        $rule->addRuleReroll(6, TestedDiceRulesRerollAction::Substract);

        $this->
            array($rule->Reroll)
                ->hasSize(2)
                ->hasKeys(array(1,6));

        $this->string($rule->Reroll[1]->Action)->isEqualTo(TestedDiceRulesRerollAction::Substract);
        $this->string($rule->Reroll[6]->Action)->isEqualTo(TestedDiceRulesRerollAction::Substract);

        $this->boolean($rule->Reroll[1]->Inherits)->isFalse();
        $this->boolean($rule->Reroll[6]->Inherits)->isTrue();        
    }

}

/*

$average = new DiceRules();
$ordered = new DiceRules();
$reroll = new DiceRules();
$rerollSub = new DiceRules();
$rerollSubLimit1 = new DiceRules();
$rerollSubLimit2 = new DiceRules();
$rerollSubLimit3 = new DiceRules();


$rerollOrdered = new DiceRules();
$rerollAverage = new DiceRules();

$average->Result->Type = DiceRulesResultType::Average;
$rerollAverage->Result->Type = DiceRulesResultType::Average;

$ordered->Result->Type = DiceRulesResultType::Ordered;
$rerollOrdered->Result->Type = DiceRulesResultType::Ordered;

$reroll->addRuleReroll(6, DiceRulesRerollAction::Add);
$rerollOrdered->addRuleReroll(6, DiceRulesRerollAction::Add);
$rerollAverage->addRuleReroll(6, DiceRulesRerollAction::Add);

$rerollSub->addRuleReroll(6, DiceRulesRerollAction::Add);
$rerollSub->addRuleReroll(1, DiceRulesRerollAction::Substract, false);

$rerollSubLimit1->addRuleReroll(6, DiceRulesRerollAction::Add);
$rerollSubLimit1->addRuleReroll(1, DiceRulesRerollAction::Substract, false);
$rerollSubLimit1->Result->Type = DiceRulesResultType::Ordered;

$rerollSubLimit2->addRuleReroll(6, DiceRulesRerollAction::Add);
$rerollSubLimit2->addRuleReroll(1, DiceRulesRerollAction::Substract, false);
$rerollSubLimit2->Result->Type = DiceRulesResultType::Ordered;

$rerollSubLimit3->addRuleReroll(6, DiceRulesRerollAction::Add);
$rerollSubLimit3->addRuleReroll(1, DiceRulesRerollAction::Substract, false);
$rerollSubLimit3->Result->Type = DiceRulesResultType::Ordered;

$rerollSubLimit1->Result->Limit = 5;
$rerollSubLimit2->Result->Limit = 6;
$rerollSubLimit3->Result->Limit = 6;

$rerollSubLimit2->Result->LimitIgnoreNegative = true;

$rerollSubLimit3->Result->LimitIgnoreNegative = true;
$rerollSubLimit3->Result->LimitType = DiceRulesResultLimit::Lower;

echo '<pre>';

*/