<?php

class DialogConfig {

    public $text;
    public $buttonsHTML;
    public $title;
    public $width;
    public $height;

    public function __construct($title, $text, $buttonsHTML, $width=500, $height = 170) {

        $this->text = $text;
        $this->buttonsHTML = $buttonsHTML;
        $this->title = $title;
        $this->height = $height;
        $this->width = $width;
    }

}

?>
