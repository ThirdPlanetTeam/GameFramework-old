<?php

/**********************************
 * Dice Roll by Ludovic Magerand  *
 **********************************/

namespace api\tools;

class Dice {

	const DefaultFaces = 6;

	public static function Roll($nb,$faces = Dice::DefaultFaces) {

		$max = mt_getrandmax()+1.0;

		$u = sqrt(-2*log(mt_rand()/$max));
		$v = 2*M_PI*mt_rand()/$max;

		$result = round(sqrt(2.0*$nb/3.0)*$u*cos($v)+2.0*$nb);

		return min(max($nb,$result),$faces*$nb);

	}	

}