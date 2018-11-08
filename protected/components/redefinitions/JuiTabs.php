<?php

/**
 * Redefinition of CJuiTabs for better handling of tooltips
 *
 * @author Renaud
 */
class JuiTabs extends CJuiTabs {
  
    	public function run()
	{
		$id=$this->getId();
		if(isset($this->htmlOptions['id']))
			$id=$this->htmlOptions['id'];
		else
			$this->htmlOptions['id']=$id;
                
		echo CHtml::openTag($this->tagName,$this->htmlOptions)."\n";

		$tabsOut="";
		$contentOut="";
		$tabCount=0;

		foreach($this->tabs as $title=>$content)
		{
			$tabId=(is_array($content) && isset($content['id']))?$content['id']:$id.'_tab_'.$tabCount++;

                        $tooltip = (isset($content['tooltip'])? $content['tooltip']: '');
                        
			if(!is_array($content))
			{
				$tabsOut.=strtr($this->headerTemplate,array('{tooltip}' => $tooltip, '{title}'=>$title,'{url}'=>'#'.$tabId,'{id}'=>'#'.$tabId))."\n";
				$contentOut.=strtr($this->contentTemplate,array('{content}'=>$content,'{id}'=>$tabId))."\n";
			}
			elseif(isset($content['ajax']))
			{
				$tabsOut.=strtr($this->headerTemplate,array('{tooltip}' => $tooltip, '{title}'=>$title,'{url}'=>CHtml::normalizeUrl($content['ajax']),'{id}'=>'#'.$tabId))."\n";
			}
			else
			{
				$tabsOut.=strtr($this->headerTemplate,array('{tooltip}' => $tooltip, '{title}'=>$title,'{url}'=>'#'.$tabId,'{id}'=>$tabId))."\n";
				if(isset($content['content']))
					$contentOut.=strtr($this->contentTemplate,array('{content}'=>$content['content'],'{id}'=>$tabId))."\n";
			}
		}
		echo "<ul>\n".$tabsOut."</ul>\n";
		echo $contentOut;
		echo CHtml::closeTag($this->tagName)."\n";

		$options=CJavaScript::encode($this->options);
		Yii::app()->getClientScript()->registerScript(__CLASS__.'#'.$id,"jQuery('#{$id}').tabs($options);");
	}

}

?>
