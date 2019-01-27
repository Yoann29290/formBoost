<?php
/********************************************
Auteur                : Chapalain Yoann
Site web              : http://www.happyflash.fr
Fichier               : Field.class.php
Dernière modification : En cours      	
 *******************************************/
 abstract class Field
 {
	// - -- Attributs
	/** Nom (html) du champs */
	protected  $name;
	/** Label, désignation (html) du champs */
	protected  $label;
	/** Valeur du champs : ce qui est saisi, coché, sélectionné ... */
	protected  $value;
	/** Legende accompagnant le champs : doit permettre le but du champs, aider à la bonne saisie ... */
	protected $toolTip;
	/** Indicateur pour permettre de savoir si le champs est requis. */
	private  $required;
	/** Validateur du champs */
	private  $validator;
	/** Style CSS appliqué au champs */
	protected  $cssClass;
	/** Flag d'erreur du champs : lorsqu'il est levé, il est permet la mise en forme css correspondante */
	private  $error;
	/** Ensemble des messages d'erreurs remontés */
	protected  $errorMessages;
	
	// a virer => les faires passer en param de la methodes toXML ...(ne concerne qu'elle)
	/** Context XML parent */
	protected  $xml;
	/** Noeud parent auquel sera rattaché l'arbre XML généré du champs */
	protected  $parent_node;
	
	/**
	 * Constructeur de la classe field.
	 * @param name				Nom du champs.
	 * @param label				Label du champs.
	 * @param value				Valeur permettant de renseigner le champs.
	 * @param required			Indique si le champs doit être remplis.
	 * @param validator			Permet de connaitre le type de vérification à effectuer (ex: email, captcha, password...).
	 * 
	 */
	function __construct($name, $label, $value=null, $required, $validator) {
		$this->name = $name;
		if($label != null) $this->label = $label;
		else $this->label = $name;
		$this->value = $value;
		$this->required = $required;
		$this->validator = $validator;
		if(isset($this->validator)){
			$this->validator->setFieldLibelle($label);
		}
		$this->error = "false";
		$this->errorMessages = array();
	}
	/**
	 * validate 
	 * Fonction de validation du champs.
	 * Effectue les vérifications des validators.
	 * @return vrai(true) si le champs est valide ou faux(false) si il est invalide
	 */
	function validate(){
		$errorCheck = true;
		// - -- Si le champs est requis, il ne peut pas être vide.
		if(( ($this->getValue() == null ) && $this->getRequired())){
			$this->setError("true");	// Flag erreur pour le champs.
			array_push($this->errorMessages, "'" . $this->getLabel()."' est requis.");
			$errorCheck = false;
		}
		// - -- Vérification à l'aide du validator du champs.
		elseif($this->getValidator()){				
			if(!$this->getValidator()->validate($this->getValue())){
				$this->setError("true");	// Flag erreur pour le champs.
				array_push($this->errorMessages, $this->getValidator()->getErrorMessage());
				$errorCheck = false;
			}
		}
		return $errorCheck;
	}
	/**
	 * toXML
	 * Fonction de génération de l'arbre XML correspondant au formulaire.
	 * L'arbre sera directement rattaché au noeud XML $parent_node
	 * L'arbre généré aura alors la forme suivante 
	 * <fieldElement name=... label=... error=... >
	 * 	<fieldName>
	 *		propriétés ...
	 *	</fieldName>
	 * </fieldElement>
	 * @param valid : false pas de validation, on affiche les valeurs / true, on affiche pas les valeurs
	 * 
	 */
	abstract function toXML($valid=false);	
	/**
	 * toHTML
	 * Fonction de génération du code HTML correspondant au formulaire.
	 * @param valid : false pas de validation, on affiche les valeurs / true, on affiche pas les valeurs
	 */
	//abstract function toHTML($valid=false);
	/*
	 * - -- Accesseurs.
	 */
	function getType(){
		return $this->type;
	}
	function getName(){
		return $this->name;
	}
	function getLabel(){
		return $this->label;
	}
	function getValue(){
		return $this->value;
	}	
	function getToolTip(){
		return $this->toolTip;
	}	
	function getCssClass(){
		return $this->cssClass;
	}
	function getRequired(){
		return $this->required;
	}
	function getValidator(){
		return $this->validator;
	}
	function getError(){
		return $this->error;
	}
	function getErrorMessages(){
		return $this->errorMessages;
	}
	function getXml(){
		return $this->xml;
	}
	function getParentNode(){
		return $this->parent_node;
	}
	/*
	 * - -- Setters.
	 */
	function setType($type){
		$this->type = $type;
	}
	function setName($name){
		$this->name = $name;
	}
	function setLabel($label){
		$this->label = $label;
	}
	function setValue($value){
		$this->value = $value;
	}
	function setToolTip($toolTip){
		$this->toolTip = $toolTip;
	}	
	function setCssClass($cssClass){
		$this->cssClass = $cssClass;
	}
	function setRequired($required){
		$this->required = $required;
	}
	function setValidator($validator){
		$this->validator = $validator;
	}
	function setError($error){
		$this->error = $error;
	}
	function setXml($xml){
		$this->xml = $xml;
	}
	function setParentNode($pn){
		$this->parent_node = $pn;
	}
	function addErrorMessage($msg){
		array_push($this->errorMessages, $msg);
	}
 } 
 ?>