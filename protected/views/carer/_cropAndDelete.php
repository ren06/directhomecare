<?php
echo '<div id="crop">';
echo $photo->showCrop(120, 96);

echo '&#160;&#160;' . $photo->displayDocumentStatusWithStyle();
echo '&#160;&#160;' . CHtml::button(Yii::t('texts', 'BUTTON_DELETE'), array('class' => 'button small', 'id' => 'document_remove_photo'));

echo '</div>';

//$uniqueId = uniqid();
//    echo CHtml::ajaxButton(Yii::t('texts', 'BUTTON_DELETE'), $this->createUrl('documentDelete'), array(
//        'success' => 'function(html) {$("#photo_main").html(html); }',
//        'data' => array('type' => Document::TYPE_PHOTO, 'active' => 0),
//        'type' => 'GET',
//            ), array('class' => 'rc-button-small', 'name' => 'document_remove_photo_' . $uniqueId, 'id' => 'id_document_remove_photo_' . $uniqueId )
//    );

if(Yii::app()->user->wizard_completed == Wizard::CARER_LAST_STEP_INDEX){
    
    $redirectUrl = $this->createUrl('carer/maintainProfile');
}
else{
    $redirectUrl = $this->createUrl('carer/profile');
}

?>

<script type="text/javascript">
    $("#document_remove_photo").click(function() {

        if (confirm('<?php echo Yii::t('texts', 'NOTE_ARE_YOU_SURE_YOU_WANT_TO_DELETE_THIS_DOCUMENT'); ?>')) {
            
            var personalText = $('#Personal_text').val();
            var motivationText = $('#Motivation_text').val();
        
            $.ajax({
                success: function(html) {
                    //$("#photo_main").html(html);
                    window.location.href = '<?php echo $redirectUrl ?>';
                },
//                beforeSend: function(xhr, opts) {
//                    confirm("Are you sure?");
//                },
                type: "post",
                url: "<?php echo $this->createUrl("documentDelete") ?>",
                data: {type: <?php echo Document::TYPE_PHOTO ?>, active: <?php echo 0 ?>, personal_text: personalText, motivation_text: motivationText},
                cache: false,
                dataType: "html"
            }
            );
        }
    });
</script>
