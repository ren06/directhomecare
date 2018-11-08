<div class="rc-upload-container-dropdown" id="document_<?php echo $index ?>">
    <?php
    if ($carerDocument->documentType->type == Document::TYPE_DIPLOMA) {

        $divName = 'Diploma';
        $divId = 'diploma';
        $documents = Document::getDocumentList(Document::TYPE_DIPLOMA);
    } else {

        $divName = 'CriminalRecord';
        $divId = 'criminalRecord';
        $documents = Document::getDocumentList(Document::TYPE_CRIMINAL);
    }
    ?>

    <div class="row">
        <div class="columns large-3 medium-6 small-12">
            <?php
            echo CHtml::activeDropDownList($carerDocument, "[$index]id_document", $documents, array('class' => 'rc-drop-disabled', 'disabled' => 'disabled'));
            ?>
        </div>
        <div class="columns large-3 medium-6 small-12">
            <?php
            echo CHtml::activeDropDownList($carerDocument, "[$index]year_obtained", array($carerDocument->year_obtained), array('class' => 'rc-drop-disabled', 'disabled' => 'disabled'));
            ?>
        </div>
        <div class="columns large-3 medium-6 small-12">
            <?php
            $uniqueId = uniqid();
            echo "[<span id='$uniqueId' class='rc-link-toolbox qTipTooltipImage' style='color:#808080;'>" . Yii::t('texts', 'TOOLTIP_DOCUMENT') . '</span>]';
            echo $carerDocument->showImage($height = "148", $width = "288", false, '');
            echo ' '. ' ' . $carerDocument->displayDocumentStatusWithStyle();
            ?>
        </div>
        <div class="columns large-3 medium-6 small-12">
            <?php
            echo CHtml::button(Yii::t('texts', 'BUTTON_DELETE'), array('class' => 'button small', 'id' => 'document_remove' . $index));
            ?>
        </div>
    </div>
    <?php
    if (isset($carerDocument->reject_reason)) {
        echo '<p class="rc-error">' . $carerDocument->reject_reason . '</p>';
    }
    ?>
    <script type="text/javascript">
        $("#document_remove<?php echo $index ?>").click(function() {
            if (confirm('<?php echo Yii::t('texts', 'NOTE_ARE_YOU_SURE_YOU_WANT_TO_DELETE_THIS_DOCUMENT'); ?>')) {
                $.ajax({
                    success: function(html) {
                        $("#document_<?php echo $index ?>").remove();

                        var count = $("#diplomas .rc-upload-container-dropdown").length;

                        if (count > 4) {
                            $('#add_diploma_button').hide();
                            $('#add_diploma_message').show();
                        }
                        else {
                            $('#add_diploma_button').show();
                            $('#add_diploma_message').hide();
                        }

                    },
                    type: 'get',
                    url: '<?php echo $this->createUrl('diplomaCRBDelete') ?>',
                    data: {
                        index: <?php echo $index ?>
                    },
                    cache: false,
                    dataType: 'html'
                });
            }
        });

        $(document).ready(function() {
            var content = $('#<?php echo $uniqueId ?>').next();
            //alert(content);

            $('#<?php echo $uniqueId ?>').qtip({
                // Simply use an HTML img tag within the HTML string
                content: content, //'<img width="288" height="" alt="Photo" src="http://localhost:8888/directhomecare/carer/getImage/fileContent/16/doc/18" class="rc-image-profile">',
                style: {
                    classes: 'qtip-light',
                    // width: 300,
                    // height: 200
                },
                hide: {
                    delay: 100,
                    fixed: false
                },
                // size: { x: 300, y: 290}
            });
        });
    </script>        
</div>