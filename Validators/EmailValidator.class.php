<?php
/********************************************
Auteur                : Chapalain Yoann
Site web              : http://www.happyflash.fr
Fichier               : EmailValidator.class.php
Dernière modification : En cours      	
*******************************************/
class EmailValidator extends Validator
{
	/**
	* Constructeur de la classe field.
	* @param errorMessage		Message d'erreur associé.
	* @return void
	*/
	function __construct($errorMessage='Format incorrect.') {
	
		parent::__construct($errorMessage);
	}
	/**
	* validate /@override
	* Fonction de validation d'une expression.
	* @param expression	Expression à valider
	* @return vrai(true) si l'adresse est valide ou faux(false) si elle est invalide
	*/
	function validate($expression){
		$regex = '#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#';
		return preg_match($regex,trim($expression));
	}
}
?>