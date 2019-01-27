<?php
/********************************************
Auteur                : Chapalain Yoann
Site web              : http://www.happyflash.fr
Fichier               : FormErrorItem.class.php
Dernière modification : En cours      	
 *******************************************/
class FormErrorItem
{
	// - -- Attributs 
	private $errorMessage; // - -- Libelle de l'erreur
	/**
	* Constructeur de la classe FormErrorItem
	*
	* @return void
	*/
	public function __construct($errorMessage) {
		$this->errorMessage = $errorMessage;
	}
	/**
	* getErrorMessage
	* Fonction de renvoi du message d'erreur.
	* @return String errorMessage
	*/
	function getErrorMessage(){
		return $this->errorMessage;
	}
	/**
	* setError
	* Fonction de définition du message.
	* @param String : un message d'erreur
	* @return void
	*/
	function setError(string $errorMessage){
		$this->errorMessage = $errorMessage;
	}
} 
?>