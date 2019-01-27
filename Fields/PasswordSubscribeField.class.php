<?php
/********************************************
Auteur                : Chapalain Yoann
Site web              : http://www.happyflash.fr
Fichier               : PasswordSubscribeField.class.php
Dernière modification : En cours      	
 *******************************************/
 class PasswordSubscribeField extends Field
 {
	// - -- Attributs
	/** Nom du champs de confirmation */
	private $nameConfirmed;
	/** Contenu / Valeur du champs de confirmation */
	private $valueConfirmed;
	/** Indicateur pour la position des champs : true, les champs sont en ligne, false, ils se superposent */
	private $inline;
	
	/**
	 * Constructeur de la classe PasswordSubscribeField.
	 * Classe permettant la création de deux input de type password. 
	 * UseCase : Champs password pour formulaire d'inscription (avec vérification par validation de deux champs)
	 * @param name				Nom du premier champs.
	 * @param nameConfirmed		Nom du champs de confirmation.
	 * @param label				Libellé du premier champs.
	 * @param value				Valeur permettant de renseigner le premier champs.
	 * @param valueConfirmed	Valeur permettant de renseigner le second champs.
	 * @param required			Indique si le champs doit être remplis.
	 * @param validator			Permet de connaitre le type de vérification à effectuer (ex: email, captcha, password...).
	 * @param inline			Permet d'indiquer si les deux champs doivent être aligné ou non (false par défaut).
	 */
	function __construct($name, $nameConfirmed, $label, $value=null, $valueConfirmed=null, $required, $validator=null, $inline='true') {
		parent::__construct($name, $label, $value, $required, $validator);
		$this->valueConfirmed = $valueConfirmed;
		$this->nameConfirmed = $nameConfirmed;
		$this->inline = $inline;
	}
	/**
	 * validate 
	 * Fonction de validation du champs.
	 * Effectue les vérifications des validators.
	 * @return vrai(true) si le champs est valide ou faux(false) si il est invalide
	 */
	function validate(){
		// - -- Appel de la méthode parent. (impératif)
		$errorCheck = parent::validate();
		// Vérification spécifique au champs.
		if($this->value != $this->valueConfirmed){
			$this->setError("true");
			$this->addErrorMessage("Les deux mots de passe doivent correspondre.");
			$errorCheck = false;
		}
		return $errorCheck;
	}
	/**
	 * toXML
	 * Fonction de génération de l'arbre XML correspondant au formulaire.
	 * L'arbre sera directement rattaché au noeud XML $parent_node
	 * L'arbre généré aura alors la forme suivante 
	 * <fieldElement>
	 * 	<passwordField name=... nameConf=... label=... labelConf=... inline=... error=... />
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
		// - Password
		$xml_inputPWConfElement = $this->xml->createElement('passwordSubscribeField');
		$xml_inputPWConfElement->setAttribute('name', $this->getName());
		$xml_inputPWConfElement->setAttribute('label', ucfirst($this->getLabel()));
		$xml_inputPWConfElement->setAttribute('labelConf', ucfirst($this->nameConfirmed));
		$xml_inputPWConfElement->setAttribute('nameConf', $this->nameConfirmed);
		$xml_inputPWConfElement->setAttribute('error', $this->getError());
		$xml_inputPWConfElement->setAttribute('inline', $this->getInline());
		if($this->getCssClass())
			$xml_inputPWConfElement->setAttribute('cssClass', $this->getCssClass()); // Classe css
		// Si le formulaire est valide, il est envoyé, on efface les champs
		if(!$valid) $xml_inputPWConfElement->setAttribute('value', $this->getValue()); 
		// Si le formulaire est valide, il est envoyé, on efface les champs
		if(!$valid) $xml_inputPWConfElement->setAttribute('valueConf', $this->valueConfirmed); 
		$this->parent_node->appendChild($xml_fieldElement);
		$xml_fieldElement->appendChild($xml_inputPWConfElement);
	}
	/**
	  * getInline
	  * Fonction permettant de connaitre l'état du flag inline.
	  * Vrai : Les deux champs sont en ligne / False : ils sont l'un au dessus de l'autre
	  * @return bool : inline
	  */
	function getInline(){
		return $this->inline;
	}
} 
 ?>