<?php

/**
 * Description of ButtonConfig
 *
 * @author I031360
 */
class ButtonConfig {

    public $label;
    public $action;

    public function __construct($label, $action) {

        $this->label = $label;
        $this->action = $action;

    }
}

?>
