<?php
/********************************************
Auteur                : Chapalain Yoann
Site web              : http://www.happyflash.fr
Fichier               : FormException.class.php
Dernière modification : En cours      	
 *******************************************/
 class FormException extends Exception
 {
	/**
	 * Constructeur de la classe Form
	 * Traite toutes les exceptions relatives au framework FormBoost
	 * @param message		Message à afficher.
	 * @param code			Code rattaché à l'exception.
	 * @return void
	 */
    public function __construct($message, $code = 0) {
        parent::__construct($message, $code);
    }
	// Redéfinition de toString
    public function __toString() {
        return __CLASS__ . " [{$this->code}]: {$this->message}\n";
    }
} 
?>