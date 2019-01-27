<?php
/********************************************
Auteur                : Chapalain Yoann
Site web              : http://www.happyflash.fr
Fichier               : CaptchaField.class.php
Derni�re modification : En cours      	
 *******************************************/
 class CaptchaField extends Field
 {
	/** Chemin constant pour acc�der � la ressource g�n�r�e */
	private $const_CaptchaPath;
	/** Prefix rajout� pour acc�der au dossier racine du framework */
	private $prefix_captchaPath;
	/** Hauteur de l'image g�n�r�e */
	private $height; 
	/** Largeur de l'image g�n�r�e */
	private $width;				 
	
	/**
	 * Constructeur de la classe captchaField.
	 * @param name				Nom du champs.
	 * @param label				Libell� du champs.
	 * @param value				Valeur permettant de renseigner le champs.
	 */
	function __construct($name, $label, $value) {
		@session_start();// - -- @ : rend muet les erreur provoqu�es par la fonction (surtout en cas de doublons...).
		// Particularit� d'un captcha : obligatoire (sinon on en utilise pas ...) et pas de validator, int�gr� � sa m�thode de validation...
		parent::__construct($name, $label, $value, true, null);
		// Par d�faut
		$this->const_CaptchaPath = "FormBoost/ressources/img/captcha.png";
		$this->height = 32;
		$this->width = 100;
		// G�n�ration du captcha (si pas de valeur saisie donc � l'initialisation).
		if(empty($value) || $value == null){
			$this->captchaGenerate();
		}
	}
	// -- Accesseurs & Getters
	// - Chemin de l'image g�n�r�e.
	function getPrefix_captchaPath(){
		return $this->prefix_captchaPath;
	}
	function setPrefix_captchaPath($captchaPath){
		$this->prefix_captchaPath = $prefix_captchaPath;
	}
	function getCaptchaPath(){
		return $this->prefix_captchaPath . $this->const_CaptchaPath;
	}
	// - Largeur de l'image g�n�r�e
	function getWidth(){
		return $this->width;
	}
	function setWidth($width){
		$this->width = $width;
	}
	// - Hauteur de l'image g�n�r�e
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
		// - V�rification de la similitude entre le captcha g�n�r� et ce qui est saisi.
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
	 * Fonction de g�n�ration de l'image captcha.
	 * Effectue les diff�rents calculs n�cessaires � l'�laboration de l'image de validation.
	 */
	function captchaGenerate(){
		// - Cr�ation de l'image.
		$captchaImg = imagecreate($this->getWidth(), $this->getHeight());
		// - Configuration du captcha.
		// D�finition des couleurs.
		$backgroundColor = imagecolorallocate($captchaImg, 41, 9, 48);	// Couleur du background.
		// Longueur de la chaine de caract�re.
		$stringLenght = 5;  
		// Tableau de couleur.
		$colors = array ( imagecolorallocate($captchaImg, 234,255,255),
						  imagecolorallocate($captchaImg, 204,204,204),
						  imagecolorallocate($captchaImg, 100,128,50),
						  imagecolorallocate($captchaImg, 100,123,123) 
						);
		// Typoghraphie � utiliser.
		if ( !defined('ABSPATH') ) define('ABSPATH', dirname(__FILE__) . '/');
		$font = '../ressources/fonts/arial.ttf';		
		// - G�n�ration du background pour "perdre" les bots.
		// Trois lignes.
		$color = imagecolorallocate($captchaImg, 0, 0, 0);
		ImageLine ($captchaImg, rand(0, $this->getWidth()), rand(0, $this->getHeight()), rand(0, $this->getWidth()), rand(0, $this->getHeight()), $color);
		ImageLine ($captchaImg, rand(0, $this->getWidth()), rand(0, $this->getHeight()), rand(0, $this->getWidth()), rand(0, $this->getHeight()), $color);
		// Et deux ellipses...
		ImageEllipse ($captchaImg, rand(0, $this->getWidth()), rand(0, $this->getHeight()), rand(10, $this->getWidth()/2), rand(10, $this->getHeight()/2), $color);
		// - G�n�ration de la chaine de caract�re � afficher.
		$charSet  = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ'; // Certains caract�res ont �t� enlev�s car ils pr�tent � confusion
		$randStr = null;
		for ($i=0; $i < $stringLenght; $i++) {
			$randStr .= $charSet{ mt_rand( 0, strlen($charSet)-1 ) };
		}
		// - Ajout des caract�res dans l'image
		for($i=0; $i < $stringLenght; $i++){
			//$char$i = substr($randStr,0,1);
			imagettftext($captchaImg, 								// Ressource
						 $this->getHeight()/2, 						// Taille de la police
						 rand(-20,30), 								// Inclinaison
						 10 + ($i * $this->getWidth()/($stringLenght + 1) ), // Coordonn�e x
						 $this->getHeight()/1.5, 							 // Coordonn�e y
						 $colors[array_rand($colors)],  					 // Couleur
						 ABSPATH .'/'. $font, 								 // Typographie
						 substr($randStr, $i, 1)							 // Texte � afficher
						);
		}
		// - Creation de la session.
		$_SESSION[$this->getName().'allocateString'] = md5($randStr);
		// On d�truit les variables inutiles :
		unset($i);
		unset($randStr);
		// Enregistrement de l'image dans le dossier sp�cifi� (celui des ressources par d�faut).
		imagepng($captchaImg, $this->getCaptchaPath()); 
	}
	/**
	 * toXML
	 * Fonction de g�n�ration de l'arbre XML correspondant au formulaire.
	 * L'arbre sera directement rattach� au noeud XML $parent_node
	 * L'arbre g�n�r� aura alors la forme suivante 
	 * <fieldElement label=... for=...>
	 * 	<captchaField name=... label=... error=... value=...> 
	 * </fieldElement>
	 * @param Bool : Flag d'indication de la prise en compte de la validation.
	 * @throws FormException
	 */
	function toXML($valid = false){
		// - -- Remonte une exception si le formulaire n'a pas de contexte xml.
		if(!isset($this->xml)){
			throw new FormException("Erreur de g�n�ration - Impossible d'associer le formulaire � un flux XML!");
		}
		if(!isset($this->parent_node)){
			throw new FormException("Erreur de g�n�ration - Impossible d'associer le formulaire � son noeud parent!");
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
		// Si le formulaire est valide, il est envoy�, on efface les champs
		 if(!$valid) $xml_inputElement->setAttribute('value', $this->getValue()); 
		// - Ajout du captcha
		$xml_inputElement->setAttribute('captchaSrc', $this->getCaptchaPath()); // G�n�ration de l'image.	
		$this->parent_node->appendChild($xml_fieldElement);
		$xml_fieldElement->appendChild($xml_inputElement);
	}
}
 ?>