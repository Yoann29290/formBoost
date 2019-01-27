<?php
/********************************************
Auteur                : Chapalain Yoann
Site web              : http://www.happyflash.fr
Fichier               : SelectField.class.php
Dernière modification : En cours      	
 *******************************************/
 class SelectField extends Field
 {
	// - -- Attributs
	/** Tableau des options */
	private $arrayOption = array();
	/**
	 * Constructeur de la classe SelectField.
	 * @param name				Nom du champs.
	 * @param label				Libellé du champs.
	 * @param arrayOption		Tableau des options du select.
	 * @param required			Indique si le champs doit être remplis.
	 * @param validator			Permet de connaitre le type de vérification à effectuer (ex: email, captcha, password...).
	 * @return void
	 */
	function __construct($name, $label, $arrayOption, $selected, $required, $validator) {
		parent::__construct($name, $label, $selected, $required, $validator);
		$this->arrayOption = array();
		$this->arrayOption = $arrayOption;
	}	
	/**
	 * addOption
	 * Fonction d'ajout d'une option.
	 * @return void
	 */
	function addOption($option){
		array_push($this->arrayOption, $option);
	}
	
	/**
	 * validate 
	 * Fonction de validation du champs.
	 * @return vrai : Impossible de ne pas selectionner une valeur
	 */
	function validate() {		
		return true;
	}
	
	
	/**
	 * toXML
	 * Fonction de génération de l'arbre XML correspondant au formulaire.
	 * L'arbre sera directement rattaché au noeud XML $parent_node
	 * L'arbre généré aura alors la forme suivante 
	 * <fieldElement>
	 * <select [multiple="multiple"]>
	 * 	<option[selected]>OPTION 1 </option>
	 * 	<option>OPTION ... </option>
	 * 	<option>OPTION N </option>
	 * </select>
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
		$xml_inputElement = $this->xml->createElement('selectField');		
		$xml_inputElement->setAttribute('label', ucfirst($this->getLabel()));
		$xml_inputElement->setAttribute('error', $this->getError()); // Gestion des erreurs
		$xml_inputElement->setAttribute('name', $this->getName());
		if($this->getCssClass())
			$xml_inputElement->setAttribute('cssClass', $this->getCssClass()); // Classe css
		// -- Les options
			foreach ($this->arrayOption as $option) { 
				$xml_option = $this->xml->createElement('option');
				$isSelectedPost = isset($_POST[$this->getName()])? $_POST[$this->getName()]==$option:false;
				$isSelectedGet = isset($_GET[$this->getName()])? $_GET[$this->getName()]==$option:false;
				if(($option == $this->getValue() || $isSelectedPost || $isSelectedGet)  && !$valid){
					$xml_option->setAttribute('selected', "true"); 				
				}
				$xml_option->appendChild($this->xml->createCDataSection($option) );			
				$xml_inputElement->appendChild($xml_option);
			} 
			$this->parent_node->appendChild($xml_fieldElement);
			$xml_fieldElement->appendChild($xml_inputElement);
	}
}
 ?>