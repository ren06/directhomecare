<?php

class UIServices {

    /**
     * Must implement a dialogYes javascript function for the Yes action
     */
    public static function showMissionDialog($actionURL, $missionId, $mainText, $buttonText1, $buttonText2, $title, $returnURL = '', $width = 500, $height = 170) {

        $buttonId = uniqid();

        if ($actionURL === "cancelAppliedChoose") {
            $grid = '#' . Tables::AVAILABLE_MISSIONS_GRID;
            $url = CHtml::normalizeUrl(array("carer/cancelAppliedChooseMission"));
        } else if ($actionURL === "cancelSelected") {
            $grid = '#' . Tables::SELECTED_MISSIONS_GRID;
            $url = CHtml::normalizeUrl(array("carer/cancelSelected")); //// logic changed
        } else if ($actionURL === "confirmSelected") {
            $grid = '#assignedAndSelectedMissions';
            $url = CHtml::normalizeUrl(array("carer/confirmSelected")); ////  logic changed
        } else if ($actionURL === "cancelApplied") {
            $grid = '#' . Tables::AWAITING_MISSIONS_GRID;
            $url = CHtml::normalizeUrl(array("carer/cancelAppliedBookedMission"));
        } else if ($actionURL === "cancelAssigned") {
            $grid = '#' . Tables::ASSIGNED_MISSIONS_GRID;
            $url = CHtml::normalizeUrl(array("carer/cancelAssigned"));
        } else if ($actionURL === "cancelMission") {
            $grid = '#' . Tables::BOOKED_SERVICES_GRID;
            $url = CHtml::normalizeUrl(array("clientManageBookings/cancelService"));
        } else {
            $url = $actionURL;
            $grid = '';
        }
//        
//         $button1 = CHtml::ajaxButton($buttonText1, $url, 
//        array('success' => "function(html) {
//            $('div.ui-dialog:visible').find('div.ui-dialog-content').dialog('close');            
//            $(grid).replaceWith(html);
//        }",
//              'type' => 'post',
//              'url' => $url,
//              'data' => array('id' => $missionId),
//              'dataType' => 'html'),
//        array('id' => $buttonId, 'class' => 'rc-button'));

        $button1 = CHtml::submitButton($buttonText1, array('id' => $buttonId, 'class' => 'rc-button',
                    'onclick' => "dialogYes('$buttonId', '$url', '$grid', '$missionId', '$returnURL');"));

        $button2 = CHtml::submitButton($buttonText2, array('id' => uniqid(), 'class' => 'rc-button',
                    'onclick' => "$('div.ui-dialog:visible').find('div.ui-dialog-content').dialog('close')"));

        self::unregisterAllScripts();

        $dialogConfig = new DialogConfig($title, $mainText, array($button1, $button2), $width, $height);

        $output = Yii::app()->controller->renderPartial('/common/_createConfirmationDialog', array('dialogConfig' => $dialogConfig), true, true);

        return $output;
    }

//    public static function showOKDialog($mainText, $buttonText, $title) {
//
//        $button = CHtml::button($buttonText, array('class' => 'rc-button', 'style' => 'width:100px;',
//                    'onclick' => "$('div.ui-dialog:visible').find('div.ui-dialog-content').dialog('close')"));
//
//        self::unregisterAllScripts();
//
//        $dialogConfig = new DialogConfig($title, $mainText,
//                        array($button), 500, 110);
//
//        $output = Yii::app()->controller->renderPartial('/creditCard/_createConfirmationDialog', array('dialogConfig' => $dialogConfig), true, true);
//
//        return $output;
//    }

    public static function checkInternetConnection() {

        $connected = @fsockopen("www.google.com", 80);
        if ($connected) {

            $is_con = true;
            fclose($connected);
        } else {
            $is_con = false;
        }
        return $is_con;
    }

