<?php

/**
 * Description of ProcessEmailsCommand
 *
 * @author Renaud
 */

class ProcessEmailsCommand extends CConsoleCommand {

    public function run($args) {

        CronJob::processEmails();
        
        echo '</br>ProcessEmailsCommand finished</br>';
    }

}
?>
