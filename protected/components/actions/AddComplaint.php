<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AddComplaint
 *
 * @author Renaud
 */
class AddComplaint extends CAction {

    const NAME = 'addComplaint';

    public function run() {

        if (Yii::app()->request->isAjaxRequest) {

            UIServices::unregisterAllScripts();

            $missionId = $_POST['id_mission'];
            $user = $_POST['user'];
            $text = $_POST['ComplaintPost']['text'];

            $author = Yii::app()->user->roles;

            if ($author == Constants::USER_CLIENT) {
                Mission::authorizeClient($missionId);
            } else {
                Mission::authorizeCarer($missionId);
            }

            $mission = Mission::loadModel($missionId);


            $newPost = Complaint::addPost($missionId, $user, $author, $author, $text);
            $complaintId = $newPost->id_complaint;

            //Emails::sendToAdmin_NewComplaint();

            $complaint = Complaint::loadModel($complaintId);

            $output = $this->getController()->renderPartial('/complaint/_complaint', array('mission' => $mission, 'complaint' => $complaint, 'user' => $user)
                    , true, true);

            echo $output;
        }
    }

}

?>
