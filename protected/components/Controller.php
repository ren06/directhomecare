<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController {

    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '//layouts/column1';

    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();

    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();
    public $pageSpecialTitle;
    public $pageSubTitle;
    public $keyWords;
    public $description;

    protected function beforeRender($view) {

        if (Yii::app()->piwik->siteID != '0') {

            $return = parent::beforeRender($view);
            Yii::app()->piwik->render();
            return $return;
        } else {
            return parent::beforeRender($view);
        }
    }
    
    protected function beforeAction($action) {
        
        $lang = Yii::app()->session['lang'];
        
        if(isset($lang)){
           Yii::app()->language = $lang;
        }
        
        if( Yii::app()->request->isAjaxRequest ) {
            UIServices::unregisterAllScripts();
        }

        return parent::beforeAction($action);
  }
}

?>
