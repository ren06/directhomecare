<?php

/*

 * Yii extension EZClip

 * This is a class for copy text to cilpboard
 *
 * @author Gastón De Mársico
 * @version 0.1
 * @creation date: 2011-04-11
 * @filesource EZClip.php, ZeroClipboard.swf, jquery.zclip.min.js
 *
  1. You have to download and upload files to /protected/extensions/Ezclip/.

  2. Implementation: Add this code to a view.
  Eg.
  <?php echo CHtml::TextArea('$name', $value='', $htmlOptions ('id'=>'$id')) ?>

  <?php $this->widget( 'ext.EZClip.EZClip', array(
  "type" => "type of HTML object eg. input, default is "textarea",
  "idSelector" => "id of HTML object",
  ));?>
 *
 */

class EZClip extends CWidget
{
	// Id of the HTML object to be copy
	public $idSelector;
	// Type of the HTML object to be copy
	public $type = 'textarea';
	// Copy to clipboard message
	public $textLink = 'Copy to clipboard';
	// Path to flash file
	private $swfFilePath;
	// Path to js file
	private $scriptsPath;
	// Flash file name
	private $swfFile = 'ZeroClipboard.swf';
	// Array of js files to be registered
	private $jsFiles = array( "jquery.zclip.min.js" );
	// Path to js files
	private $jsPath = "js";
	// used to register jquery
	private $js;

	private function registerScripts()
	{
		$scripts = Yii::app()->clientScript;
		//Publish only one swf
		$this->scriptsPath = dirname( __FILE__ ) . '/js/';
		$this->swfFilePath = Yii::app()->getAssetManager()->publish( $this->scriptsPath . $this->swfFile );
		$this->jsPath = Yii::app()->getAssetManager()->publish( $this->scriptsPath );
		if( $this->js === null )
		{
			if( !$scripts->isScriptRegistered( 'jquery' ) )
			{
				$scripts->registerCoreScript( 'jquery' );
			}
			foreach( $this->jsFiles as $file )
			{
				Yii::app()->getAssetManager()->publish( $this->scriptsPath . $file );
				$scripts->registerScriptFile( $this->jsPath . '/' . $file, CClientScript::POS_BEGIN );
			}
		}
	}

	public function init()
	{
		$this->registerScripts();
		parent::init();
	}

	public function run()
	{
		if( isset( $this->idSelector ) )
		{
			$script = "$(document).ready(function(){ $('a#copy-" . $this->idSelector . "').zclip({message:'" . Yii::t( 'generic', 'Copied text:' ) . "',path:'" . $this->swfFilePath . "',copy:function(){return $('" . $this->type . "#" . $this->idSelector . "').val();}});});";
			// Used to get text from non input objects
			//$script = "$(document).ready(function(){ $('a#copy-description').zclip({path:'".$this->swfFilePath."',copy:$('p#code_followMe').text()});});";
			Yii::app()->clientScript->registerScript( $this->id, $script, CClientScript::POS_READY );

			if( $this->textLink === '' )
			{
				$this->textLink = Yii::t( 'generic', 'Copy to clipboard' );
			}
			echo CHtml::link( $this->textLink, 'javascript:void(0)', array( 'id' => 'copy-' . $this->idSelector ) );
		}
	}

}

?>