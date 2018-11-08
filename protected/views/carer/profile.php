<style type="text/css">
    #myinformation {
        <?php $this->renderPartial('/layouts/_menuButtonSelectedCss'); ?>
    }
</style>

<div style="display:none">
</div>

<?php
$this->pageTitle = Yii::t('texts', 'HEADER_PROFILE');
$this->pageSubTitle = 'Smile !';
?>

<?php
$form = $this->beginWidget('CActiveForm', array('id' => 'carer_profile-form',
    'enableAjaxValidation' => false,
        ));
?>

<div class="row">
    <div class="columns large-12 medium-12 small-12">
<?php
if ($maintain == true) {
    $this->renderPartial('_myInformationCarerMenu', array('selectedMenu' => 'Profile'));
} else {
    echo Wizard::generateWizard();
}
?>
    </div>
</div>
<div class="row">
    <div class="columns large-6 medium-8 small-12">
<?php
if (Yii::app()->user->hasFlash('notice')):
    echo '<div class="flash-notice">' . Yii::app()->user->getFlash('notice') . '</div>';
endif;
if (Yii::app()->user->hasFlash('success')):
    echo '<div class="flash-success">' . Yii::app()->user->getFlash('success') . '</div>';
endif;
if (Yii::app()->user->hasFlash('error')):
    echo '<div class="flash-error">' . Yii::app()->user->getFlash('error') . '</div>';
endif;
?>

        <h3><?php echo Yii::t('texts', 'NOTE_WHY_ARE_YOU_INTERESTED_IN_THIS_JOB'); ?></h3>

        <?php
        if ($maintain == true) {
            ?>
            <div class="rc-module-container-button">
            <?php
            echo CHtml::button(Yii::t('texts', 'BUTTON_EDIT'), array('class' => 'button small', 'id' => 'editMotivationText'));
            echo CHtml::button(Yii::t('texts', 'BUTTON_SAVE'), array('class' => 'button small', 'id' => 'saveMotivationText', 'style' => 'display:none'));
            echo CHtml::button(Yii::t('texts', 'BUTTON_CANCEL'), array('class' => 'button small', 'id' => 'cancelMotivationText', 'style' => 'display:none'));
            ?>
            </div>
                <?php
            }
            ?>
        <?php
        $textObject = $carer->getMotivationText();
        if (isset($textObject)) {
            $text = $textObject->text;
        } else {
            $text = '';
        }
        echo CHtml::textArea('motivation_text', $text, array('id' => 'Motivation_text', 'maxlength' => 150, 'class' => 'rc-textarea-profile'));
        ?>
        <div id="Motivation_text_status">
        <?php
        if (isset($textObject)) {
            echo $textObject->displayDocumentStatusWithStyle();
            if ($textObject->status == CarerDocument::STATUS_REJECTED) {
                echo '<p class="rc-error">' . $textObject->reject_reason . '</p>';
            }
            echo '<br><br>';
        }
        ?>
        </div>
        <h3><?php echo Yii::t('texts', 'NOTE_HOW_WOULD_YOU_DESCRIBE_YOUR_PERSO'); ?></h3>
            <?php
            if ($maintain == true) {
                ?>
            <div class="rc-module-container-button">
            <?php
            echo CHtml::button(Yii::t('texts', 'BUTTON_EDIT'), array('class' => 'button small', 'id' => 'editPersonalText'));
            echo CHtml::button(Yii::t('texts', 'BUTTON_SAVE'), array('class' => 'button small', 'id' => 'savePersonalText', 'style' => 'display:none'));
            echo CHtml::button(Yii::t('texts', 'BUTTON_CANCEL'), array('class' => 'button small', 'id' => 'cancelPersonalText', 'style' => 'display:none'));
            ?>
            </div>
                <?php
            }
            ?>
        <?php
        $textObject = $carer->getPersonalityText();
        if (isset($textObject)) {
            $text = $textObject->text;
        } else {
            $text = '';
        }
        echo CHtml::textArea('personal_text', $text, array('id' => 'Personal_text', 'maxlength' => 150, 'class' => 'rc-textarea-profile'));
        ?>
        <div id="Personal_text_status">
        <?php
        if (isset($textObject)) {
            echo $textObject->displayDocumentStatusWithStyle();
            if ($textObject->status == CarerDocument::STATUS_REJECTED) {
                echo '<p class="rc-error">' . $textObject->reject_reason . '</p>';
            }
            echo '<br><br>';
        }
        ?>
        </div>
        <!--  LANGUAGES  -->
        <h3><?php echo Yii::t('texts', 'HEADER_LANGUAGES'); ?></h3>
            <?php
            if ($maintain) {
                echo CHtml::submitButton(Yii::t('texts', 'BUTTON_SAVE_CHANGES'), array('class' => 'button small', 'submit' => array('carer/maintainProfile', 'navigation' => 'save')));
            }
            ?> 
        <p class="rc-note">
        <?php echo Yii::t('texts', 'NOTE_LANGUAGES'); ?>
        </p>
        <div id="languages" >
            <?php
            $this->renderPartial('_languages', array('carerLanguages' => $carerLanguages));
            ?>
        </div>
        <br>
        <!--  MY PICTURE  -->
        <h3><?php echo Yii::t('texts', 'HEADER_MY_PHOTO'); ?></h3>
        <p class="rc-note">
