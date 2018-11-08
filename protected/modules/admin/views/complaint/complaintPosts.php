
<div id="discussion">

    <?php
    if (isset($complaint)) {
        ?>
        <h3><?php echo $complaint->getSolvedLabel() ?> - Created by <?php echo $complaint->getCreatedByLabel() ?> </h3>
        <p>
            <?php
            $posts = $complaint->complaintPosts;

            foreach ($posts as $post) {
                
                echo $post->authorLabel();
                echo '<br>';
                echo $post->visibleByLabel();

                echo '<p>';
                echo CHtml::textArea('text', $post->text, array('id' => 'Motivation_text', 'maxlength' => 150, 'class' => 'rc-textarea', 'disabled' => 'disabled'));
                echo '</p>';
            }
        }

        $model = new ComplaintPost();

        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'complain-form',
            'enableAjaxValidation' => true,
                ));

        echo $form->textArea($model, 'text', array('id' => "complaint_text", 'maxlength' => 150, 'class' => 'rc-textarea'));
        echo CHtml::hiddenField('complaintId', $complaint->id);
        echo CHtml::hiddenField('solved', $complaint->solved);
        ?>
    <div class="row buttons">


        <p>
            <?php echo CHtml::checkBox('visible_by_client', false); ?>
            visible by client

            <?php echo CHtml::checkBox('visible_by_carer', false); ?>
            visible by carer

        <p>

            <?php echo CHtml::button('Add response', array('submit' => array('/admin/complaint/addResponse'))); ?>

            <?php
            if ($complaint->solved == Complaint::UNSOLVED) {

                $buttonText = 'Set to solved';
            } else {
                $buttonText = 'Set to unsolved';
            }

            echo CHtml::button($buttonText, array('submit' => array('/admin/complaint/toggleSolved')));
            ?>
    </div>
    <?php
    $this->endWidget();
    ?>
    <p>

    <div>
        <?php
        $url = $this->createUrl("missionAdmin/viewMission", array("missionId" => $complaint->id_mission));

        echo CHtml::link('Create aborted Slot', $url);
        ?>

    </div>


</div>

