<?php
/********************************************
Auteur                : Chapalain Yoann
Site web              : http://www.happyflash.fr
Fichier               : PasswordField.class.php
Dernière modification : En cours      	
 *******************************************/
 class PasswordField extends Field
 {
	/**
	 * Constructeur de la classe passwordField.
	 * @param name				Nom du champs.
	 * @param label				Libellé du champs.
	 * @param value				Valeur permettant de renseigner le champs.
	 * @param required			Indique si le champs doit être remplis.
	 * @param validator			Permet de connaitre le type de vérification à effectuer (ex: email, captcha, password...).
	 */
	function __construct($name, $label,  $value=null, $required, $validator=null) {
		parent::__construct($name, $label, $value, $required, $validator);
	}
	/**
	 * toXML
	 * Fonction de génération de l'arbre XML correspondant au formulaire.
	 * L'arbre sera directement rattaché au noeud XML $parent_node
	 * L'arbre généré aura alors la forme suivante 
	 * <fieldElement>
	 * 	<passwordField name=... label=... error=.../>
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
		$xml_inputPWElement = $this->xml->createElement('passwordField');
		$xml_inputPWElement->setAttribute('label', ucfirst($this->getLabel()));
		$xml_inputPWElement->setAttribute('name', $this->getName());
		$xml_inputPWElement->setAttribute('error', $this->getError()); // Gestion des erreurs
		if($this->getCssClass())
			$xml_inputPWElement->setAttribute('cssClass', $this->getCssClass()); // Classe css
		// Si le formulaire est valide, il est envoyé, on efface les champs
		if(!$valid) $xml_inputPWElement->appendChild($this->xml->createCDataSection($this->getValue()) );
		$this->parent_node->appendChild($xml_fieldElement);
		$xml_fieldElement->appendChild($xml_inputPWElement);
	}
}
 ?>