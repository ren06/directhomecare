<?php

return array(
            'showIdTable' => false,
            'showSpec' => false,
            'showPopulateData' => true,
            'showChat' => false,
            'useMouseFlow' => false,
            'setTime' => false,
            'today' => '2013-06-12 12:05:00', //used if setTime is true
            'allowClient' => true,
            'allowClientPayment' => true,
            'defaultService' => 2, //DEPRECATED //default 1 : live in 2 hourly 
            'otherServiceEnabled' => true, //DEPRECATED //if default service is 1, then 2 is disabled and vice versa
            'debugUser' => true,
            'debugUserId' => '580',
            'debugUserRole' => '2',
            'debugCancelByClientCreditCarer' => true, //when false: the carer is not paid when the mission is cancelled by client
            'paymentTest' => true, //if true use test environment, if false use live one
            //'customerChatEnabled' => false,
        )
?>
