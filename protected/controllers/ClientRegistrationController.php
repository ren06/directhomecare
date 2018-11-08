<?php
/**
 *
 * Use to redirect any old controller/action to the homepage
 * 
 */

class ClientRegistrationController extends CController {

    public function init() {
        parent::init();

        Yii::app()->attachEventHandler('onError', array($this, 'handleError'));
        Yii::app()->attachEventHandler('onException', array($this, 'handleError'));
    }

    public function handleError(CEvent $event) {
        if ($event instanceof CExceptionEvent) {
            // handle exception
             $this->redirect(array('/site/index'));
        } elseif ($event instanceof CErrorEvent) {
            // handle error
             $this->redirect(array('/site/index'));
        }

        $event->handled = TRUE;
    }

}

?>
