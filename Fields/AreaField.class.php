<?php
/********************************************
Auteur                : Chapalain Yoann
Site web              : http://www.happyflash.fr
Fichier               : AreaField.class.php
Dernière modification : En cours      	
 *******************************************/
 class AreaField extends Field
 {
	// - -- Attributs
	/** Taille du textArea en colonnes */
	private $cols;
	/** Taille du textArea en lignes */
	private $rows;
	
	/**
	 * Constructeur de la classe areaField.
	 * @param name				Nom du champs.
	 * @param label				Libellé du champs.
	 * @param value				Valeur permettant de renseigner le champs.
	 * @param required			Indique si le champs doit être remplis.
	 * @param validator			Permet de connaitre le type de vérification à effectuer (ex: email, captcha, password...).
	 * @return void
	 */
	function __construct($name, $label, $value=null, $required, $validator) {
		parent::__construct($name, $label, $value, $required, $validator);
		$this->cols = 50;
		$this->rows = 7;
	}
	/**
	 * getCols
	 * Retourne la taille du textArea en colonnes.
	 * @return cols : taille du textArea en colonnes
	 */
	function getCols(){
		return $this->cols;
	}
	/**
	 * getRows
	 * Retourne la taille du textArea en lignes.
	 * @return rows : taille du textArea en lignes
	 */
	function getRows(){
		return $this->rows;
	}
	
	/**
	 * setCols
	 * Définit la taille du textArea en colonnes.
	 * @param cols : taille du textArea en colonnes
	 */
	function setCols($cols){
		$this->cols = $cols;
	}
	/**
	 * setRows
	 * Définit la taille du textArea en lignes.
	 * @param rows : taille du textArea en lignes
	 */
	function setRows($rows){
		$this->rows = $rows;
	}

	/**
	 * toXML
	 * Fonction de génération de l'arbre XML correspondant au formulaire.
	 * L'arbre sera directement rattaché au noeud XML $parent_node
	 * L'arbre généré aura alors la forme suivante 
	 * <fieldElement>
	 * 	<areaField name=... label=... error=... cols=... rows=...>
	 * 		value
	 * 	</textField>
	 * </fieldElement>
	 * @param Bool : Flag d'indication de la prise en compte de la validation.
	 * @throws FormException
	 */
	function toXML($valid = false){
		// - -- Remonte une exception si le formulaire n'a pas de contexte xml.
		if(!isset($this->xml)){
			throw new FormException("Erreur de génération - Impossible d'associer le formulaire à un flux XML!");
		}
		if(!isset($this->parent_node)){
			throw new FormException("Erreur de génération - Impossible d'associer le formulaire à son noeud parent!");
		}
		$xml_fieldElement = $this->xml->createElement('fieldElement');
		$xml_fieldElement->setAttribute('label', ucfirst($this->getLabel()));
		$xml_fieldElement->setAttribute('for', $this->getName());
		// Champs requis ?
		if($this->getRequired()) $xml_fieldElement->setAttribute('required', 'true');
		$xml_inputAreaElement = $this->xml->createElement('areaField');
		$xml_inputAreaElement->setAttribute('label', ucfirst($this->getLabel()));
		$xml_inputAreaElement->setAttribute('name', $this->getName());
		$xml_inputAreaElement->setAttribute('error', $this->getError()); // Gestion des erreurs
		$xml_inputAreaElement->setAttribute('cols', $this->getCols()); 
		$xml_inputAreaElement->setAttribute('rows', $this->getRows()); 
		if($this->getCssClass())
			$xml_inputAreaElement->setAttribute('cssClass', $this->getCssClass()); // Classe css
		// Si le formulaire est valide, il est envoyé, on efface les champs
		if(!$valid) $xml_inputAreaElement->appendChild($this->xml->createCDataSection($this->getValue()) );
		$this->parent_node->appendChild($xml_fieldElement);
		$xml_fieldElement->appendChild($xml_inputAreaElement);
	}
}
 ?>