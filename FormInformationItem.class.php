<?php
/********************************************
Auteur                : Chapalain Yoann
Site web              : http://www.happyflash.fr
Fichier               : FormInformationItem.class.php
Derni�re modification : En cours      	
 *******************************************/
class FormInformationItem
{
	// - -- Attributs 
	private $informationMessage; // - -- Libelle de l'information
	private $urlIcon;            // - -- Url/chemin de l'icone associee
        
	/**
	* Constructeur de la classe FormInformationItem
	*
	* @return void
	*/
	public function __construct($informationMessage, $urlIcon=null) {
		$this->informationMessage = $informationMessage;
		$this->urlIcon = $urlIcon;
	}
	/**
	* getinformationMessage
	* Fonction de renvoi du message d'information.
	* @return String informationMessage
	*/
	function getInformationMessage(){
		return $this->informationMessage;
	}
	/**
	* setError
	* Fonction de d�finition du message.
	* @param String : un message d'information
	* @return void
	*/
	function setInformation(string $informationMessage){
		$this->informationMessage = $informationMessage;
	}
	/**
	* geturlIcon
	* Fonction de renvoi de l'ic�ne.
	* @return int urlIcon
	*/
	function getUrlIcon(){
		return $this->urlIcon;
	}
	/**
	* setError
	* Fonction de d�finition de l'url de l'ic�ne.
	* @param string : L'url
	* @return void
	*/
	function setUrlIcon(int $urlIcon){
		$this->urlIcon = $urlIcon;
	}
} 
?>