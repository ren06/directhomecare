<style>
    textarea{
        width:400px;
        height:300px;
        border:1px solid #000;
        
    }
</style>

<h3>Convert Field Names</h2>

<div style="margin-left:45px; width:100%; margin-top: 20px">
<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

echo CHtml::beginForm();
echo CHtml::textArea('originalFieldNames', $originalFieldNames, array('class' => 'textarea'));
echo '<br>';
echo CHtml::submitButton();
echo CHtml::endForm();
?>

<h3>Result</h3>

<?php
foreach ($fieldsNames as $fieldName) {

    echo $fieldName . '<br>';
}
?>

</div>
