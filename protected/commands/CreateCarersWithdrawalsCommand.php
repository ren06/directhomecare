<?php

/**
 * Description of CreditCarersCommand
 *
 * @author Renaud
 */
class CreateCarersWithdrawalsCommand extends CConsoleCommand {

    public function run($args) {

        CronJob::createCarersWithdrawals();
        
        echo '</br>CreateCarersWithdrawalsCommand finished</br>';
    }

}

?>