    public static function showMap($postCode) {

        if (true) {//self::checkInternetConnection()) {
            // import the library
            Yii::import('application.extensions.EGMap2.*');

            $gMap = new EGMap();
            $gMap->zoom = 13;
            $mapTypeControlOptions = array(
                'position' => EGMapControlPosition::LEFT_BOTTOM,
                'style' => EGMap::MAPTYPECONTROL_STYLE_DROPDOWN_MENU
            );

            $gMap->mapTypeControlOptions = $mapTypeControlOptions;

            $address = $postCode . ', ' . Yii::app()->params['mapLocation'];

            // Create geocoded address
            $geocoded_address = new EGMapGeocodedAddress($address);

            $geocoded_address->geocode($gMap->getGMapClient());

            // Center the map on geocoded address
            $gMap->setCenter($geocoded_address->getLat(), $geocoded_address->getLng());
            $gMap->setWidth('29em');
            $gMap->setHeight('25.5em');


            $geocoded_address->geocode($gMap->getGMapClient());

            // Create GMapInfoWindows
            $info_window_a = new EGMapInfoWindow('<div>' . Yii::t('texts', 'HEADER_LOCATION') . '</div>');

            $icon = new EGMapMarkerImage(Yii::app()->request->baseUrl . '/images/icon-mappointer.png');
            $icon->setSize(32, 37);
            $icon->setAnchor(16, 16.5);
            $icon->setOrigin(0, 0);

            // Create marker
            $marker = new EGMapMarker($geocoded_address->getLat(), $geocoded_address->getLng(), array('title' => Yii::t('texts', 'HEADER_LOCATION'), 'icon' => $icon));

            // $marker->addHtmlInfoWindow($info_window_a); No InfoWindow in Details

            $gMap->addMarker($marker);

            $gMap->renderMap(null, 'en-GB', 'GB');
        } else {

            echo Yii::t('texts', 'ERROR_COULD_NOT_CONNECT_TO_GOOGLE_MAPS');
        }
    }

    public static function showMissionMap($object) {

        self::showMap($object->serviceLocation->post_code);
    }

    public static function widgetCheckBoxSelect($checkBoxState, $index, $sessionVariable) {

        $userIndexes = Yii::app()->session[Session::USERS_INDEXES];

        $id = $userIndexes[$index];

        $selectedIds = Yii::app()->session[$sessionVariable];

        if ($checkBoxState == '0') {

            unset($selectedIds[$id]);
        } else {

            $selectedIds[$id] = $id;
        }

        Yii::app()->session[$sessionVariable] = $selectedIds;
    }

    public static function widgetRadioButtonSelect($index) {

        $selectedId = Session::getSelectedServiceLocation();
        $userIndexes = Yii::app()->session[Session::USERS_INDEXES];
        $userIndexesKeys = array_keys($userIndexes);

        foreach ($userIndexesKeys as $userIndexKey) {

            $id = (int) $userIndexes[$userIndexKey];

            if ($userIndexKey == $index) {
                $selectedId = $id;
                break;
            }
        }
        Session::setSelectedServiceLocation($id);
    }

    /**
     * 
     * @param type $objects
     * @param type $centreAddress Address object
     */
    public static function showMissionsMap($objects, $centreAddress = null) {

        $i = 0;

        // import the library
        Yii::import('application.extensions.EGMap2.*');

        $gMap = new EGMap();

        $gMap->setWidth('60em');
        $gMap->setHeight('29em');

        $mapTypeControlOptions = array(
            'position' => EGMapControlPosition::LEFT_BOTTOM,
            'style' => EGMap::MAPTYPECONTROL_STYLE_DROPDOWN_MENU
        );

        //check google maps available

        $gMap->mapTypeControlOptions = $mapTypeControlOptions;

        foreach ($objects as $object) {

            $i++;

            $serviceLocation = $object->serviceLocation;

            if ($serviceLocation->latitude == 0 && $serviceLocation->longitude == 0) {

                $serviceLocation->setCoordinates();
                $serviceLocation->save();
            }

            $info_window_a = new EGMapInfoWindow('<div class="rc-map-infowindow"><b>' . Yii::t('texts', 'TABLES_HEADER_MISSION') . '&#160;' . $i . '</b>' . $object->displayHTML() . '</div>');

            $icon = new EGMapMarkerImage(Yii::app()->request->baseUrl . '/images/icon-mappointer.png');
            $icon->setSize(32, 37);
            $icon->setAnchor(16, 16.5);
            $icon->setOrigin(0, 0);

            // Create marker
            $marker = new EGMapMarker($serviceLocation->latitude, $serviceLocation->longitude, array('title' => Yii::t('texts', 'ALT_CLICK_FOR_DETAILS'), 'icon' => $icon));

            $marker->addHtmlInfoWindow($info_window_a);

            $gMap->addMarker($marker);
        }

        //$gMap->enableMarkerClusterer(new EGMapMarkerClusterer(array('minimumClusterSize' => 10, 'styles' => array())));
        //To center on London
        if (isset($centreAddress)) {

            if ($centreAddress->latitude == 0 && $centreAddress->longitude == 0) {

                $centreAddress->setCoordinates();
                $centreAddress->save();
            }

            $address = $centreAddress->post_code . ', UK';

            $geocoded_address = new EGMapGeocodedAddress($address);
        } else {
            $geocoded_address = new EGMapGeocodedAddress('London');
        }

        //call the service
        $geocoded_address->geocode($gMap->getGMapClient());

        $gMap->zoom = 10;

        $gMap->setCenter($geocoded_address->getLat(), $geocoded_address->getLng());

        $gMap->renderMap(null, 'en-GB', 'GB');
    }

