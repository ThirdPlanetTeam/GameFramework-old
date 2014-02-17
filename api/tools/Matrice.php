<?php

/*********************************************
 * PHP Game Framework            			 *
 * Published under MIT License   			 *
 * Copyright (c) 2013-2014 Third Planet Team *
 *********************************************/

namespace api\tools;

class Matrice extends GameplayElement {

	const maxDirection = 100;

	public function __construct($row = 0, $column = 0, $default = 0) {
		$row = getNumber($row);
		$column = getNumber($column);

		$grille = array();

		for($row_index = 0; $row_index <= $row; $row_index++) {
			$arr = array();
			for($column_index = 0; $column_index <= $column; $column_index++) {
				$arr[] = $default;
			}
			$grille[] = $arr;
		}

		$this->addData['grille'] = $grille;
		$this->addData['correspondance_lignes'] = array();
		$this->addData['correspondance_colonnes'] = array();
		$this->addData['nb_lignes'] = $row;
		$this->addData['nb_colonnes'] = $column;
		$this->addData['default'] = $default;

	}

	public function ajouterPoint($ligne, $colonne, $valeur) {
		$ligne = $this->ajusterTaille('lignes', $ligne);
		$colonne = $this->ajusterTaille('colonnes', $colonne);

		$this->grille[$ligne][$colonne] = $valeur;
	}

	private function ajusterTaille($sens, $indice) {
		if(gettype($indice) != "integer") {
			$indice = (string)$indice;

		}	

		if(!in_array($indice, $this->data['correspondance_' . $sens])) {
			$nb = count($this->data['correspondance_' . $sens]);
			$this->data['correspondance_' . $sens][$indice] = ;
			$this->data['nb_' . $sens]++;
		}	

		return 	
	}

	private function getNumber($num) {
		if($num < 0) {
			$num = 0;
		}

		if($num > self::maxDirection) {
			$num = self::maxDirection;
		}

		return $num;
	}

	/**
	 * Virtually draw a line from (x1, y1) to (x2, y2) using Bresenham algorithm, returning the coordinates of the points that would belong to the line.
	 * @param $x1 (Int)
	 * @param $y1 (Int)
	 * @param $x2 (Int)
	 * @param $y2 (Int)
	 * @param $guaranteeEndPoint By default end point (x2, y2) is guaranteed to belong to the line. Many implementation don't have this. If you don't need it, it's a little faster if you set this to false.
	 * @return (Array of couples forming the line) Eg: array(array(2,100), array(3, 101), array(4, 102), array(5, 103))
	 * Public domain Av'tW
	 */
	public static function bresenham($x1, $y1, $x2, $y2, $guaranteeEndPoint=true) {
		$xBegin = $x1;
		$yBegin = $y1;
		$xEnd = $x2;
		$yEnd = $y2;
		$dots = array();        // Array of couples, returned array</p>

		$steep = abs($y2 - $y1) > abs($x2 - $x1);

		// Swap some coordinateds in order to generalize
		if ($steep) {
			$tmp = $x1;
			$x1 = $y1;
			$y1 = $tmp;
			$tmp = $x2;
			$x2 = $y2;
			$y2 = $tmp;
		}
		
		if ($x1 > $x2) {
			$tmp = $x1;
			$x1 = $x2;
			$x2 = $tmp;
			$tmp = $y1;
			$y1 = $y2;
			$y2 = $tmp;
		}
		
		$deltax = floor($x2 - $x1) + 0.5;
		$deltay = floor(abs($y2 - $y1)) + 0.5;
		$error = 0;
		$deltaerr = $deltay / $deltax;
		$y = $y1;
		$ystep = ($y1 > $y2) ? 1 : -1;

		for ($x = $x1; $x < $x2; $x++) {
			$dots[] = $steep ? array($y, $x) : array($x, $y);
			$error += $deltaerr;

	        if ($error >= 0.5) {
				$y += $ystep;
				$error -= 1;
			}
		}

		if ($guaranteeEndPoint) {
			// Bresenham doesn't always include the specified end point in the result line, add it now.
			if ((($xEnd - $x) * ($xEnd - $x) + ($yEnd - $y) * ($yEnd - $y)) <
			(($xBegin - $x) * ($xBegin - $x) + ($yBegin - $y) * ($yBegin - $y))) {
				// Then we're closer to the end
				$dots[] = array($xEnd, $yEnd);
			} else {
				$dots[] = array($xBegin, $yBegin);
			}
		}
			
		return $dots;
	}

	public static function GraphOrientes (Array $graph) {
		// récupérer la liste des indices

		$indices = array();

		$matrice = new Matrice();
		//$matrice->configuration->dynamique = true;

		foreach ($graph as $ligne) {
			if($ligne instanceof MatriceGraph) {
				if(!in_array($ligne->point1, $indices)) {
					$indices[] = $ligne->point1;
				}

				if(!in_array($ligne->point2, $indices)) {
					$indices[] = $ligne->point2;
				}				
			}

			$matrice->ajouterPoint($ligne->point1, $ligne->point2, $ligne->data);
		}


	}

}

class CubeMatrice extends Matrice {

}

class MatriceGraph {
	public $point1;
	public $point2;
	public $data;

	public function __construct($point1, $point2, $data) {
		$this->point1 = $point1;
		$this->point2 = $point2;
		$this->data = $data;
	}
}