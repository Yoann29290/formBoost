<?php
/********************************************
Auteur                : Chapalain Yoann
Site web              : http://www.happyflash.fr
Fichier               : FormValidator.class.php
Dernière modification : En cours      	
*******************************************/
 abstract class Validator
 {
	// - -- Attributs
	private  $errorMessage; // Message associé en cas de non validation du champs testé.
	private	 $fieldLibelle;	// Désignation du associé.
	
	/**
	 * Constructeur de la classe field.
	 * @param errorMessage		Message d'erreur associé.
	 * 
	 */
	function __construct($errorMessage) {
		$this->errorMessage = $errorMessage;
	}
	/**
	 * valid
	 * Fonction de validation d'une expression.
	 * 
	 * @return vrai(true) si la validation est effectuée ou faux(false) dans le cas contraire
	 */
	abstract function validate($expression);
	/*
	 * - -- Accesseurs.
	 */
	function getErrorMessage(){
		return $this->getFieldLibelle() . " : " . $this->errorMessage;
	}
	function getFieldLibelle(){
		return $this->fieldLibelle;
	}
	/*
	 * - -- Setters.
	 */
	function setErrorMessage($errorMessage){
		$this->errorMessage = $errorMessage;
	}
	function setFieldLibelle($fieldLibelle){
		$this->fieldLibelle = $fieldLibelle;
	}
 }

?>