<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  <xsl:output method="xml" version="1.0" encoding="UTF-8" indent="yes" 
			  doctype-public="-//W3C//DTD XHTML 1.0 Strict//FR" 
			  doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"/>

	<!-- Root -->
	<xsl:template match="/">
             <xsl:apply-templates select="formBox" />
        </xsl:template>
	<!-- =========== -->
	<!-- Formulaires -->
	<!-- =========== -->
	<xsl:template match="formBox">
            <!-- Position : top -->
            <xsl:if test="@information_block = 'top' or @error_block = 'top'">
                <div id="form_top">
                    <xsl:if test="@information_block='top'">
                        <xsl:apply-templates select="formBoxes/informationBox" />
                        <xsl:apply-templates select="formBoxes/validBox" />
                    </xsl:if>
                    <xsl:if test="@error_block='top'">
                        <xsl:apply-templates select="formBoxes/errorBox" />
                    </xsl:if>
                </div>
            </xsl:if>
            <div id="form_wrapper">
                <!-- Affichage du formulaire -->
                <xsl:apply-templates select="form" />

                <!-- Ajouts des boites (gauche / droite sinon) -->
                <div id="form_boxes_right" class="float-right box_form">
                    <xsl:if test="@information_block='right'">
                        <xsl:apply-templates select="formBoxes/informationBox" />
                        <xsl:apply-templates select="formBoxes/validBox" />
                    </xsl:if>
                    <xsl:if test="@error_block='right'">
                        <xsl:apply-templates select="formBoxes/errorBox" />
                    </xsl:if>
                </div>
                <div id="form_boxes_left" class="float-left box_form">
                    <xsl:if test="@information_block='left'">
                        <xsl:apply-templates select="formBoxes/informationBox" />
                        <xsl:apply-templates select="formBoxes/validBox" />
                    </xsl:if>
                    <xsl:if test="@error_block='left'">
                        <xsl:apply-templates select="formBoxes/errorBox" />
                    </xsl:if>
                </div>
                <div class="clear"></div>
            </div>
            <!-- Position : bottom -->
            <xsl:if test="@information_block = 'bottom' or @error_block = 'bottom'">
                <div id="form_top">
                    <xsl:if test="@information_block='bottom'">
                        <xsl:apply-templates select="formBoxes/informationBox" />
                        <xsl:apply-templates select="formBoxes/validBox" />
                    </xsl:if>
                    <xsl:if test="@error_block='bottom'">
                        <xsl:apply-templates select="formBoxes/errorBox" />
                    </xsl:if>
                </div>
            </xsl:if>
	</xsl:template>
	<!-- Formulaire -->
	<xsl:template match="form">            
            <div id="formulaire" >
                    <!-- Si il n'y a aucunes informations à afficher (infos / erreur / validation) -->
                    <!-- Le formulaire peut donc occuper toute la place. -->
                    <xsl:if test="@block='false'">
                            <xsl:attribute name="class">float-left</xsl:attribute>
                    </xsl:if>	
                    <xsl:if test="parent::node()/@information_block = 'left' or parent::node()/@error_block = 'left'">
                            <xsl:attribute name="class">float-right</xsl:attribute>			
                    </xsl:if>
                    <form>
                    <xsl:if test="@method != 'SCRIPT'">
                        <xsl:attribute name="action"><xsl:value-of select="@action"/></xsl:attribute>
                    </xsl:if>
                    <xsl:attribute name="method"><xsl:value-of select="@method"/></xsl:attribute>	
                    <xsl:attribute name="enctype">multipart/form-data</xsl:attribute>	
                        <!-- Pour chaque fieldset du formulaire -->
                        <xsl:for-each select="fieldSet">
                            <fieldset>
                                <xsl:attribute name="class"><xsl:value-of select="@cssClass"/></xsl:attribute>						
                                <legend align='top'><xsl:value-of select="@legend"/></legend>  
                                <!-- Pour chaque champs de ce fieldset -->
                                <xsl:for-each select="fieldElement">
                                    <p>		                                        
										<label>
											<xsl:attribute name="for"><xsl:value-of select="@for"/></xsl:attribute>						
											<xsl:value-of select="@label"/>								
											<!-- Si le champs est requis, on l'indique -->
											<xsl:if test="@required='true'">
												<span style="color:#CF0000; font-weight:bold" title="Required"> (*) </span>
											</xsl:if>
										</label>
                                        <!-- text Field -->
                                        <xsl:if test="textField">
                                            <xsl:apply-templates select="textField" />
                                        </xsl:if>							
                                        <!-- passwordField -->
                                        <xsl:if test="passwordField">
                                            <xsl:apply-templates select="passwordField" />							
                                        </xsl:if>
                                        <!-- passwordSubscribeField -->
                                        <xsl:if test="passwordSubscribeField">
                                            <xsl:apply-templates select="passwordSubscribeField" />							
                                        </xsl:if>
                                        <!-- captchaField -->
                                        <xsl:if test="captchaField">
                                            <xsl:apply-templates select="captchaField" />															
                                        </xsl:if>
                                        <!-- upload Field -->
                                        <xsl:if test="uploadField">
                                            <xsl:apply-templates select="uploadField" />
                                        </xsl:if>	
                                        <!-- areaField -->
                                        <xsl:if test="areaField">
                                            <xsl:apply-templates select="areaField" />							
                                        </xsl:if>
                                        <!-- selectField -->
                                        <xsl:if test="selectField">
                                            <xsl:apply-templates select="selectField" />															
                                        </xsl:if>
                                        <!-- checkboxField -->
                                        <xsl:if test="checkboxField">
                                            <xsl:apply-templates select="checkboxField" />															
                                        </xsl:if>
                                        <!-- optionField -->										
                                        <xsl:if test="optionField">
                                            <xsl:apply-templates select="optionField" />											
                                        </xsl:if>
                                    </p>
                                </xsl:for-each>
                            </fieldset>
                        </xsl:for-each>
                        <!-- Boutons -->
                        <p class="buttonGroup">
                            <xsl:for-each select="parent::node()/button">
                                    <input>
                                            <xsl:attribute name="name"><xsl:value-of select="@name" /></xsl:attribute>
                                            <xsl:if test="parent::node()/form/@method != 'SCRIPT'">
                                                    <xsl:attribute name="type"><xsl:value-of select="@type" /></xsl:attribute>
                                            </xsl:if>
                                            <xsl:if test="parent::node()/form/@method = 'SCRIPT'">
                                                <xsl:attribute name="type">button</xsl:attribute>
                                                    <xsl:attribute name="onClick"><xsl:value-of select="parent::node()/form/@action" /></xsl:attribute>
                                            </xsl:if>                                                                
                                            <xsl:attribute name="class">button</xsl:attribute>													
                                            <xsl:if test="@cssClass!=''">
                                                    <xsl:attribute name="class"><xsl:value-of select="@cssClass" /></xsl:attribute>													
                                            </xsl:if>								
                                            <xsl:attribute name="value"><xsl:value-of select="@label" /></xsl:attribute>
                                    </input>
                            </xsl:for-each>
                        </p>
                    </form>			
            </div>
	</xsl:template>	
	<!-- ================================ -->
	<!-- Champs et éléments de formulaire -->
	<!-- ================================ -->
	<!-- Text Field -->
	<xsl:template match="textField">	
		<input>
			<xsl:attribute name="id"><xsl:value-of select="@name" /></xsl:attribute>
			<xsl:attribute name="type">text</xsl:attribute>
			<xsl:attribute name="name"><xsl:value-of select="@name" /></xsl:attribute>
			<xsl:attribute name="value"><xsl:value-of select="@value" /></xsl:attribute>
			<xsl:if test="@cssClass">
				<xsl:attribute name="class"><xsl:value-of select="@cssClass" /></xsl:attribute>
			</xsl:if>
			<xsl:if test="@error='true'">
				<xsl:attribute name="class">formError</xsl:attribute>
			</xsl:if>
		</input>		
	</xsl:template>	
	<!-- TextArea Field -->
	<xsl:template match="areaField">	
		<textarea>
			<xsl:attribute name="id"><xsl:value-of select="@name" /></xsl:attribute>
			<xsl:attribute name="name"><xsl:value-of select="@name" /></xsl:attribute>
			<xsl:attribute name="cols"><xsl:value-of select="@cols" /></xsl:attribute>
			<xsl:attribute name="rows"><xsl:value-of select="@rows" /></xsl:attribute>
			<xsl:if test="@cssClass">
				<xsl:attribute name="class"><xsl:value-of select="@cssClass" /></xsl:attribute>
			</xsl:if>
			<xsl:if test="@error='true'">
				<xsl:attribute name="class">formError</xsl:attribute>
			</xsl:if>
			<xsl:value-of select="."/>
		</textarea>
	</xsl:template>	
	<!-- Password Field -->
	<xsl:template match="passwordField">
		<input>
			<xsl:attribute name="id"><xsl:value-of select="@name" /></xsl:attribute>
			<xsl:attribute name="type">password</xsl:attribute>
			<xsl:attribute name="name"><xsl:value-of select="@name" /></xsl:attribute>
			<xsl:attribute name="value"><xsl:value-of select="@value" /></xsl:attribute>
			<xsl:if test="@cssClass">
				<xsl:attribute name="class"><xsl:value-of select="@cssClass" /></xsl:attribute>
			</xsl:if>
			<xsl:if test="@error='true'">
				<xsl:attribute name="class">formError</xsl:attribute>
			</xsl:if>
		</input>
	</xsl:template>	
	<!-- passwordSubscribe Field -->
	<xsl:template match="passwordSubscribeField">	
		<input>
			<xsl:attribute name="id"><xsl:value-of select="@name" /></xsl:attribute>
			<xsl:attribute name="type">password</xsl:attribute>
			<xsl:attribute name="name"><xsl:value-of select="@name" /></xsl:attribute>
			<xsl:attribute name="value"><xsl:value-of select="@value" /></xsl:attribute>
			<xsl:if test="@cssClass">
				<xsl:attribute name="class"><xsl:value-of select="@cssClass" /></xsl:attribute>
			</xsl:if>
			<xsl:if test="@error='true'">
				<xsl:attribute name="class">formError</xsl:attribute>
			</xsl:if>
		</input>
		<!-- Cas exceptionnel pour le placement d'un label : le inline . (seul endroit pour lui indiquer ou le mettre) -->
		<xsl:if test="@inline='false'">
			<label>
				<xsl:attribute name="for"><xsl:value-of select="@nameConf" /></xsl:attribute>						
				<xsl:value-of select="@labelConf" /> :
			</label>
		</xsl:if>
		<input>
			<xsl:attribute name="id"><xsl:value-of select="@name" /></xsl:attribute>
			<xsl:attribute name="type">password</xsl:attribute>
			<xsl:attribute name="name"><xsl:value-of select="@nameConf" /></xsl:attribute>
			<xsl:attribute name="value"><xsl:value-of select="@valueConf" /></xsl:attribute>
			<xsl:if test="@cssClass">
				<xsl:attribute name="class"><xsl:value-of select="@cssClass" /></xsl:attribute>
			</xsl:if>
			<xsl:if test="@error='true'">
				<xsl:attribute name="class">formError</xsl:attribute>
			</xsl:if>
		</input>
	</xsl:template>
	<!-- upload Field -->
	<xsl:template match="uploadField">	
		<input>
			<xsl:attribute name="type">hidden</xsl:attribute>
			<xsl:attribute name="name">MAX_FILE_SIZE</xsl:attribute>
			<xsl:attribute name="value"><xsl:value-of select="@maxFileSize" /></xsl:attribute>
		</input>
		<input>
			<xsl:attribute name="id"><xsl:value-of select="@name" /></xsl:attribute>
			<xsl:attribute name="type">file</xsl:attribute>
			<xsl:attribute name="name"><xsl:value-of select="@name" /></xsl:attribute>
			<xsl:attribute name="value"><xsl:value-of select="@value" /></xsl:attribute>
			<xsl:if test="@cssClass">
				<xsl:attribute name="class"><xsl:value-of select="@cssClass" /></xsl:attribute>
			</xsl:if>
			<xsl:if test="@error='true'">
				<xsl:attribute name="class">formError</xsl:attribute>
			</xsl:if>
		</input>
	</xsl:template>
	<!-- Select Field -->
	<xsl:template match="selectField">	
		<!--
		<select [multiple="multiple"]>
			<option>OPTION 1 </option>
			<option>OPTION 2 </option>
		</select> 
		-->
		<select>
			<xsl:attribute name="id"><xsl:value-of select="@name" /></xsl:attribute>
			<xsl:attribute name="name"><xsl:value-of select="@name" /></xsl:attribute>
			<xsl:if test="@multiple='true'">
				<xsl:attribute name="multiple">multiple</xsl:attribute>
			</xsl:if>			
			<xsl:if test="@cssClass">
				<xsl:attribute name="class"><xsl:value-of select="@cssClass" /></xsl:attribute>
			</xsl:if>
			<xsl:if test="@error='true'">
				<xsl:attribute name="class">formError</xsl:attribute>
			</xsl:if>
			<xsl:for-each select="option">
				<option>
					<xsl:if test="@selected='true'">
						<xsl:attribute name="selected">true</xsl:attribute>
					</xsl:if>
					<xsl:value-of select="." />
				</option>
			</xsl:for-each>
		</select>		
	</xsl:template>	
	<!-- Radio Field -->
	<xsl:template match="optionField">		
		<label><xsl:value-of select="@label" /></label>
		<br />
		<xsl:for-each select="row">			
				<xsl:for-each select="option">
					<label style="display:inline">
						<xsl:attribute name="for"><xsl:value-of select="parent::node()/parent::node()/@name" />_<xsl:value-of select="." /></xsl:attribute>
					<input> 
						<xsl:attribute name="type">radio</xsl:attribute>
						<!-- On remonte de deux; 1 première fois pour le <row> et l'autre pour accéder au bon noeud -->
						<xsl:attribute name="name"><xsl:value-of select="parent::node()/parent::node()/@name" /></xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="." /></xsl:attribute>
						<xsl:attribute name="id"><xsl:value-of select="parent::node()/parent::node()/@name" />_<xsl:value-of select="." /></xsl:attribute>
						<xsl:if test="@checked='true'">
							<xsl:attribute name="checked">true</xsl:attribute>
						</xsl:if>
						<xsl:if test="@cssClass">
							<xsl:attribute name="class"><xsl:value-of select="@cssClass" /></xsl:attribute>
						</xsl:if>
						<xsl:if test="@error='true'">
							<xsl:attribute name="class">formError</xsl:attribute>
						</xsl:if>
					</input> 
						<xsl:value-of select="." />
					</label>
				</xsl:for-each>
	
		</xsl:for-each>
	</xsl:template>	
	<!-- CheckBox Field -->
	<xsl:template match="checkboxField">
	<fieldset>
		<xsl:attribute name="class"><xsl:value-of select="@cssFieldSet" /></xsl:attribute>
		<legend><xsl:value-of select="@label" /></legend>
		<xsl:for-each select="row">
			<p>
			<xsl:for-each select="checkbox">
				<label style="display:inline">
					<xsl:attribute name="for">chk_<xsl:value-of select="." /></xsl:attribute>
				<input> 
					<xsl:attribute name="type">checkbox</xsl:attribute>
					<!-- On remonte de deux; 1 première fois pour le <row> et l'autre pour accéder au bon noeud -->
					<xsl:attribute name="name"><xsl:value-of select="parent::node()/parent::node()/@name" /></xsl:attribute> 
					<xsl:attribute name="value"><xsl:value-of select="." /></xsl:attribute>
					<xsl:attribute name="id">chk_<xsl:value-of select="." /></xsl:attribute>
					<xsl:if test="@checked='true'">
						<xsl:attribute name="checked">true</xsl:attribute>
					</xsl:if>
					<xsl:if test="@cssClass">
						<xsl:attribute name="class"><xsl:value-of select="@cssClass" /></xsl:attribute>
					</xsl:if>
					<xsl:if test="@error='true'">
						<xsl:attribute name="class">formError</xsl:attribute>
					</xsl:if>
				</input> 
					<xsl:value-of select="." />
				</label>
			</xsl:for-each>
			</p>
		</xsl:for-each>
	</fieldset>
	</xsl:template>	
	<!-- Captcha Field -->
	<xsl:template match="captchaField">
		<input>
			<xsl:attribute name="id"><xsl:value-of select="@name" /></xsl:attribute>
			<xsl:attribute name="type">text</xsl:attribute>
			<xsl:attribute name="name"><xsl:value-of select="@name" /></xsl:attribute>
			<xsl:attribute name="value"><xsl:value-of select="@value" /></xsl:attribute>
			<xsl:if test="@cssClass">
				<xsl:attribute name="class"><xsl:value-of select="@cssClass" /></xsl:attribute>
			</xsl:if>
			<xsl:if test="@error='true'">
				<xsl:attribute name="class">formError</xsl:attribute>
			</xsl:if>
		</input>		
		<!-- Mise en place du captcha -->
		<img>
			<xsl:attribute name="src" title="Captcha" alt="captcha">
				<xsl:value-of select="@captchaSrc" /></xsl:attribute>
		</img>
		<!-- javascript ?? -->
	</xsl:template>	
	<!-- Upload Field -->	
	<!-- ============================== -->
	<!-- Partie Informations / Erreurs -->
	<!-- ============================== -->
	<!-- Block d'informations -->
	<xsl:template match="formBoxes/informationBox">
            <!-- Affichage du bloc ssi il y'a des informations -->
            <xsl:if test="informationBoxItem">                
                <div id="form_infos">		
                    <div id="formInformationBoxImage">
                        <img>
                            <xsl:attribute name="src" title="Information">
                                <xsl:value-of select="imgSrc" />
                            </xsl:attribute> 
                        </img>
                    </div>
                    <div id="formInformationBoxItems">
                        <ul>
                            <xsl:for-each select="informationBoxItem">
                                <li>
                                    <!--
                                    <img>
                                        <xsl:attribute name="src"><xsl:value-of select="@icon"/></xsl:attribute>
                                        <xsl:attribute name="alt"><xsl:value-of select="@label"/></xsl:attribute>
                                    </img>
                                    -->
                                    <xsl:value-of select="@label" />
                                    </li>
                            </xsl:for-each>
                        </ul>
                    </div>
                    <div style="clear:both;" />
                </div>			
            </xsl:if>
	</xsl:template>
	<!-- Block des messages d'erreurs -->	
	<xsl:template match="formBoxes/errorBox">
            <!-- Affichage du bloc ssi il y'a des erreurs -->
            <xsl:if test="errorBoxItem">
                <div id="form_right_error" class="box_error">
                    <ul>				
                        <xsl:for-each select="errorBoxItem">
                            <li><xsl:value-of select="@label" /></li>
                        </xsl:for-each>
                    </ul>
                </div>
            </xsl:if>
	</xsl:template>	
	<!-- Block des messages de validation -->	
	<xsl:template match="formBoxes/validBox">
            <!-- Affichage du bloc ssi il y'a des validations -->
            <xsl:if test="validBoxItem">
                <div id="form_right_valid" class="box_valid">
                    <ul>				
                        <xsl:for-each select="validBoxItem">
                            <li><xsl:value-of select="@label" /></li>
                        </xsl:for-each>
                    </ul>
                </div>
            </xsl:if>
	</xsl:template>
        <xsl:template match="select">
            <xsl:copy-of select="."/>
        </xsl:template>
</xsl:stylesheet>