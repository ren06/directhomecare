<?php

/**
 * Description of CreditCarersCommand
 *
 * @author Renaud
 */
class CreatePaymentsAndMissionsCommand extends CConsoleCommand {

    public function run($args) {

        CronJob::createPaymentsAndMissions();
        
        echo '</br>CreatePaymentsAndMissionsCommand finished</br>';
    }

}

?>
