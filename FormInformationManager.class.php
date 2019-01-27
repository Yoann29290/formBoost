<?php
/********************************************
Auteur                : Chapalain Yoann
Site web              : http://www.happyflash.fr
Fichier               : FormInformationManager.class.php
Derni�re modification : En cours      	
 *******************************************/
class FormInformationManager
{
	// - -- Attributs 
        /** Tableaux d'item information */
        private $arrayInformation; 
        /** Image illustration */
        private $urlImg;

	/**
	* Constructeur de la classe FormInformationManager
	*
	*/
	public function __construct($imgSrc = "", $arrayInformation = array()) {
		$this->arrayInformation = array();
		$this->arrayInformation = $arrayInformation;
                $this->urlImg = $imgSrc;
	}
	/**
	* getArrayInformation
	* Fonction de renvoi du conteneur d'information.
	* @return Array() arrayInformation
	*/
	function getArrayInformation(){
		return $this->arrayInformation;
	}
	/**
	* addInformation
	* Fonction d'ajout d'information dans le conteneur d'information.
	* @param FormInformationItem : une information
	*/
	function addInformation(FormInformationItem $anInformation){
		array_push($this->arrayInformation, $anInformation);
	}
	
	/**
	* addBulkInformation
	* Fonction d'ajout d'un bloc d'information dans le conteneur d'information.
	* @param FormInformationItem : une information
	*/
	function addBulkInformation($arrayInformation){
		foreach($arrayInformation as $informationItem)
			$this->addInformation($informationItem);
	}
        
        /**
	* 
	* Definit l'image d'illustration
	* @param imgSrc : une information
	*/
	function setUrlImg($imgSrc){
		$this->urlImg = $imgSrc;
	}
        
       /**
	* 
	* Retourne l'image d'illustration
	* @return imgSrc : une information
	*/
	function getUrlImg() {
		return $this->urlImg;
	}
        
	/**
	 * toXML
	 * Fonction de g�n�ration de l'arbre XML correspondant aux informations du formulaire.
	 * L'arbre sera directement rattach� au noeud XML $parent_node
	 * @param xml   		Flux XML appelant.
	 * @param parent_node   Noeud parent.
	 * @return void
	 */
	function toXML(&$xml, &$parent_node){
		// - -- Remonte une exception si le formulaire n'a pas transmis de contexte xml.
		if(!isset($xml)){
			throw new FormException("Erreur de generation - Impossible d'associer le formulaire a un flux XML!");
		}
		if(!isset($parent_node)){
			throw new FormException("Erreur de generation - Impossible d'associer le formulaire a son noeud parent!");
		}
		// -- Ajout des messages d'erreur :
		if(count($this->arrayInformation) > 0){		
			$xml_box_information = $xml->createElement('informationBox');
                        $parent_node->appendChild($xml_box_information);                        
                        $xml_box_information_src = $xml->createElement('imgSrc', $this->getUrlImg());  
                        $xml_box_information->appendChild($xml_box_information_src);
			foreach ($this->arrayInformation as $msg) {				
                            $xml_box_information_item = $xml->createElement('informationBoxItem');
                            $xml_box_information_item->setAttribute('icon', $msg->getUrlIcon() );	
                            $xml_box_information_item->setAttribute('label', $msg->getInformationMessage());
                            $xml_box_information->appendChild($xml_box_information_item);
			}
		}
	}
} 
?>