<?php echo Yii::t('texts', 'NOTE_PLEASE_UPLOAD_A_PHOTO'); ?>
        </p>
<?php $entity = FileContent::getDocumentTypeFolder(Document::TYPE_PHOTO); ?>
        <div id="<?php echo $entity ?>_main">
            <?php
            echo $this->renderPartial('_showPhoto', array('carer' => $carer), true, false);
            ?>
        </div>
        <br>
        <!-- SOCIAL BUTTONS -->
        <h3><?php echo Yii::t('texts', 'HEADER_SOCIAL_MEDIA'); ?></h3>
        <p class="rc-note">
<?php echo Yii::t('texts', 'NOTE_IMPROVE_YOUR_RATING_BY_FOLLOWING_DIRECT_HOMECARE'); ?>
        </p>
<?php include Yii::app()->basePath . '/views/site/pages/_socialButtons.php'; ?>

            <?php if ($maintain == true) { ?>
            <br>
            <!--  DRIVING LICENSE  -->
            <h3><?php echo Yii::t('texts', 'HEADER_MY_IDENTIFICATION') . ' / ' . Yii::t('texts', 'HEADER_MY_DRIVING'); ?></h3>
            <p class="rc-note">
    <?php echo Yii::t('texts', 'NOTE_PLEASE_UPLOAD_A_SCAN_OF_ID'); ?>
            </p>
    <?php $entity = FileContent::getDocumentTypeFolder(Document::TYPE_IDENTIFICATION); ?>
            <div id="<?php echo $entity ?>_main">
                <?php
                $carerIdentification = $carer->getIdentification();
                echo $this->renderPartial('_showImageDocument', array('carerDocument' => $carerIdentification, 'entity' => $entity, 'type' => Document::TYPE_IDENTIFICATION), true, false);
                ?>
            </div>
                <?php $entity = FileContent::getDocumentTypeFolder(Document::TYPE_DRIVING_LICENCE); ?>
            <!-- do you own a car -->
            <br>
            <p>
            <?php
            if ($carer->car_owner == 0) {
                $carer->car_owner = 1;
            }
            echo Yii::t('texts', 'LABEL_DO_YOU_OWN_A_CAR');
            ?><?php
                echo Yii::app()->params['radioButtonSpaceafter'];
                echo $form->radioButtonList($carer, 'car_owner', array(2 => Yii::t('texts', 'LABEL_YES'), 1 => Yii::t('texts', 'LABEL_NO')), array('class' => '', 'separator' => Yii::app()->params['radioButtonSeparator']));
                echo $form->error($carer, 'car_owner');
                ?>
            </p>

                <?php
            }
            if ($maintain == false) {
                ?>
            <div class="row">
                <div class="buttons">
                    <div class="columns large-6 medium-6 small-12">
            <?php echo CHtml::submitButton(Yii::t('texts', 'BUTTON_BACK'), array('class' => 'button expand', 'submit' => array('carer/profile', 'navigation' => 'back'))); ?>
                    </div>
                    <div class="columns large-6 medium-6 small-12">
                        <?php echo CHtml::submitButton(Yii::t('texts', 'BUTTON_FINISH'), array('class' => 'button expand alert', 'submit' => array('carer/profile', 'navigation' => 'next'))); ?>
                    </div>
                </div>
                        <?php $this->renderPartial('/common/_ajaxLoader'); ?>
            </div>
    <?php
}
?>
    </div>
    <div class="columns large-6 medium-8 small-12">
        <?php
        if ($maintain == true) {
            $this->renderPartial('/site/_carerProfileConversation', array('carer' => $carer, 'showButton' => false, 'isNewsletter' => false, 'viewer' => Constants::USER_CARER));
        }
        ?>
    </div>
</div>
<div class="row">
    <div class="columns large-12 medium-12 small-12">
