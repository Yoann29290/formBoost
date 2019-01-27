<?php
include_once('Buttons/Button.class.php');
include_once('FormException.class.php');
include_once('FormErrorManager.class.php');
include_once('FormErrorItem.class.php');
include_once('FormInformationManager.class.php');
include_once('FormInformationItem.class.php');
include_once('Form.class.php');
// - Fields
include_once('Fields/Field.class.php');
include_once('Fields/FieldSet.class.php');
include_once('Fields/TextField.class.php');
include_once('Fields/CheckboxField.class.php');
include_once('Fields/OptionField.class.php');
include_once('Fields/SelectField.class.php');
include_once('Fields/SelectMultipleField.class.php');
include_once('Fields/AreaField.class.php');
include_once('Fields/PasswordField.class.php');
include_once('Fields/PasswordSubscribeField.class.php');
include_once('Fields/CaptchaField.class.php');
include_once('Fields/UploadField.class.php');
// - Validators
include_once('Validators/Validator.class.php');
include_once('Validators/EmailValidator.class.php');
include_once('Validators/StringValidator.class.php');
include_once('Validators/CheckBoxValidator.class.php');
/********************************************
Auteur                : Chapalain Yoann
Site web              : http://www.happyflash.fr
Fichier               : Form.class.php
Dernière modification : En cours      	
 *******************************************/
 class Form
 {
	// - -- Attributs
	/** Context XML du document conteneur */
	private $xml;
	/** Noeud parent du document auquel sera rattaché le flux XML généré pour le formulaire */
	private $parent_node;
	/** Tableau de FieldSet */
	private $arrayFieldSet;
	/** Tableau des boutons situés en bas du formulaire : submit, reset, prévisualisation */
	private $arrayButton;
	/** Bouton d'envoi : présent par défaut sur tous les formulaire ... */
	private $submitButton;
	/** URL de la page/script contenant l'action à exécuter */
	private $action;
	/** Méthode de passage des argument : POST, GET, REQUEST, SCRIPT */
	private $method;
	/** Manager des erreurs du formulaire : affichage et remontée d'exceptions */
	private $errorManager;
	/** Manager des informations à afficher */
	private $informationManager;
	/** Message à afficher en cas de succès */
	private $successMessage;
	/** Indicateur pour l'affichage du message de validation/success */
	private $allowValidMsg;
	/** Indicateur de validation du formulaire */
	private $formChecked; 
	/** Placement du bloc information/error : top /right/left/bottom */
	private $errorBlockPosition;
	private $informationBlockPosition;
	/** Affichage ou non des messages d'erreurs */
	private $showError;
	/** Tableau des position valide pour le placement des blocs (liées aux possibilités CSS) */ 
	private $arrayValidPosition;
	/**
	 * Constructeur de la classe Form
	 * @param arrayFieldSet		Tableau des champs du formulaire.
	 * @param action			Script rattaché au formulaire.
	 * @param method			Méthode choisie pour la transmission des valeurs (GET || POST).
	 * 
	 */
	function __construct($arrayFieldSet, $action, $method ) {
		$this->arrayValidPosition = array("top", "right", "bottom", "left");
		if(!is_array($arrayFieldSet)) { 
			$this->arrayFieldSet = array(); 
		} else {
			$this->arrayFieldSet = $arrayFieldSet;
		}
		$this->action = $action;
		$this->method = $method;
		$this->errorManager = new FormErrorManager();
		$this->informationManager = new FormInformationManager();
		$this->arrayButton = array();
		// Bouton de validation du formulaire (par défaut, pour pas dire imposé)
                $submitButton = new Button ("submit", "Envoyer", "submit", null);
                $this->addButton($submitButton);
                // Configuration par défaut.
		$this->successMessage = "Tout s'est correctement déroulé."; 
		$this->allowValidMsg = false;		
		$this->formChecked = false;
		$this->showError(true);
		// Attention la position left l'emporte sur la droite ...
		$this->setErrorBlockPosition("right");
		$this->setInformationBlockPosition("right");
	}
	
	/**
	 * checkFields
	 * Fonction de vérification des champs (vide ou non et/ou Format)
	 * Active le niveau 'error' des champs à problème / Renseigne les message d'erreur
	 * Effectue les vérifications des validators.
	 * @return valid (boolean) : Le niveau du flag d'erreur ( true = ok / false = erreur).
	 */
	function checkFields(){
            $valid = true;
            // - Verification de tous fieldset / champs.
            foreach ($this->arrayFieldSet as $fieldSet) { 
                foreach ($fieldSet->getArrayField() as $field) { 
                    if (!$field->validate()){
                        $valid = false;
                        foreach ($field->getErrorMessages() as $msg) {
                            $this->errorManager->addError(new FormErrorItem($msg));
                        }
                    }
                }
            }	
            $this->formChecked = $valid;			
            return $valid;
	}
	/**
	 * upload
	 * Lance l'action Upload de tous les champs concernzs.
	 * @return execute (Boolean) : True, Tout s'est correctement dzroulz / False, dans le cas contraire
	 */
	function upload(){	
            $execute = true; 
            foreach ($this->arrayFieldSet as $fieldSet) { 
                foreach ($fieldSet->getArrayField() as $field) {
                    if(get_class($field) == "UploadField"){
                        if(!$field->upload()){
                            $execute = false;
                        }
                    } 
                }	
            }
            return $execute;
	}
	/**
	 * isValid
	 * Indique si l'ensemble des champs du formulaire a ete ou non valide.
	 * Effectue les verifications des validators.
	 * @return valid (boolean) : Le niveau du flag d'erreur ( true = ok / false = erreur).
	 */
	function isValid(){
            return $this->checkFields();
	}
	// - -- Ajouts dans les tableaux
	
	/**
	 * addFieldSet
	 * Fonction d'ajout d'un fielset.
	 * @param fieldSet (array) : un tableau de champs
	 * @throws FormException
	 */
	function addFieldSet($fieldSet){
            if($fieldSet != null){
                if(get_class($fieldSet) == "FieldSet"){
                    if($this->arrayFieldSet == null){
                            $this->arrayFieldSet = array();
                    }
                    array_push($this->arrayFieldSet, $fieldSet);
                } else {
                    throw new FormException("Le paramètre doit être une instance de la classe FieldSet!");
                }
            }
	}
	/**
	 * addInformation
	 * Fonction d'ajout d'information.
	 * @param information (Information) : une information
	 *
	 */
	function addInformation($information){
            if($information != null) {
                if(is_array ($information)) { 
                    $this->informationManager->addBulkInformation($information);     
                } else {
                    $this->informationManager->addInformation($information);
                }
            }
	}
     
       /**
	 * 
	 * Fonction de definition d'une image
	 * @param imgSrc (String) : url img
	 *
	 */
        function setIllustrationInformation($imgSrc) {
            $this->informationManager->setUrlImg($imgSrc);
        }
        
	/**
	 * addButton
	 * Fonction d'ajout d'un bouton.
	 * @param button (Button) : le bouton a ajouter
	 * 
	 */
	function addButton($button){
            if($button != null && is_array($this->arrayButton)) {
                    array_push($this->arrayButton, $button);
            }
	}
	
	// - -- Getters & Setters
	/**
	 * setXml
	 * Fonction de définition d'un contexte xml.
	 * @param xml.
	 * 
	 */
	function setXml(&$xml){
            $this->xml = $xml;
	}
	/**
	 * setParentNode
	 * Fonction de définition du noeud parent auquel sera rattache directement le formulaire.
	 * @param parent_node.
	 * 
	 */
	function setParentNode(&$parent_node){
            $this->parent_node = $parent_node;
	}
	/**
	 * setSuccessMessage
	 * Fonction de définition du message a afficher en cas de succes.
	 * @param String : successMessage.
	 * 
	 */
	function setSuccessMessage($successMessage){
            $this->successMessage = $successMessage;
	}
	/**
	 * getSuccessMessage
	 * Retourne le message a afficher en cas de succes.
	 * @return String : successMessage.
	 * 
	 */
	function getSuccessMessage(){
            return $this->successMessage;
	}
	// - -- Affichage et positionnement	
	/* isValidPosition
	 * Positions valides : 
	 * Cf. CSS
	 * top, right, bottom, left
	 * @param position (boolean) : position to check;
	 * @return flag (boolean) : Niveau retourne a la verification (True = OK / False = KO)
	 * 
	 */
	function isValidPosition($position){
            if(in_array($position, $this->arrayValidPosition)){
                return true;
            } else {
                return false; 
            }
	}
	/**
	 * showValidMessage
	 * Active le flag d'autorisation d'affichage du message de validation
	 */
	function showValidMessage(){
            $this->allowValidMsg = true;
	}
	/**
	 * showErrors
	 * Active le flag d'autorisation d'affichage des messages d'erreurs
	 * @param flag (boolean) : niveau du flag
	 *
	 */
	function showError($flag){
            $this->showError = $flag;
	}
	/**
	 * setErrorBlockPosition
	 * Definit la position du block de messages d'erreurs.
	 * @param position (string) : position du block
	 * @throws formException
	 */
	function setErrorBlockPosition($position){
            if($this->isValidPosition(strtolower($position))) {
                $this->errorBlockPosition = strtolower($position);			
            } else {
                throw new FormException("La position spécifiée n'est pas correcte (valide : " . implode(',', $this->arrayValidPosition) .")");
            }
	}	
	/**
	 * getErrorBlockPosition
	 * Retourne la position du block de messages d'erreurs.
	 * @return position (string) : position du block
	 *
	 */
	function getErrorBlockPosition(){
            return $this->errorBlockPosition;
	}
	/**
	 * setInformationBlockPosition
	 * Definit la position du block de messages d'informations.
	 * @param position (string) : position du block
	 * @throws formException
	 */
	function setInformationBlockPosition($position){
            if($this->isValidPosition(strtolower($position))) {
                    $this->informationBlockPosition = strtolower($position);
            } else {
                throw new FormException("La position spécifiée n'est pas correcte - (valide : " . implode(',', $this->arrayValidPosition) .")");
            }
	}
	/**
	 * getErrorBlockPosition
	 * Retourne la position du block de messages d'informations.
	 * @return position (string) : position du block
	 *
	 */
	function getInformationBlockPosition(){
            return $this->informationBlockPosition;
	}
	
	// - -- Representation / export
	/**
	 * toXML
	 * Fonction de generation de l'arbre XML correspondant au formulaire.
	 * L'arbre sera directement rattache au noeud XML $parent_node
	 * L'arbre genere aura alors la forme suivante :
	 * <formBox error_block="..." information_block="...">
         *   <form action="..." method="..." block="false">
         *    <fieldSet legend="...">
	 * 		<fieldElement name="..." >
	 * 		</fieldElement>
         *    </fieldSet>
         *  </form>
	 * </formBox>
	 * @throws FormException
	 */
	function toXML(){		
		// - -- Remonte une exception si le formulaire n'a pas de contexte xml.
		if(!isset($this->xml)){
			throw new FormException("Erreur de generation - Impossible d'associer le formulaire à un flux XML!");
		}
		if(!isset($this->parent_node)){
			throw new FormException("Erreur de generation - Impossible d'associer le formulaire à son noeud parent!");
		}
		//- -- La boite du formulaire
		$xml_formBox = $this->xml->createElement('formBox');
		$this->parent_node->appendChild($xml_formBox);
		//- -- Le formulaire
		$xml_form = $this->xml->createElement('form'); 	
		$xml_form->setAttribute('action', $this->action);
		$xml_form->setAttribute('method', $this->method);			
		$xml_formBox->appendChild($xml_form);
		// -- Les champs du formulaire	- 
		foreach ($this->arrayFieldSet as $fieldSet) { 
			$fieldSet->setXml($this->xml);
			$fieldSet->setParentNode($xml_form);
			$fieldSet->toXml($this->formChecked);
		}
		// -- Les boutons du formulaire -
		foreach ($this->arrayButton as $button) { 
			$button->setXml($this->xml);
			$button->setParentNode($xml_formBox);				
			$button->toXml(); 
		}		
		// - -- Cadre de droite : Erreurs / Informations / Validation
		$xml_formBoxes = $this->xml->createElement('formBoxes');
		$xml_formBox->appendChild($xml_formBoxes);
		// -- Informations :
		$this->informationManager->toXml($this->xml, $xml_formBoxes);		
		// -- Erreurs :
		$this->errorManager->toXml($this->xml, $xml_formBoxes);
		// -- Validation 
		if($this->allowValidMsg){		
			$xml_box_valid = $this->xml->createElement('validBox');
			$xml_box_valid_item = $this->xml->createElement('validBoxItem');
			$xml_box_valid_item->setAttribute('label', $this->getSuccessMessage());
			$xml_box_valid->appendChild($xml_box_valid_item);
			$xml_formBoxes->appendChild($xml_box_valid);
		}		
		// - -- Taille du formulaire et gestion de positionnement des blocks
		// - Positionnement du formulaire	
		if($this->isMaxWidth()){
			$xml_form->setAttribute('block', 'true'); // Pas de blocs infos ou erreurs : le formulaire prend toute la place
		}else{
			$xml_form->setAttribute('block', 'false');
		}
		// - Positionnement des autres blocks
		if($this->showError){
			$xml_formBox->setAttribute('error_block', $this->getErrorBlockPosition() );
		}
		else{
			$xml_formBox->setAttribute('error_block', 'none');
		}
		$xml_formBox->setAttribute('information_block', $this->getInformationBlockPosition() );		
	}
        
        /**
         * Retourne le flux HTML correspondant au formulaire
         * genere
         * 
         * @return String : Flux Html 
         */
        function toHTML() {
            $dom = new DOMDocument('1.0', 'UTF-8');
            $this->setXml($dom);
            $this->setParentNode($dom);
            $this->toXML();
            $proc = new XsltProcessor();
            $proc->registerPhpFunctions();
            $xsl = new DomDocument();
            $xsl->load(dirname(__FILE__).'/ressources/styles/FormBoost.xsl');
            $proc->importStylesheet($xsl);
            return $proc->transformToXML($dom);
        }
        
	/**
	 * isMaxWidth
	 * Indicateur permettant de savoir si le formulaire peut ou non prendre la place.
	 * @return indicateur (Boolean) : Indicateur
	 *
	 */
	private function isMaxWidth(){
            // Si tout se d�roule parfaitement, le formulaire prend toute la place.
            $indicateur = true; 
            // Des erreurs && ( block erreur a droite || gauche)
            if(count($this->errorManager->getArrayError()) && ($this->getErrorBlockPosition() == 'right' || $this->getErrorBlockPosition() == 'left')){
                    $indicateur = false;		
            } // Des infos && ( block infos a droite || gauche)
            elseif(count($this->informationManager->getArrayInformation()) && ($this->getInformationBlockPosition() == 'right' || $this->getInformationBlockPosition() == 'left')){
                    $indicateur = false;
            } // Une validation (block info a droite ou gauche)
            elseif($this->allowValidMsg && ($this->getInformationBlockPosition() == 'right' || $this->getInformationBlockPosition() == 'left')){
                    $indicateur = false;
            }
            return $indicateur;
	}
 } 
 ?>