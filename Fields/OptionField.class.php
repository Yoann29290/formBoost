<?php
/********************************************
Auteur                : Chapalain Yoann
Site web              : http://www.happyflash.fr
Fichier               : OptionField.class.php
DerniÃ¨re modification : En cours      	
 *******************************************/
 class OptionField extends Field
 {
	// - -- Attributs
	/** Tableau des valeurs possible */
	private $arrayOption = array();   
	/** Nombre de ligne */
	private $nbRows;			     
	/** Nombre de colonne */
	private $nbCols;	
	/** Style CSS donné au fieldset */
	private $cssFieldSet;
	/**
	 * Constructeur de la classe OptionField.
	 * @param name				Nom du champs.
	 * @param label				Libellé du champs.
	 * @param arrayOption		Tableau des options du select.
	 * @param required			Indique si le champs doit etre remplis.
	 * @param validator			Permet de connaitre le type de vérification a effectuer (ex: email, captcha, password...).
	 */
	function __construct($name, $label, $arrayOption, $selected, $required) {
		parent::__construct($name, $label, $selected, $required, null); // Pas de validator
		$this->arrayOption = array();
		$this->arrayOption = $arrayOption;
		// - Valeur par defaut.
		$this->nbRows = 5; // Nombre maximum de ligne.
		$this->nbCols = 5; // Nombre maximum d'item par ligne.
		$this->setCssFieldSet("testFieldSet");
	}	
	/**
	 * addOption
	 * Fonction d'ajout d'une option.
	 */
	function addOption($option){
		array_push($this->arrayOption, $option);
	}
	/**
	 * getCssFieldSet
	 * Retourne le style css du fieldset.
	 * @return cssFieldSet : le style css du fieldset
	 */
	function getCssFieldSet(){
		return $this->cssFieldSet;
	}
	/**
	 * setCssFieldSet
	 * Definit le style css du fieldset.
	 * @param cssFieldSet : le style css du fieldset
	 */
	function setCssFieldSet($cssFieldSet){
		$this->cssFieldSet = $cssFieldSet;
	}
	
	/**
	 * validate 
	 * Fonction de validation du champs.
	 * @return vrai : Impossible de ne pas selectionner une valeur
	 */
	function validate() {		
		foreach ($this->arrayOption as $option) {
			if($option == $this->getValue()) {
				return true;
			}
		}
		return false;
	}
	
	function getValue() {
		if(isset($_POST[$this->getName()])) {
			return $_POST[$this->getName()];
		}else if(isset($_GET[$this->getName()])) {
			return $_GET[$this->getName()];;
		}
	}
	
	/**
	 * toXML
	 * Fonction de génération de l'arbre XML correspondant au formulaire.
	 * L'arbre sera directement rattaché au noeud XML $parent_node
	 * L'arbre généré aura alors la forme suivante 
	 * <fieldElement name="..." >
	 * 	<checkboxField>
	 *  	<row>
	 * 			<option name="..." value="..." id="..." /> 
	 * 			<option name="..." value="..." id="..." /> 
	 * 			<option name="..." value="..." id="..." /> 
	 *  	</row>	 
	 *  	<row>
	 * 			<option name="..." value="..." id="..." /> 
	 * 			<option name="..." value="..." id="..." /> 
	 *  	</row>
	 * 	</select>
	 * </fieldElement>
	 * @param Bool : Flag d'indication de la prise en compte de la validation;
	 * @throws FormException
	 */
	function toXML($valid = false){
		// - -- Remonte une exception si le formulaire n'a pas de contexte xml.
		if(!isset($this->xml)){
			throw new FormException("Erreur de génération - Impossible d'associer le formulaire Ã  un flux XML!");
		}
		if(!isset($this->parent_node)){
			throw new FormException("Erreur de génération - Impossible d'associer le formulaire Ã  son noeud parent!");
		}
		$xml_fieldElement = $this->xml->createElement('fieldElement');
		$xml_fieldElement->setAttribute('label', $this->getValue());		
		$xml_fieldElement->setAttribute('for', $this->getName());		
		if($this->getRequired()) $xml_fieldElement->setAttribute('required', 'true'); // Champs requis ?
		$xml_inputElement = $this->xml->createElement('optionField');		
		$xml_inputElement->setAttribute('label', ucfirst($this->getLabel()));
		$xml_inputElement->setAttribute('error', $this->getError()); 	// Gestion des erreurs
		$xml_inputElement->setAttribute('name', $this->getName()); 
		if($this->getCssClass() != null)
			$xml_inputElement->setAttribute('cssClass', $this->getCssClass()); // Classe css
		if($this->getCssFieldSet() != null)
			$xml_inputElement->setAttribute('cssFieldSet', $this->getCssFieldSet()); // Classe css du fieldset
		// -- Les options
		$cpt_row = 0; // Compteur de boucle de rows.
		$cpt_col = 0; // Compteur de boucle de cols.
		foreach ($this->arrayOption as $option) {
			// Creation d'une ligne si on a pas atteint le nombre max de ligne autorisé
			// et si on a terminé la ligne précédente.
			if($cpt_row < $this->nbRows && !$cpt_col){
				$xml_row = $this->xml->createElement('row');
				$xml_inputElement->appendChild($xml_row); 
			}
			$xml_option = $this->xml->createElement('option');			
			if($option == $this->getValue() && !$valid){
				$xml_option->setAttribute('checked', "true"); 				
			}
			$xml_option->appendChild($this->xml->createCDataSection($option) );			
			$xml_row->appendChild($xml_option); // Ajout dans la ligne
			if($cpt_col+1 < $this->nbCols){
				$cpt_col++;
			}else{
				$cpt_col = 0;
				$cpt_row++;
			}
		} 
		$this->parent_node->appendChild($xml_fieldElement);
		$xml_fieldElement->appendChild($xml_inputElement);
	}
}
 ?>