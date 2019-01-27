<?php
/********************************************
Auteur                : Chapalain Yoann
Site web              : http://www.happyflash.fr
Fichier               : UploadField.class.php
Dernière modification : En cours      	
 *******************************************/
 class UploadField extends Field
 {
	/** Taille maximum autorisé pour l'upload */
	private $maxFileSize;
	/** Tableau des extensions autorisées pour l'upload */
	private $validExtensionArray;
	/** Répertoire de destination du fichier uploadé. (En partant du niveau ou se trouve le FrameWork)
	  * Si celui-ci n'existe pas, il est créé.
	  */
	private $fileDestination;
	/** Suffixe rajouté au nom de fichier uploadé */
	private $suffixe;
	/** Préfixe rajouté au nom de fichier uploadé */
	private $prefixe;	
	/** Miniature ? */
	private $isThumbed;	
	/** Miniature ? */
	private $validExtensionArrayForThumb;
	
	/**
	 * Constructeur de la classe uploadField.
	 * @param name					Nom du champs.
	 * @param label					Libellé du champs.
	 * @param value					Valeur permettant de renseigner le champs.
	 * @param required				Indique si le champs doit être remplis.
	 * @param maxFileSize			Taille de maximum du fichier à télécharger.
	 * @param validExtensionArray	Tableau des extension autorisée.
	 * @param fileDestination		Dossier de destination du fichier.
	 * @param isThumbed		        Doit-on creer une miniature.
	 */
	function __construct($name, $label, $value=null, $required, $maxFileSize, $validExtensionArray, $fileDestination, $isThumbed) {
		parent::__construct($name, $label, $value, $required, null);
		// - Configuration de l'upload
		if(!isset($maxFileSize)){
			$this->maxFileSize = 3000000; //3Mo		
		}else{
			$this->maxFileSize = $maxFileSize;		
		}
		if(!isset($validExtensionArray)){		
			$this->validExtensionArray = array();
		}else{
			$this->validExtensionArray = $validExtensionArray;
		}		
		if(!isset($fileDestination)){		
			$this->fileDestination = 'upload/';
		}else{		
			$this->fileDestination = $fileDestination;
		}
		$this->isThumbed = $isThumbed;
		
		$this->prefixe = "";
		$this->suffixe = "";
		$this->validExtensionArrayForThumb = array('.png', '.gif', '.jpg', '.jpeg');
	}
	/**
	 * setSuffixe
	 * Définit un suffixe pour le nom du fichier uploadé.
	 * @param (String) : suffixe	 
	 */
	function setSuffixe($suffixe){
		$this->suffixe = $suffixe;
	}
	/**
	 * setPrefixe
	 * Définit un prefixe pour le nom du fichier uploadé.
	 * @param (String) : prefixe	 
	 */
	function setPrefixe($prefixe){
		$this->prefixe = $prefixe;
	}
	/**
	 * validate 
	 * Fonction de validation du champs.
	 * @return vrai(true) si le champs est valide ou faux(false) si il est invalide
	 */
	function validate(){
		// - Informations de description du fichier.
		$extension = strtolower(strrchr($_FILES[$this->getName()]['name'], '.')); 
		$fileSize = filesize($_FILES[$this->getName()]['tmp_name']);
		$file = basename($_FILES[$this->getName()]['name']);		
		$valid = parent::validate();
		// Extension
		if(!in_array($extension, $this->validExtensionArray)){
			$this->setError("true");	// Flag erreur pour le champs.
			$this->addErrorMessage("Extension fournit invalide. (valide : " . implode(',', $this->validExtensionArray) .")");
			$valid = false;
		}
		// Coherence extension - miniature
		if($this->isThumbed && !in_array($extension, $this->validExtensionArrayForThumb)) {
			$this->setError("true");	// Flag erreur pour le champs.
			$this->addErrorMessage("Extension fournit invalide pour la création d'une miniature. (valide : " . implode(',', $this->validExtensionArrayForThumb) .")");
			$valid = false;
		}
		// Taille		
		elseif($fileSize > $this->maxFileSize){
			$this->setError("true");	// Flag erreur pour le champs.
			$this->addErrorMessage("Fichier trop volumineux. <br />Taille du fichier : " .$fileSize. " - MAX autorise : " .$this->maxFileSize);
			$valid = false;
		}
		return $valid;
	}
	/**
	 * upload
	 * Fonction d'upload du fichier.
	 * @return valid(boolean) : retourne vrai si tout s'est bien passé, et faux dans le cas contraire.
	 */
	function upload(){
		// - Informations de description du fichier.
		$extension = strrchr($_FILES[$this->getName()]['name'], '.'); 
		//echo "Extension : ".$extension . "<br>";
		//print_r( $_FILES[$this->getName()] );
		$fileSize = filesize($_FILES[$this->getName()]['tmp_name']);
		//echo "<br>Taille : ".$fileSize . "<br> / " . $this->maxFileSize;
		$file = basename($_FILES[$this->getName()]['name']);	
		if(!empty($this->prefixe)) $file = $this->prefixe . $file;		
		if(!empty($this->suffixe)) {
			$file = strstr($file, '.', true); // attention aux noms composés ex test.toto.jpg => test.jpg :(
			$file = $file . $this->suffixe . $extension;
		}
		//echo "<br>Fichier : ".$file . "<br>";
		// - Vérification 
		$valid = $this->validate();		
		// - Pas d'erreur : upload.		
		if($valid) 
		{
			// - Formatage du fichier, on remplace les caractères spéciaux.
			$file = strtr($file, 
			'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 
			'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
			$file = preg_replace('/([^.a-z0-9]+)/i', '-', $file);
			//echo "<br>Nom lisse : ". $file . "<br>";
			// - Préparation du dossier de destination : s'il n'existe pas, on le créé.
			if(!file_exists($this->fileDestination)){
				if(!mkdir($this->fileDestination , 0755)){ // 0777 un peu trop...
					$this->setError("true");	// Flag erreur pour le champs.
					$this->addErrorMessage("Erreur lors de la création du répertoire ". $this->fileDestination.".");
					$valid = false;
				}
			}
		// VIDE => Erreur
			// - Upload
			if(!move_uploaded_file($_FILES[$this->getName()]['tmp_name'], $this->fileDestination . $file)) 
			{
				$this->setError("true");
				$this->addErrorMessage("L'upload a echoué : " . $this->transcodeUploadErrorMessage($_FILES[$this->getName()]['error']));
				$valid = false;
			}		
		}
		// - Creation de la miniature
		if($valid == true && $this->isThumbed == true) {
			$this->createThumbs($this->fileDestination, $this->fileDestination."thumbs/", 100, $extension, $file);
		}		
		return $valid;
	}	
	
	private function transcodeUploadErrorMessage($code)
    {
        switch ($code) {
            case UPLOAD_ERR_INI_SIZE:
                $message = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
                break;
            case UPLOAD_ERR_PARTIAL:
                $message = "The uploaded file was only partially uploaded";
                break;
            case UPLOAD_ERR_NO_FILE:
                $message = "No file was uploaded";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $message = "Missing a temporary folder";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $message = "Failed to write file to disk";
                break;
            case UPLOAD_ERR_EXTENSION:
                $message = "File upload stopped by extension";
                break;

            default:
                $message = "Unknown upload error";
                break;
        }
        return $message;
    } 
	
	function createThumbs( $pathToImages, $pathToThumbs, $thumbWidth, $fileExtension, $fileName ){
		
		if ( strtolower($fileExtension) == '.jpg' ) {
		
			$img = imagecreatefromjpeg($pathToImages . $fileName);
			$width = imagesx( $img );
			$height = imagesy( $img );

			// calculate thumbnail size
			$new_width = $thumbWidth;
			$new_height = floor( $height * ( $thumbWidth / $width ) );

			// create a new temporary image
			$tmp_img = imagecreatetruecolor( $new_width, $new_height );

			// copy and resize old image into new image
			imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );

			// save thumbnail into a file			
			return imagejpeg( $tmp_img, "{$pathToThumbs}{$fileName}" );
			
		} else if ( strtolower($fileExtension) == '.png' ) {
			$img = imagecreatefrompng( "{$pathToImages}{$fileName}" );
			$width = imagesx( $img );
			$height = imagesy( $img );
			
			$new_width = $thumbWidth;
			$new_height = floor( $height * ( $thumbWidth / $width ) );
			
			$tmp_img = imagecreatetruecolor( $new_width, $new_height );
			
			imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );
			
			return imagepng( $tmp_img, "{$pathToThumbs}{$fileName}" );
			
		} else if ( strtolower($fileExtension) == '.gif' ) {
		
			$img = imagecreatefromgif( "{$pathToImages}{$fileName}" );			
			$width = imagesx( $img );
			$height = imagesy( $img );
			
			$new_width = $thumbWidth;
			$new_height = floor( $height * ( $thumbWidth / $width ) );
			
			$tmp_img = imagecreatetruecolor( $new_width, $new_height );
			
			imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );
			return imagegif( $tmp_img, "{$pathToThumbs}{$fileName}" );
		} else {
			echo "Erreur : extension non prise en compte !";
			return false;
		}
	}
	
	
	/**
	 * toXML
	 * Fonction de génération de l'arbre XML correspondant au formulaire.
	 * L'arbre sera directement rattaché au noeud XML $parent_node
	 * L'arbre généré aura alors la forme suivante 
	 * <fieldElement>
	 * 	<uploadField name=... label=... error=... value=...>
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
		$xml_inputElement = $this->xml->createElement('uploadField');
		$xml_inputElement->setAttribute('name', $this->getName());
		$xml_inputElement->setAttribute('label', ucfirst($this->getLabel()));
		$xml_inputElement->setAttribute('maxFileSize', $this->maxFileSize);
		$xml_inputElement->setAttribute('error', $this->getError()); // Gestion des erreurs
		if($this->getCssClass())
			$xml_inputElement->setAttribute('cssClass', $this->getCssClass()); // Classe css 
		$this->parent_node->appendChild($xml_fieldElement);
		$xml_fieldElement->appendChild($xml_inputElement);
	}
}
 ?>