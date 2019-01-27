<?php
/********************************************
Auteur                : Chapalain Yoann
Site web              : http://www.happyflash.fr
Fichier               : FieldSet.class.php
Dernière modification : En cours      	
 *******************************************/
 class FieldSet
 {
	// - -- Attributs
	/** Context XML du document conteneur */
	private $xml;
	/** Noeud parent du document auquel sera rattaché le flux XML généré pour le formulaire */
	private $parent_node;
	/** Tableau de champs */
	private $arrayField;
	/** Label */
	private $label;
	/** cssClass */
	private $cssClass;
	
	/**
	 * Constructeur de la classe FieldSet
	 * @param label				Label du fieldset (cf legend en HTML).
	 * @param arrayField		Tableau des champs du formulaire.
	 * @param cssClass			Classe CSS affectée .
	 * 
	 */
	function __construct($label, $arrayField = array(), $cssClass ) {
		$this->label = $label;
		if(!is_array($arrayField)) $this->arrayField = array();
		else $this->arrayField = $arrayField;
		$this->cssClass = $cssClass;
	}
	
	/**
	 * addField
	 * Fonction d'ajout d'un champs au fieldset.
	 * @param field (Field) : un champs
	 * 
	 */
	function addField($field){
		if($field != null)
			array_push($this->arrayField, $field);
	}
	
	// - -- Getters & Setters
	/**
	 * setLabel
	 * Définit d'un label.
	 * @param label.
	 * 
	 */
	function setLabel($label){
		$this->label = $label;
	}
	/**
	 * setCssClass
	 * Définit de la classe css du fieldset.
	 * @param cssClass.
	 * 
	 */
	function setCssClass($cssClass){
		$this->cssClass = $cssClass;
	}
	/**
	 * setXml
	 * Définit d'un contexte xml.
	 * @param xml.
	 * 
	 */
	function setXml(&$xml){
		$this->xml = $xml;
	}
	/**
	 * setParentNode
	 * Définit du noeud parent auquel sera rattaché directement le formulaire.
	 * @param parent_node.
	 * 
	 */
	function setParentNode(&$parent_node){
		$this->parent_node = $parent_node;
	}	
	/**
	 * getLabel
	 * Retourne le label de ce fieldset.
	 * @return label : label du fieldset
	 */
	function getLabel(){
		return $this->label;
	}
	/**
	 * getArrayField
	 * Retourne le tableau des field du set.
	 * @return arrayField : tableau des fields du set
	 */
	function getArrayField(){
		return $this->arrayField;
	}
	/**
	 * getCssClass
	 * Retourne la classe CSS de ce fieldSet.
	 * @return cssClass : classe CSS du fieldSet
	 * 
	 */
	function getCssClass(){
		return $this->cssClass;
	}	
	
	// - -- Représentation / export
	/**
	 * toXML
	 * Fonction de génération de l'arbre XML correspondant au formulaire.
	 * L'arbre sera directement rattaché au noeud XML $parent_node
	 * @param Bool : Flag d'indication de la prise en compte de la validation.
	 *
	 */
	function toXML($flag){	
		// - -- Remonte une exception si le formulaire n'a pas de contexte xml.
		if(!isset($this->xml)){
			throw new FormException("Erreur de génération - Impossible d'associer le formulaire à un flux XML!");
		}
		if(!isset($this->parent_node)){
			throw new FormException("Erreur de génération - Impossible d'associer le formulaire à son noeud parent!");
		}	
		$xml_fieldSet = $this->xml->createElement('fieldSet'); 
		$xml_fieldSet->setAttribute('legend', ucfirst($this->getLabel()));	// Legend
		if($this->getCssClass()) 
			$xml_fieldSet->setAttribute('cssClass', $this->getCssClass());	// Class CSS		
		foreach ($this->getArrayField() as $field){ 
			$field->setXml($this->xml);
			$field->setParentNode($xml_fieldSet);				
			$field->toXml($flag);
		}
		$this->parent_node->appendChild($xml_fieldSet);
	}
 } 
 ?>