<?php if ($maintain == true) { ?>
            <!-- CRIMINAL RECORDS -->
            <br>
            <h3><?php echo Yii::t('texts', 'HEADER_MY_CRB'); ?></h3>
            <p class="rc-note">
    <?php echo Yii::t('texts', 'NOTE_ADD_A_SCAN_OF_CRB'); ?>
            </p>
            <div class="rc-container-uploadnoheight" id="criminalRecords">
                <?php
                $documentsIndexes = array();
                $carerCriminalRecords = $carer->getCriminalRecords();
                $index = 0;
                for ($i = 0; $i < count($carerCriminalRecords); $i++) {
                    $this->renderPartial('_showDocument', array(
                        'carerDocument' => $carerCriminalRecords[$i],
                        'index' => $index,
                            ), false, false);
                    $documentsIndexes[$index] = $carerCriminalRecords[$i]->id;
                    $index++;
                }
                ?>
                <div style="display:none">
                    <div id="criminalRecordsDialog">
                    </div>
                </div>
            </div>
    <?php
    echo CHtml::ajaxLink(Yii::t('texts', 'BUTTON_ADD_A_CRB_CHECK'), $this->createUrl('carer/showDialog/documentType/' . Document::TYPE_CRIMINAL), array(
        'onclick' => '$("#criminalRecordsDialog").dialog("open"); return false;',
        'update' => '#criminalRecordsDialog'
            ), array('id' => 'showCriminalRecordsDialog', 'class' => 'rc-linkbutton'));
    ?>

            <!--  QUALIFICATIONS  -->
            <br>
            <br>
            <br>
            <h3><?php echo Yii::t('texts', 'HEADER_MY_QUALIFICATIONS'); ?></h3>
            <p class="rc-note">
    <?php echo Yii::t('texts', 'NOTE_ADD_A_SCAN_OF_DIPLOMA'); ?>
            </p>
            <div class="rc-container-uploadnoheight" id="diplomas">
                <?php
                $carerDiplomas = $carer->getDiplomas();
                for ($i = 0; $i < count($carerDiplomas); $i++) {
                    $this->renderPartial('_showDocument', array(
                        'carerDocument' => $carerDiplomas[$i],
                        'index' => $index,
                            ), false, false);
                    $documentsIndexes[$index] = $carerDiplomas[$i]->id;
                    $index++;
                }
                //store in session all created documents
                Yii::app()->session['DocumentsIndexes'] = $documentsIndexes;
                ?>
                <div style="display:none">
                    <div id="diplomasDialog">
                    </div>
                </div>
            </div>
    <?php
    if (count($carerDiplomas) < 5) {
        $visibleButton = 'visible';
        $visibleMessage = 'none';
    } else {
        $visibleButton = 'none';
        $visibleMessage = 'visible';
    }
    ?>
            <div id="add_diploma_button" style="display:<?php echo $visibleButton ?>">
            <?php
            echo CHtml::ajaxLink(Yii::t('texts', 'BUTTON_ADD_A_DIPLOMA'), $this->createUrl('carer/showDialog/documentType/' . Document::TYPE_DIPLOMA), array(
                'onclick' => '$("#diplomasDialog").dialog("open"); return false;',
                'update' => '#diplomasDialog'
                    ), array('id' => 'showDiplomasDialog', 'class' => 'rc-linkbutton'));
            ?>
            </div>
            <div id="add_diploma_message" style="display:<?php echo $visibleMessage ?>">
                <p class="rc-note">
                <?php echo Yii::t('texts', 'NOTE_MAXIMUM_DIPLOMA'); ?>
                </p>
            </div>
                    <?php
                }
                $this->endWidget();
                ?>
    </div>
</div>
<script type="text/javascript">
    function submitDocument(dialogId, divId) {

        var formId = "#new" + divId + "-form";

        $.ajax({
            success: function(data) {

                if (data.status == 'failure') {

                    var json = $.parseJSON(data.html);

                    $.each(json, function(key, value) {

                        $("#" + key).addClass("clsError");
                        $("#" + key).show().html(value.toString());
                        $("label[for=" + key + "]").addClass("clsError");
                    });
                }
                else if (data.status == 'saveFailure') {

                    $("#createDialogErrorMessage" + divId).html(data.html);

                }
                else {

                    $("#" + dialogId).dialog("close");
                    $("#" + divId).append(data.html);

                    //check diploma number
                    var count = $("#diplomas .rc-upload-container-dropdown").length;

                    if (count > 4) {
                        $('#add_diploma_button').hide();
                        $('#add_diploma_message').show();
                    }
                    else {
                        $('#add_diploma_button').show();
                        $('#add_diploma_message').hide();
                    }
                }
            },
            type: 'post',
            url: '<?php echo $this->createUrl('documentAdd') ?>',
            data: {
                CarerDocument: decodeURIComponent($(formId).serialize()),
                divId: divId,
            },
            cache: false,
            dataType: 'json'
        });
    }
    ;

    $(document).ready(function()
    {
        $("#Carer_driving_licence_0").click(function() { //NO

            if ($(this).is(":checked")) {
                $(".identifications").hide("slow");
                $(".drivingLicence").show("slow");
            }
        });

        $("#Carer_driving_licence_1").click(function() { //YES

            if ($(this).is(":checked")) {
                $(".identifications").show("slow");
                $(".drivingLicence").hide("slow");
            }
        });
    });
