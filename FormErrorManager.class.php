<?php
/********************************************
Auteur                : Chapalain Yoann
Site web              : http://www.happyflash.fr
Fichier               : FormErrorManager.class.php
Derniere modification : En cours
 *******************************************/
class FormErrorManager
{
	// - -- Attributs 
	private $arrayError; // - -- Conteneur des erreurs
	/**
	* Constructeur de la classe FormErrorManager
	*
	* @return void
	*/
	public function __construct($arrayError = array()) {
		$this->arrayError = array();
		$this->arrayError = $arrayError;
	}
	/**
	* getarrayError
	* Fonction de renvoi du conteneur d'erreur.
	* @return Array() arrayError
	*/
	function getArrayError(){
		return $this->arrayError;
	}
	/**
	* addError
	* Fonction d'ajout d'erreur dans le conteneur d'erreurs.
	* @param FormErrorItem : une erreur
	* @return void
	*/
	function addError(FormErrorItem $anError){
		array_push($this->arrayError, $anError);
	}
	/**
	 * toXML
	 * Fonction de g�n�ration de l'arbre XML correspondant aux erreurs du formulaire.
	 * L'arbre sera directement rattache au noeud XML $parent_node
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
		if(count($this->arrayError) > 0){		
			$xml_box_error = $xml->createElement('errorBox');
			foreach ($this->arrayError as $errorMsg) {
				$xml_box_error_item = $xml->createElement('errorBoxItem');
				$xml_box_error_item->setAttribute('label', $errorMsg->getErrorMessage());
				$xml_box_error->appendChild($xml_box_error_item);
			}
			$parent_node->appendChild($xml_box_error);
		}
	}
} 
?>