<?php

/**
 * Description of CreditCarersCommand
 *
 * @author Renaud
 */
class CreditCarersCommand extends CConsoleCommand {

    public function run($args) {

        $rawData = CronJob::creditCarers();

        echo '<p>Taken <p><p>';

        foreach ($rawData as $raw) {

            echo 'Id: ' . $raw['id'] . ' Dates:' . $raw['dates'] . ' Last Mission dates' . $raw['lastMissionDates'] . ' Next slot: ' . $raw['nextSlot'];
        }

        echo '</br>CreditCarersCommand finished</br>';
    }

}

?>