</script>

<?php
if ($maintain == true) {
    ?>
    <script type="text/javascript">

        function editText(entity) {

            $('#' + entity + '_text').css({'background-color': '#FFF', 'color': '#333'});
            $('#' + entity + '_text').prop('disabled', false);
            $('#edit' + entity + 'Text').hide();
            $('#save' + entity + 'Text').show();
            $('#cancel' + entity + 'Text').show();
        }

        function cancelTextSuccess(entity, html) {

            var motivationText = $('#' + entity + '_text');

            motivationText.css({'background-color': '#EEE', 'color': '#666'});
            motivationText.prop('disabled', true);
            $('#edit' + entity + 'Text').show();
            $('#save' + entity + 'Text').hide();
            $('#cancel' + entity + 'Text').hide();
            motivationText.val(html);
        }

        function saveTextSuccess(entity, data) {

            var entityText = $('#' + entity + '_text');
            entityText.css({'background-color': '#EEE', 'color': '#666'});
            entityText.prop('disabled', true);
            $('#edit' + entity + 'Text').show();
            $('#save' + entity + 'Text').hide();
            $('#cancel' + entity + 'Text').hide();
            entityText.val(data.text);

            var text = '';

            if (data.status != 0) {
                text = '<br />' + data.statusText;
            }

            $('#' + entity + '_text_status').html(text);
        }


        $(document).ready(function() {

                        $(".qTipTooltipImage").each(function() {
            
                            var content = $(this).next();
            
                            $(this).qtip({
                                // Simply use an HTML img tag within the HTML string
                                content: content, //'<img width="288" height="" alt="Photo" src="http://localhost:8888/directhomecare/carer/getImage/fileContent/16/doc/18.html" class="rc-image-profile">',
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


            //grey textareas by default
            $('input[type=text], textarea').css({'background-color': '#EEE', 'color': '#666'});
            $('input[type=text], textarea').prop('disabled', true);

            $('#editMotivationText').click(function() {

                editText('Motivation');

            });

            $('#editPersonalText').click(function() {

                editText('Personal');

            });

            $('#cancelMotivationText').click(function() {

                $.ajax({
                    success: function(html) {
                        cancelTextSuccess('Motivation', html);
                    },
                    type: 'get',
                    url: '<?php echo CHtml::normalizeUrl(array('carer/cancelMotivationText')) ?>',
                    data: {},
                    cache: false,
                    dataType: 'html'
                });

            });

            $('#cancelPersonalText').click(function() {

                $.ajax({
                    success: function(html) {
                        cancelTextSuccess('Personal', html);
                    },
                    type: 'get',
                    url: '<?php echo CHtml::normalizeUrl(array('carer/cancelPersonalText')) ?>',
                    data: {},
                    cache: false,
                    dataType: 'html'
                });

            });

            $('#saveMotivationText').click(function() {

                $.ajax({
                    success: function(data) {

                        saveTextSuccess('Motivation', data)

                    },
                    type: 'get',
                    url: '<?php echo CHtml::normalizeUrl(array('carer/saveMotivationText')) ?>',
                    data: {text: $('#Motivation_text').val()},
                    cache: false,
                    dataType: 'json'
                });

            });

            $('#savePersonalText').click(function() {

                $.ajax({
                    success: function(data) {

                        saveTextSuccess('Personal', data)

                    },
                    type: 'get',
                    url: '<?php echo CHtml::normalizeUrl(array('carer/savePersonalText')) ?>',
                    data: {text: $('#Personal_text').val()},
                    cache: false,
                    dataType: 'json'
                });

            });

            //select ids starting with Carer_car_owner
            $('[id^=Carer_car_owner_]').click(function() {

                $.ajax({
                    success: function(data) {

                    },
                    type: 'get',
                    url: '<?php echo CHtml::normalizeUrl(array('carer/saveCarOwner')) ?>',
                    data: {value: $(this).val()},
                    cache: false,
                    dataType: 'json'
                });

            });


        });
    </script>
    <?php
}
?>