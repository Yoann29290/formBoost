<?php
/********************************************
Auteur                : Chapalain Yoann
Site web              : http://www.happyflash.fr
Fichier               : Button.class.php
Derniere modification : En cours
 *******************************************/
 class Button
 {
	// - -- Attributs
	private $name;
	private $label;
	private $type;
	private $cssClass;
	private $xml;
	private $parent_node;
	
	/**
	 * Constructeur de la classe Button
	 * @param name			Nom du bouton.
	 * @param label			Label du bouton.
	 * @param type			Type du bouton (submit, reset, lien).
	 * @param class			Class CSS du bouton.
	 */
	function __construct($name, $label, $type, $cssClass ) {
		$this->name = $name;
		$this->label = $label;
		$this->type = $type;
		$this->cssClass = $cssClass;
	}
	/**
	 * getName
	 * Retourne le nom du bouton.
	 * @return name.
	 */
	function getName(){
		return $this->name;
	}
	/**
	 * getLabel
	 * Retourne le label du bouton.
	 * @return label.
	 */
	function getLabel(){
		return $this->label;
	}
	/**
	 * getType
	 * Retourne le type du bouton.
	 * @return type.
	 */
	function getType(){
		return $this->type;
	}
	/**
	 * getCssClass
	 * Retourne la class du bouton.
	 * @return class.
	 */
	function getCssClass(){
		return $this->cssClass;
	}
	/**
	 * setName
	 * Definit le nom du bouton (pour le html).
	 * @param name : le nom HTML du bouton.
	 */
	function setName($name){
		$this->name = $name;
	}
	/**
	 * setLabel
	 * Definit le label du bouton.
	 * @param label.
	 */
	function setLabel($label){
		$this->label = $label;
	}
	/**
	 * setType
	 * Definit le type du bouton.
	 * @param type.
	 */
	function setType($type){
		$this->type = $type;
	}
	/**
	 * setCssClass
	 * Definit la class du bouton.
	 * @param class : un classe css.
	 */
	function setCssClass($cssClass){
		$this->cssClass = $cssClass;
	}
	/**
	 * setXml
	 * Definit le context XML du bouton.
	 * @param xml : un context XML.
	 */
	function setXml($xml){
		$this->xml = $xml;
	}
	/**
	 * setXml
	 * Definit le noeud parent du bouton.
	 * @param pn : un noeud.
	 */
	function setParentNode($pn){
		$this->parent_node = $pn;
	}
	/**
	 * toXML
	 * Fonction de g�n�ration de l'arbre XML correspondant au formulaire.
	 * L'arbre sera directement rattach� au noeud XML $parent_node
	 * L'arbre genere aura alors la forme suivante
	 * <button label="..." type="..." cssClass="..." />
	 *  
	 */
	function toXML(){
		// - -- Gerer exception : contexte xml et dom
		$xml_button = $this->xml->createElement('button');
		$xml_button->setAttribute('label', ucfirst($this->getLabel()));
		$xml_button->setAttribute('name', ucfirst($this->getName()));
		$xml_button->setAttribute('type', $this->getType());
		$xml_button->setAttribute('cssClass', $this->getCssClass());
		$this->parent_node->appendChild($xml_button);
	}
}
?>