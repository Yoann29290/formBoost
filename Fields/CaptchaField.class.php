<?php
/********************************************
Auteur                : Chapalain Yoann
Site web              : http://www.happyflash.fr
Fichier               : CaptchaField.class.php
Dernière modification : En cours      	
 *******************************************/
 class CaptchaField extends Field
 {
	/** Chemin constant pour accéder à la ressource générée */
	private $const_CaptchaPath;
	/** Prefix rajouté pour accéder au dossier racine du framework */
	private $prefix_captchaPath;
	/** Hauteur de l'image générée */
	private $height; 
	/** Largeur de l'image générée */
	private $width;				 
	
	/**
	 * Constructeur de la classe captchaField.
	 * @param name				Nom du champs.
	 * @param label				Libellé du champs.
	 * @param value				Valeur permettant de renseigner le champs.
	 */
	function __construct($name, $label, $value) {
		@session_start();// - -- @ : rend muet les erreur provoquées par la fonction (surtout en cas de doublons...).
		// Particularité d'un captcha : obligatoire (sinon on en utilise pas ...) et pas de validator, intégré à sa méthode de validation...
		parent::__construct($name, $label, $value, true, null);
		// Par défaut
		$this->const_CaptchaPath = "FormBoost/ressources/img/captcha.png";
		$this->height = 32;
		$this->width = 100;
		// Génération du captcha (si pas de valeur saisie donc à l'initialisation).
		if(empty($value) || $value == null){
			$this->captchaGenerate();
		}
	}
	// -- Accesseurs & Getters
	// - Chemin de l'image générée.
	function getPrefix_captchaPath(){
		return $this->prefix_captchaPath;
	}
	function setPrefix_captchaPath($captchaPath){
		$this->prefix_captchaPath = $prefix_captchaPath;
	}
	function getCaptchaPath(){
		return $this->prefix_captchaPath . $this->const_CaptchaPath;
	}
	// - Largeur de l'image générée
	function getWidth(){
		return $this->width;
	}
	function setWidth($width){
		$this->width = $width;
	}
	// - Hauteur de l'image générée
	function getHeight(){
		return $this->height;
	}
	function setHeight($height){
		$this->height = $height;
	}
	/**
	 * validate 
	 * Fonction de validation du champs.
	 * @return vrai(true) si le champs est valide ou faux(false) si il est invalide
	 */
	function validate(){
		// - Vérification de la similitude entre le captcha généré et ce qui est saisi.
		$errorCheck = parent::validate(); // Le champs est-il rempli ?	
		if(isset($_SESSION[$this->getName().'allocateString'])){		
			if($_SESSION[$this->getName().'allocateString'] != md5($this->getValue()) && $errorCheck){
				$this->addErrorMessage("Mauvaise saisie du captcha (attention aux majuscules).");
				$this->setError("true");
				return false;
			}
		} else {
			return false;
		}
		return true;
	}	
	/**
	 * captchaGenerate 
	 * Fonction de génération de l'image captcha.
	 * Effectue les différents calculs nécessaires à l'élaboration de l'image de validation.
	 */
	function captchaGenerate(){
		// - Création de l'image.
		$captchaImg = imagecreate($this->getWidth(), $this->getHeight());
		// - Configuration du captcha.
		// Définition des couleurs.
		$backgroundColor = imagecolorallocate($captchaImg, 41, 9, 48);	// Couleur du background.
		// Longueur de la chaine de caractère.
		$stringLenght = 5;  
		// Tableau de couleur.
		$colors = array ( imagecolorallocate($captchaImg, 234,255,255),
						  imagecolorallocate($captchaImg, 204,204,204),
						  imagecolorallocate($captchaImg, 100,128,50),
						  imagecolorallocate($captchaImg, 100,123,123) 
						);
		// Typoghraphie à utiliser.
		if ( !defined('ABSPATH') ) define('ABSPATH', dirname(__FILE__) . '/');
		$font = '../ressources/fonts/arial.ttf';		
		// - Génération du background pour "perdre" les bots.
		// Trois lignes.
		$color = imagecolorallocate($captchaImg, 0, 0, 0);
		ImageLine ($captchaImg, rand(0, $this->getWidth()), rand(0, $this->getHeight()), rand(0, $this->getWidth()), rand(0, $this->getHeight()), $color);
		ImageLine ($captchaImg, rand(0, $this->getWidth()), rand(0, $this->getHeight()), rand(0, $this->getWidth()), rand(0, $this->getHeight()), $color);
		// Et deux ellipses...
		ImageEllipse ($captchaImg, rand(0, $this->getWidth()), rand(0, $this->getHeight()), rand(10, $this->getWidth()/2), rand(10, $this->getHeight()/2), $color);
		// - Génération de la chaine de caractère à afficher.
		$charSet  = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ'; // Certains caractères ont été enlevés car ils prêtent à confusion
		$randStr = null;
		for ($i=0; $i < $stringLenght; $i++) {
			$randStr .= $charSet{ mt_rand( 0, strlen($charSet)-1 ) };
		}
		// - Ajout des caractères dans l'image
		for($i=0; $i < $stringLenght; $i++){
			//$char$i = substr($randStr,0,1);
			imagettftext($captchaImg, 								// Ressource
						 $this->getHeight()/2, 						// Taille de la police
						 rand(-20,30), 								// Inclinaison
						 10 + ($i * $this->getWidth()/($stringLenght + 1) ), // Coordonnée x
						 $this->getHeight()/1.5, 							 // Coordonnée y
						 $colors[array_rand($colors)],  					 // Couleur
						 ABSPATH .'/'. $font, 								 // Typographie
						 substr($randStr, $i, 1)							 // Texte à afficher
						);
		}
		// - Creation de la session.
		$_SESSION[$this->getName().'allocateString'] = md5($randStr);
		// On détruit les variables inutiles :
		unset($i);
		unset($randStr);
		// Enregistrement de l'image dans le dossier spécifié (celui des ressources par défaut).
		imagepng($captchaImg, $this->getCaptchaPath()); 
	}
	/**
	 * toXML
	 * Fonction de génération de l'arbre XML correspondant au formulaire.
	 * L'arbre sera directement rattaché au noeud XML $parent_node
	 * L'arbre généré aura alors la forme suivante 
	 * <fieldElement label=... for=...>
	 * 	<captchaField name=... label=... error=... value=...> 
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
		$xml_inputElement = $this->xml->createElement('captchaField');
		$xml_inputElement->setAttribute('name', $this->getName());
		$xml_inputElement->setAttribute('label', ucfirst($this->getLabel()));
		$xml_inputElement->setAttribute('error', $this->getError()); // Gestion des erreurs
		if($this->getCssClass())
			$xml_inputElement->setAttribute('cssClass', $this->getCssClass()); // Classe css
		// Si le formulaire est valide, il est envoyé, on efface les champs
		 if(!$valid) $xml_inputElement->setAttribute('value', $this->getValue()); 
		// - Ajout du captcha
		$xml_inputElement->setAttribute('captchaSrc', $this->getCaptchaPath()); // Génération de l'image.	
		$this->parent_node->appendChild($xml_fieldElement);
		$xml_fieldElement->appendChild($xml_inputElement);
	}
}
 ?>