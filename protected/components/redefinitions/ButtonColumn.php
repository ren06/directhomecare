<?php

class ButtonColumn extends CButtonColumn {

    /**
     * @var boolean whether the ID in the button options should be evaluated.
     */
    public $evaluateID = false;

    /**
     * Renders the button cell content.
     * This method renders the view, update and delete buttons in the data cell.
     * Overrides the method 'renderDataCellContent()' of the class CButtonColumn
     * @param integer $row the row number (zero-based)
     * @param mixed $data the data associated with the row
     */
    public function renderDataCellContent($row, $data) {
        $tr = array();
        ob_start();
        foreach ($this->buttons as $id => $button) {
            if ($this->evaluateID and isset($button['options']['id'])) {
                $button['options']['id'] = $this->evaluateExpression($button['options']['id'], array('row' => $row, 'data' => $data));
            }

            $this->renderButton($id, $button, $row, $data);
            $tr['{' . $id . '}'] = ob_get_contents();
            ob_clean();
        }
        ob_end_clean();
        echo strtr($this->template, $tr);
    }

    protected function renderButton($id, $button, $row, $data) {

        if (empty($button['url'])) {

            if (isset($button['visible']) && !$this->evaluateExpression($button['visible'], array('row' => $row, 'data' => $data))) {
                return;
            }

            if (isset($button['options'])) {

                $class = '';
                if (isset($button['options']['class'])) {
                    $value = $button['options']['class'];
                    $class = "class='$value'";
                }

                $tooltip = '';
                if (isset($button['options']['tooltip'])) {
                    $value = $button['options']['tooltip'];
                    $tooltip = "tooltip='$value'";
                }

                echo "<div $class $tooltip>" . $button['label'] . '</div>';
            } else {

                echo $button['label'];
            }
        } else {
            parent::renderButton($id, $button, $row, $data);
        }
    }

}

?>