    /**
     * 
     * @param type $objects
     * @param type $centreAddress Address object
     */
    public static function showCarersMap($carers, $centreAddress = null) {

        $i = 0;

        // import the library
        Yii::import('application.extensions.EGMap2.*');

        $gMap = new EGMap();

        $gMap->setWidth('80em');
        $gMap->setHeight('55em');

        $mapTypeControlOptions = array(
            'position' => EGMapControlPosition::LEFT_BOTTOM,
            'style' => EGMap::MAPTYPECONTROL_STYLE_DROPDOWN_MENU
        );

        //check google maps available

        $gMap->mapTypeControlOptions = $mapTypeControlOptions;

        foreach ($carers as $carer) {

            $address = $carer->address;

            if (isset($address)) {

                $info_window_a = new EGMapInfoWindow('<div class="rc-map-infowindow"><b>' . 'Carer: ' . $carer->displayAdmin() . '</div>');

                $icon = new EGMapMarkerImage(Yii::app()->request->baseUrl . '/images/googlemap-home.png');
                $icon->setSize(32, 37);
                $icon->setAnchor(16, 16.5);
                $icon->setOrigin(0, 0);

                // Create marker
                $marker = new EGMapMarker($address->latitude, $address->longitude, array('title' => $carer->fullName, 'icon' => $icon));

                $marker->addHtmlInfoWindow($info_window_a);

                $gMap->addMarker($marker);
                $i++;
            }
        }

        //$gMap->enableMarkerClusterer(new EGMapMarkerClusterer(array('minimumClusterSize' => 10, 'styles' => array())));
        //To center on London
        if (isset($centreAddress)) {

            if ($centreAddress->latitude == 0 && $centreAddress->longitude == 0) {

                $centreAddress->setCoordinates();
                $centreAddress->save();
            }

            $address = $centreAddress->post_code . ', UK';

            $geocoded_address = new EGMapGeocodedAddress($address);
        } else {
            $geocoded_address = new EGMapGeocodedAddress('London');
        }

        //call the service
        $geocoded_address->geocode($gMap->getGMapClient());

        $gMap->zoom = 10;

        $gMap->setCenter($geocoded_address->getLat(), $geocoded_address->getLng());

        $gMap->renderMap(null, 'en-GB', 'GB');
    }

    public static function unregisterJQueryScripts() {

        Yii::app()->clientScript->scriptMap['jquery.js'] = false; //
        Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
        Yii::app()->clientScript->scriptMap['jquery-ui.js'] = false;
        Yii::app()->clientScript->scriptMap['jquery-ui.min.js'] = false; //
        Yii::app()->clientScript->scriptMap['fileuploader.js'] = false;
        Yii::app()->clientScript->scriptMap['jquery.ba-bbq.js'] = false; //
        Yii::app()->clientScript->scriptMap['jquery.qtip.min.js'] = false;   //        
        //C:\xampp\htdocs\directhomecare\protected\extensions\jcrop\assets\js\ejcrop.js
    }

    public static function unregisterJQueryYiiScripts() {

        Yii::app()->clientScript->scriptMap['jquery.yiiactiveform.js'] = false;
        Yii::app()->clientScript->scriptMap['jquery.yiigridview.js'] = false;
        Yii::app()->clientScript->scriptMap['jquery.yiigridview.js'] = false;
        Yii::app()->clientScript->scriptMap['jquery.yii.js'] = false; //
        Yii::app()->clientScript->scriptMap['jquery.yiigridview.js'] = false; //
    }

    public static function unregisterAllScripts() {
        self::unregisterJQueryScripts();
        self::unregisterJQueryYiiScripts();
    }

    /**
     * 
     * @param type $label Label of the tooltip
     * @param type $tooltip Tooltip text
     * @param type $extraClass Add extra css class to span
     * @return type string
     */
    public static function renderTooltip($label, $tooltip, $extraClass = '', $slow = false) { //to delete this function as we will be using the HelpTooltip only
        //escape
        $tooltip = htmlspecialchars($tooltip);

        if ($slow) {
            return "<span class='qTipTooltipSlow rc-link-toolbox $extraClass' tooltip='$tooltip'>$label</span>";
        } else {
            return "<span class='qTipTooltip rc-link-toolbox $extraClass' tooltip='$tooltip'>$label</span>";
        }
    }

    /**
     * 
     * Creates a label (?) with a tooltip
     * 
     * @param type $tooltip Tooltip text
     * @param type $extraClass Add extra css class to span
     * @return type string
     */
    public static function renderHelpTooltip($tooltip, $extraClass = '') {

        return '&#160;' . '<span class="qTipTooltip rc-link-toolbox ' . $extraClass . '" tooltip="' . $tooltip . '"><span style="color:#A0A0A0">[</span>?<span style="color:#A0A0A0">]</span></span>';
    }

}

?>