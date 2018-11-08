<?php

/**
 * Runs the newsletter for carers and clients
 *
 * @author Renaud
 */
class ProcessNewslettersCommand extends CConsoleCommand {

    public function run($args) {

        if (in_array('carer', $args)) {

            echo 'Launching carer newsletter';

            CronJob::processCarerNewsletter($this);
        }

        if (in_array('client', $args)) {

            echo 'Launching client newsletter';

            CronJob::processClientNewsletter($this);
        }

        echo 'Finished';
    }

    public function createAbsoluteUrl($path) { //string
        return Yii::app()->params['server']['fullUrl'] . '/' . $path;
    }

}

?>
