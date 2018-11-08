<script type="text/javascript">
    function editMode(index){

        $('#service_user' + index + ' :input').removeAttr('disabled');
        
        $('#add_service_user').attr('disabled', true);
       
        $('#save' + index).show();
        $('#cancel' + index).show();
        
        $('#save_bottom' + index).show();
        $('#cancel_bottom' + index).show();
        
        $('#edit' + index).hide();
        $('#remove' + index).hide();          
        $('#used' + index).hide();     

        $('.buttons_input').hide();
        $('.buttons_span').show();
        
        $('#bottom_bar_' + index).show();
    }

    function displayMode(index){

        $('#service_user' + index + ' :input').attr('disabled','disabled');
        
        $("#add_service_user").removeAttr("disabled");
        $("#check_box" + index).removeAttr("disabled");
 
        $('#save' + index).hide();
        $('#cancel' + index).hide();
        
        $('#save_bottom' + index).hide();
        $('#cancel_bottom' + index).hide();
        
        $('#edit' + index).show();
        $('#edit' + index).removeAttr('disabled');
        
        $('#remove' + index).show();
        $('#remove' + index).removeAttr('disabled');   
        
        $('.buttons_input').show();
        $('.buttons_span').hide();
        $('#used' + index).show();
        
        $('#bottom_bar_' + index).hide();
    }    
    
    function disableAllButtons(currentIndex, userIndexes){
        
        $("#add_service_user").hide();
        $("#add_service_user_disabled").show();
        
        //disable all butons
        for (var i = 0; i < userIndexes.length; i++) {
            
            var index = userIndexes[i];
            if(i != currentIndex){
                
                $('#remove' + index).hide();
                $('#edit' + index).hide();
                $('#edit' + index + 'disabled').show();
                $('#remove' + index + 'disabled').show();
            }
            
        }
    }
    
    function enableAllButtons(userIndexes){
     
        $("#add_service_user").show();
        $("#add_service_user_disabled").hide();
            
        //disable all butons
        for (var i = 0; i< userIndexes.length; i++){       
            
            var index = userIndexes[i];
            $('#edit' + index).show();        
            $('#remove' + index).show();        
            $('#edit' + index + 'disabled').hide();          
            $('#remove' + index + 'disabled').hide();          
        }
    }
</script>
<div id="service_users">
    <?php 
    $numberUsers = count($this->serviceUsers);
    
    for ($i = 0; $i < count($this->serviceUsers); $i++): ?>
        <?php
        $serviceUser = $this->serviceUsers[$i];
        $new = $serviceUser->isNewRecord;

        $this->render('_service_user', array(
            'serviceUser' => $serviceUser,
            'index' => $i,
            'errorMessages' => null,
            'scenario' => $scenario,
            'actionPath' => $this->getController()->id . '/serviceUsers.',
            'newServiceUser' => $new,
            'numberUsers' => $numberUsers,
        ));
    endfor
    ?>
</div>
<br />
<?php
//echo CHtml::ajaxButton(Yii::t('texts', 'BUTTON_ADD_ANOTHER_USER'), $this->controller->createUrl('serviceUsers.addUser'), array(
//    'beforeSend' => 'function() {   }',
//    'success' => 'function(html) {          
//        $("#service_users").append(html);                          
//     }',
//    'error' => 'function(data) {
//         }',
//        ), array('class' => 'rc-button-white', 'id' => 'add_service_user')
//);
//echo '<span id="add_service_user_disabled" class="rc-linkbutton-white-disabled" style="display:none">' . Yii::t('texts', 'BUTTON_ADD_ANOTHER_USER') . '</span>';
?>