<?php
/********************************************
Auteur                : Chapalain Yoann
Site web              : http://www.happyflash.fr
Fichier               : StringValidator.class.php
Dernière modification : En cours      	
*******************************************/
class StringValidator extends Validator
{
        /** Taille minimale que la chaine a valider doit verifier */
	private $minLenght;
        /** Taille maximale que la chaine a valider doit verifier */
	private $maxLenght;
	
	/**
	* Constructeur de la classe field.
	* @param errorMessage		Message d'erreur associé.
	* @return void
	*/
	function __construct($minLenght=0, $maxLenght=100, $errorMessage = null) {
		// TODO : lever une exception si min > max et min et/ou max < 0
		// En attendant .. vérification manuelle : quick & dirty !
		if($minLenght > $maxLenght){
			$maxLenght = $minLenght;
			$minLenght = 1;
		}
		$this->minLenght = $minLenght;
		$this->maxLenght = $maxLenght;
		if(!isset($errorMessage) || $errorMessage == null){
			$errorMessage = "Entre ".$minLenght." et ".$maxLenght." caractères attendus.";
		}
		parent::__construct($errorMessage);
	}
	/**
	* validate /@override
	* Fonction de validation d'une expression.
	* @param expression	Expression à valider
	* @return vrai(true) si le champs est valide ou faux(false) si il est invalide
	*/
	function validate($expression){
		$valid = true;
		if(strlen($expression) > $this->maxLenght || strlen($expression) < $this->minLenght){
			$valid = false;
		}		
		return $valid;
	}
}
?>