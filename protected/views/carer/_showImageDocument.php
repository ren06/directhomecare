<?php
$responseScriptTemplate = "js:function(id, fileName, responseJSON){ 
                         
                $.ajax({
                        url: '" . CHtml::normalizeUrl(array("carer/displayImage")) . "',
                        type: 'post',
                        data: {
                            documentId: responseJSON['documentId'], 
                            entity: responseJSON['entity'], 
                            type: responseJSON['type']
                            },     
                        success: function(data){
                                $('#ENTITY_main').html(data);
                            },                            
                        });
                        
                    }";

if (isset($carerDocument)) {
    echo $carerDocument->showImage();
    echo '&#160;&#160;' . $carerDocument->displayDocumentStatusWithStyle();
    echo '&#160;&#160;' . CHtml::button(Yii::t('texts', 'BUTTON_DELETE'), array('class' => 'button small', 'id' => 'document_remove_' . $entity));
    if ($carerDocument->reject_reason != '') {
        echo '<p class="rc-error">' . $carerDocument->reject_reason . '</p>';
    }
    ?>
    <div id="<?php echo $entity; ?>_uploadButton" style="display:none">
        <?php
    } else {
        ?>
        <div id="<?php echo $entity; ?>_uploadButton">
            <?php
        }
        switch ($type) {
            case Document::TYPE_PHOTO;
                $buttonText = Yii::t('texts', 'BUTTON_UPLOAD_PHOTO');
                break;
            case Document::TYPE_DRIVING_LICENCE;
                $buttonText = Yii::t('texts', 'BUTTON_UPLOAD_DRIVING_LICENCE');
                break;
            case Document::TYPE_IDENTIFICATION;
                $buttonText = Yii::t('texts', 'BUTTON_UPLOAD_ID');
                break;
        }
        $responseScript = str_replace('ENTITY', $entity, $responseScriptTemplate);
        $this->widget('application.extensions.EAjaxUpload.EAjaxUpload', array(
            'id' => $entity . 'uploadWidget' . uniqid(),
            'config' => array(
                'action' => Yii::app()->createUrl('carer/uploadImage/type/' . $type),
                'allowedExtensions' => array("jpg", "jpeg", "png", "gif"),
                'sizeLimit' => 10 * 1024 * 1024, // maximum file size in bytes
                'minSizeLimit' => 0.001 * 1024 * 1024, // minimum file size in bytes
                'buttonText' => $buttonText,
                'onComplete' => $responseScript,
            )
        ));
        ?>
    </div>
    <?php
    if (isset($carerDocument)) {
        //($carerDocument->isActive() ? $active = 1 : $active = 0 );
        $active = 0;
        ?>
        <script type="text/javascript">
            $("#document_remove_<?php echo $entity ?>").click(function() {

                if (confirm('<?php echo Yii::t('texts', 'NOTE_ARE_YOU_SURE_YOU_WANT_TO_DELETE_THIS_DOCUMENT'); ?>')) {
                    $.ajax({success: function(html) {
                            $("#<?php echo $entity ?>_main").html(html);
                        }, type: "post", url: "<?php echo $this->createUrl("documentDelete") ?>", data: {type: <?php echo $type ?>, active: <?php echo $active ?>}, cache: false, dataType: "html"});
                }
            });
        </script>
        <?php
    }
    ?>