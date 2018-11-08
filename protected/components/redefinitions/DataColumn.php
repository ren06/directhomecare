<?php

/**
 * DataColumn class file.
 * Extends {@link CDataColumn}
 */
class DataColumn extends CDataColumn {

    /**
     * @var boolean whether the htmlOptions values should be evaluated. 
     */
    public $evaluateHtmlOptions = false;

    /**
     * Renders a data cell.
     * @param integer $row the row number (zero-based)
     * Overrides the method 'renderDataCell()' of the abstract class CGridColumn
     */
    public function renderDataCell($row) {
        $data = $this->grid->dataProvider->data[$row];

        $options = $this->evaluateExpression($this->htmlOptions, array('data' => $data, 'row' => $row));


        echo CHtml::openTag('td', $options);
        $this->renderDataCellContent($row, $data);
        echo '</td>';
    }


}

?>
