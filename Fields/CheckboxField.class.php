<?php
/********************************************
Auteur                : Chapalain Yoann
Site web              : http://www.happyflash.fr
Fichier               : CheckBoxField.class.php
Dernière modification : En cours      	
 *******************************************/
 class CheckBoxField extends Field
 {
	// - -- Attributs
	/** Tableau des valeurs possible */
	private $arrayValue = array();  
	/** Nombre de ligne */
	private $nbRows;			    
	/** Nombre de colonne */
	private $nbCols;	
	/** Style CSS donné au fieldset */
	private $cssFieldSet;	
	
	/**
	 * Constructeur de la classe checkBoxField.
	 * @param name				Nom du champs.
	 * @param label				Libellé du champs.
	 * @param arrayValue		Tableau des valeurs du groupe de selection.
	 * @param required			Indique si le champs doit être remplis.
	 * @param validator			Permet de connaitre le type de vérification à effectuer (ex: email, captcha, password...).
	 * 
	 */
	function __construct($name, $label, $arrayValue, $checked, $required, $validator) {
		parent::__construct($name, $label, is_array($checked)?$checked:array(), $required, $validator);
		$this->arrayValue = array();
		$this->arrayValue = $arrayValue;
		// - Valeur par défaut.
		$this->nbRows = 5; // Nomnbre maximum de ligne.
		$this->nbCols = 5; // Nombre maximum d'item par ligne.
		$this->setCssFieldSet("testFieldSet");
	}
	// - Accesseurs et modifieurs
	function getNbRows(){
		return $this->nbRows;
	}
	function setNbRows($nbRows){
		$this->nbRows = $nbRows;
	}
	function getNbCols(){
		return $this->nbCols;
	}
	function setNbCols($nbCols){
		$this->nbCols = $nbCols;
	}
	/**
	 * addValue
	 * Fonction d'ajout d'une valeur.
	 * @return void
	 */
	function addValue($value){
		array_push($this->arrayValue, $value);
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
	 * Définit le style css du fieldset.
	 * @param cssFieldSet : le style css du fieldset
	 */
	function setCssFieldSet($cssFieldSet){
		$this->cssFieldSet = $cssFieldSet;
	}
	/**
	 * toXML
	 * Fonction de génération de l'arbre XML correspondant au formulaire.
	 * L'arbre sera directement rattaché au noeud XML $parent_node
	 * L'arbre généré aura alors la forme suivante :
	 * <fieldElement name="..." >
	 * 	<checkboxField>
	 *  	<row>
	 * 			<checkbox name="..." value="..." id="..." /> 
	 * 			<checkbox name="..." value="..." id="..." /> 
	 * 			<checkbox name="..." value="..." id="..." /> 
	 *  	</row>	 
	 *  	<row>
	 * 			<checkbox name="..." value="..." id="..." /> 
	 * 			<checkbox name="..." value="..." id="..." /> 
	 *  	</row>
	 * 	</select>
	 * </fieldElement>
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
		$xml_fieldElement->setAttribute('label', $this->getLabel());		
		$xml_fieldElement->setAttribute('for', $this->getName());	
		//		
		if($this->getRequired()) $xml_fieldElement->setAttribute('required', 'true'); // Champs requis ?
		$xml_inputElement = $this->xml->createElement('checkboxField');		
		$xml_inputElement->setAttribute('label', ucfirst($this->getLabel()));
		$xml_inputElement->setAttribute('error', $this->getError()); 	// Gestion des erreurs
		$xml_inputElement->setAttribute('name', $this->getName()."[]"); // On doit récupérer un tableau.
		if($this->getCssClass())
			$xml_inputElement->setAttribute('cssClass', $this->getCssClass()); // Classe css
		if($this->getCssFieldSet() != null)
			$xml_inputElement->setAttribute('cssFieldSet', $this->getCssFieldSet()); // Classe css du fieldset
		// -- Les checkbox
		$cpt_row = 0; // Compteur de boucle de rows.
		$cpt_col = 0; // Compteur de boucle de cols.
		foreach ($this->arrayValue as $checkbox) {
			// Creation d'une ligne si on a pas atteint le nombre max de ligne autorisé
			// et si on a terminé la ligne précédente.
			if($cpt_row < $this->nbRows && !$cpt_col){
				$xml_row = $this->xml->createElement('row');
				$xml_inputElement->appendChild($xml_row); 
			}
			$xml_checkbox = $this->xml->createElement('checkbox');	
			if(in_array($checkbox, $this->getValue()) && !$valid ){
				$xml_checkbox->setAttribute('checked', "true"); 				
			}
			$xml_checkbox->appendChild($this->xml->createCDataSection($checkbox) );			
			$xml_row->appendChild($xml_checkbox); // Ajout dans la ligne
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