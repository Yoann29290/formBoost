<?php
/********************************************
Auteur                : Chapalain Yoann
Site web              : http://www.happyflash.fr
Fichier               : CheckBoxValidator.class.php
Derni�re modification : En cours      	
*******************************************/
class CheckBoxValidator extends Validator
{
	private $minNbCheck;
	private $maxNbCheck;
	
	/**
	* Constructeur de la classe field.
	* @param minNbCheck			Nombre de case � cocher au minimum.
	* @param maxNbCheck			Nombre de case � cocher au maximum.
	* @param nomChamps			D�signation du champs.
	* @param errorMessage		Message d'erreur associ�.
	* @return void
	*/
	function __construct($minNbCheck=0, $maxNbCheck, $errorMessage) {
		// TODO : lever une exception si min > max et min et/ou max < 0
		// En attendant .. v�rification manuelle : quick & dirty !	
		$this->minNbCheck = $minNbCheck;
		$this->maxNbCheck = $maxNbCheck;
		if(!isset($errorMessage) || $errorMessage == null){
			$errorMessage = "Veuillez cocher entre ".$minNbCheck." et ".$maxNbCheck." cases.";
		}
		parent::__construct($errorMessage);
	}
	/**
	* validate @override
	* Fonction de validation d'une expression.
	* @param arrayCheckBox	Tableau des checkBox � valider
	* @return vrai(true) si le champs est valide ou faux(false) si il est invalide
	*/
	function validate($arrayCheckBox){
		$valid = true;
		if(count($arrayCheckBox) > $this->maxNbCheck || count($arrayCheckBox) < $this->minNbCheck){
			$valid = false;
		}
		return $valid;
	}
}
?>