<style>
    textarea{
        width:2200px;
        height:300px;
        border:1px solid #000000;
        
    }
</style>

<h3>Create DTD Table</h3>

<div style="margin-left:45px; width:100%; margin-top: 20px">
<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

echo CHtml::beginForm();
echo '<br>';
echo CHtml::textArea('sql', $sql, array('class' => 'textarea'));
echo '<br>';
echo CHtml::submitButton();
echo CHtml::endForm();
?>


<?php
echo CHtml::textArea('result', $result, array('class' => 'textarea'));
?>

</div>
