<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AddComplaintDialog
 *
 * @author Renaud
 */
class AddComplaintDialog extends CAction {

      const NAME = 'addComplaintDialog';

    public function run() {

        if (Yii::app()->request->isAjaxRequest) {

            $missionId = $_POST['missionId'];
            $user = $_POST['user'];

            UIServices::unregisterAllScripts();

            $output = $this->getController()->renderPartial('/complaint/_createComplaintDialog', array('missionId' => $missionId, 'user' => $user), true, true);

            echo $output;
        }
    }

}

?>
