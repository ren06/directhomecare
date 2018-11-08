<?php

class WebUser extends CWebUser {
 
    /**
     * Overrides a Yii method that is used for roles in controllers (accessRules).
     *
     * @param string $operation Name of the operation required (here, a role).
     * @param mixed $params (opt) Parameters for this operation, usually the object to access.
     * @return bool Permission granted?
     */
    public function checkAccess($operation, $params=array()) {
        if (empty($this->id)) {
            // Not identified => no rights
            return false;
        }
        $role = $this->getState("roles");
        
        // allow access if the operation request is the current user's role
        return ($operation === $role);
    }

    public function loginRequired() {
        
        $app = Yii::app();
        $request = $app->getRequest();

        if (!$request->getIsAjaxRequest()){
         
            $returnUrl = Yii::app()->request->requestUri;
             
            $this->setReturnUrl($returnUrl);
        }

        if (($url = $this->loginUrl) !== null) {
            if (is_array($url)) {
                $route = isset($url[0]) ? $url[0] : $app->defaultController;
                $url = $app->createUrl($route, array_splice($url, 1));
                //throw new CHttpException(403, Yii::t('texts', 'FLASH_ERROR_403_YOU_ARE_NOT_AUTHORISED_TO_VIEW_THIS_PAGE'));
            }
            $request->redirect($url);
        }
        else
            throw new CHttpException(403, Yii::t('texts', 'FLASH_ERROR_403_YOU_ARE_NOT_AUTHORISED_TO_VIEW_THIS_PAGE'));
    }

}

?>