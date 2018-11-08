<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of self
 *
 * @author I031360
 */
class Wizard2 {

    const VIEW_FIND_CARERS = 'findCarers';
    const VIEW_SELECT_DATES = 'selectDates';
    const VIEW_SIGNUP_LOGIN = 'signupLogin';
    const VIEW_USER_LOCATION = 'userLocation';
    const VIEW_ORDER_PAYMENT = 'orderPayment';
    const VIEW_CONFIRMATION = 'confirmation';
    const CLIENT_LAST_STEP_INDEX = 4;

    public static function initStepsClient() {

        $steps = array(
            self::VIEW_FIND_CARERS => array('name' => self::VIEW_FIND_CARERS, 'completion' => 0, 'view' => self::VIEW_FIND_CARERS, 'controller' => 'site', 'label' => Yii::t('texts', 'Find Carers'), 'completed' => false, 'active' => true, 'enabled' => true),
            self::VIEW_SIGNUP_LOGIN => array('name' => self::VIEW_SIGNUP_LOGIN, 'completion' => 1, 'view' => self::VIEW_SIGNUP_LOGIN, 'controller' => 'site', 'label' => Yii::t('texts', 'Signup Login'), 'completed' => false, 'active' => false, 'enabled' => true),
            self::VIEW_SELECT_DATES => array('name' => self::VIEW_SELECT_DATES, 'completion' => 2, 'view' => self::VIEW_SELECT_DATES, 'controller' => 'site', 'label' => Yii::t('texts', 'Select Dates'), 'completed' => false, 'active' => false, 'enabled' => true),
            self::VIEW_USER_LOCATION => array('name' => self::VIEW_USER_LOCATION, 'completion' => 3, 'view' => self::VIEW_USER_LOCATION, 'controller' => 'client', 'label' => Yii::t('texts', 'User Location'), 'completed' => false, 'active' => false, 'enabled' => true),
            self::VIEW_ORDER_PAYMENT => array('name' => self::VIEW_ORDER_PAYMENT, 'completion' => 4, 'view' => self::VIEW_ORDER_PAYMENT, 'controller' => 'client', 'label' => Yii::t('texts', 'Order Payment'), 'completed' => false, 'active' => false, 'enabled' => true),
            self::VIEW_CONFIRMATION => array('name' => self::VIEW_CONFIRMATION, 'completion' => 5, 'view' => self::VIEW_CONFIRMATION, 'controller' => 'client', 'label' => Yii::t('texts', 'Confirmation'), 'completed' => false, 'active' => false, 'enabled' => true),
        );

        Yii::app()->session['steps'] = $steps;
    }

    public static function initStepsNewBooking() {

        $steps = array(
            self::VIEW_SELECT_DATES => array('name' => self::VIEW_SELECT_DATES, 'completion' => 2, 'view' => self::VIEW_SELECT_DATES, 'controller' => 'site', 'label' => Yii::t('texts', 'Select Dates'), 'completed' => false, 'active' => false, 'enabled' => true),
            self::VIEW_USER_LOCATION => array('name' => self::VIEW_USER_LOCATION, 'completion' => 3, 'view' => self::VIEW_USER_LOCATION, 'controller' => 'client', 'label' => Yii::t('texts', 'User Location'), 'completed' => false, 'active' => false, 'enabled' => true),
            self::VIEW_ORDER_PAYMENT => array('name' => self::VIEW_ORDER_PAYMENT, 'completion' => 4, 'view' => self::VIEW_ORDER_PAYMENT, 'controller' => 'client', 'label' => Yii::t('texts', 'Order Payment'), 'completed' => false, 'active' => false, 'enabled' => true),
            self::VIEW_CONFIRMATION => array('name' => self::VIEW_CONFIRMATION, 'completion' => 5, 'view' => self::VIEW_CONFIRMATION, 'controller' => 'client', 'label' => Yii::t('texts', 'Confirmation'), 'completed' => false, 'active' => false, 'enabled' => true),
        );

        Yii::app()->session['steps'] = $steps;
    }

    private static function getSteps() {
        return $steps = Yii::app()->session['steps'];
    }

    private static function getStepByName($stepName) {
        $steps = self::getSteps();
        return $steps[$stepName];
    }

    public static function isStarted() {
        $steps = self::getSteps();
        return isset($steps);
    }

    public static function getStepByIndex($int) {

        $steps = self::getSteps();
        foreach ($steps as $step) {
            if ($step['completion'] == $int) {
                return $step;
            }
        }
    }

    public static function getStepByIndexPath($int) {
        $step = self::getStepByIndex($int);
        $path = $step['controller'] . '/' . $step['view'];
        return $path;
    }

    public static function setCurrentStepCompleted() {

        $activeStep = self::getActiveStepName();
        $steps = self::getSteps();

        $steps[$activeStep]['completed'] = true;

        Yii::app()->session['steps'] = $steps;
    }

    public static function setActiveStep($stepName) {

        //if entered step does not have its previous step completed
        //redirect to first page

        $steps = self::getSteps();

        if (isset($steps)) {

            foreach ($steps as &$step) {

                if ($step['name'] == $stepName) {
                    $step['active'] = true;
                } else {
                    $step['active'] = false;
                }
            }

            Yii::app()->session['steps'] = $steps;
        }
    }

    public static function getActiveStepName() {

        //if entered step does not have its previous step completed
        //redirect to first page

        $steps = self::getSteps();

        if (isset($steps)) {

            foreach ($steps as &$step) {

                if ($step['active'] == true) {
                    return $step['name'];
                }
            }
        }
    }

    private static function getStepCompletion($stepName) {

        $steps = self::getSteps();

        return $steps[$stepName]['completion'];
    }

    private static function getActiveStepIndex() {
        $steps = self::getSteps();
        $currentStep = self::getActiveStepName();

        $keys = array_keys($steps);

        $currentStepIndex = array_search($currentStep, $keys);

        return $currentStepIndex;
    }

    public static function getNextStepName() {

        $currentStepIndex = self::getActiveStepIndex();

        $steps = self::getSteps();
        $newArray = array_keys($steps);
        $stepName = $newArray[$currentStepIndex + 1];

        return $stepName;
    }

    public static function getPreviousStepName() {

        $currentStepIndex = self::getActiveStepIndex();

        $steps = self::getSteps();
        $newArray = array_keys($steps);
        if ($currentStepIndex != 0) {
            $index = $currentStepIndex - 1;
        } else {
            $index = 0; //in case it's first step
        }
        $stepName = $newArray[$index];

        return $stepName;
    }

    public static function removeStep($stepName) {

        $steps = self::getSteps();

        unset($steps[$stepName]);

        Yii::app()->session['steps'] = $steps;
    }

    public static function generateWizard() {

        $steps = self::getSteps();

        if (isset($steps)) {
            $result = '<div class="rc-container-button">' . "\n";
            $i = 0;

            $disabled = false;
            $ARROW_SIGN = '<span class="rc-arrow">&#62;</span>';

            //remove confirmation step

            $keys = array_keys($steps);
            $lastKey = $keys[count($keys) - 1];
            unset($steps[$lastKey]);
            $size = count($steps);
            // end remove confirmaion step
            foreach ($steps as &$step) {

                //figure out if completed
                if ($i == 0) {
                    $arrowSign = $ARROW_SIGN;
                    if ($step['active'] == true) {

                        //  $line = '<span class="wzd-active wzd-first">';
                        $cssClass = 'rc-wzd-active';
                    } elseif ($step['completed'] == true) {
                        $cssClass = 'rc-wzd-previous';
                    } else {
                        $cssClass = 'rc-wzd-button';
                        $disabled = true;
                    }
                } elseif ($i == $size - 1) {
                    $arrowSign = null;
                    if ($step['active'] == true) {
                        $cssClass = 'rc-wzd-active';
                    } elseif ($step['completed'] == true) {
                        $cssClass = 'rc-wzd-previous';
                    } else {
                        $cssClass = 'rc-wzd-button';
                        $disabled = true;
                    }
                } else {
                    $arrowSign = $ARROW_SIGN;
                    if ($step['active'] == true) {
                        $cssClass = 'rc-wzd-active';
                    } elseif ($step['completed'] == true) {
                        $cssClass = 'rc-wzd-previous';
                    } else {
                        $cssClass = 'rc-wzd-button';
                        $disabled = true;
                    }
                }

                $htmlOptions = array('class' => $cssClass, 'submit' => array(Yii::app()->session['controller'] . '/' . self::getActiveStepName(), 'toView' => $step['name']));

                //SPECIAL CASES STYLING
                //SPECIAL CASE FOR SIGN IN
                if ($step['enabled'] == false) {

                    //if step is active create button
                    if ($step['active'] == true) {

                        $button = CHtml::submitButton($step['label'], $htmlOptions) . $arrowSign;
                    } else {

                        //greyed all the time when not active
                        $button = '<span class="rc-linkbutton-disabled">' . $step['label'] . '</span>' . $arrowSign;
                    }
                }
                //when the button is disabled (step not reached) or the self is finished
                elseif ($disabled || self::isStepAllCompleted() == true) {

                    if ($i == (count($steps) - 1)) { //last step
                        if (self::isStepAllCompleted()) {
                            //Display normally the last button (Confirmation)
                            $button = CHtml::submitButton($step['label'], $htmlOptions) . $arrowSign;
                        } else {
                            $button = '<span class="rc-linkbutton-disabled">' . $step['label'] . '</span>' . $arrowSign;
                        }
                    } else {
                        //grey all other buttons
                        $button = '<span class="rc-linkbutton-disabled">' . $step['label'] . '</span>' . $arrowSign;
                    }
                } else {
                    //add normal button (style defined in first algorithm)
                    $button = CHtml::submitButton($step['label'], $htmlOptions) . $arrowSign;
                }

                $result = $result . $button;

                $i++;
            }

            $result = $result . ' </div>' . "\n";

            return $result;
        }
    }

//    public static function handleWizardStepCompleted($client, $view) {
//
//        $currentStepIndex = self::getCompletedStepIndex($view);
//        $wizardCompleted = $client->wizard_completed;
//
//        if ($currentStepIndex > $wizardCompleted) {
//            $client->wizard_completed = $currentStepIndex;
//            $client->save(false);
//        }
//    }

    public static function handleClientSecurity2() {

        //check if at least 3 carers and a valid quote
        //$selectedCarers = count(Session::getSelectedCarers());
        $selectedCarer = Session::getSelectedCarer();

        $quote = Session::getSelectedValidQuote();

        //if ($selectedCarers < BusinessRules::getCarerSelectionMinimumSelected() || !isset($quote)) {
        if (!isset($selectedCarer) || !isset($quote)) {

            throw new CHttpException(403, Yii::t('texts', 'FLASH_ERROR_403_YOU_ARE_NOT_AUTHORISED_TO_VIEW_THIS_PAGE'));
        }
    }

    public static function handleClientSecurity3() {

        self::handleClientSecurity2();

        $serviceLocation = Session::getSelectedServiceLocation();
        $serviceUsers = Session::getSelectedServiceUsers();

        //check if at least 1 service user and 1 service location);

        if (!isset($serviceLocation) || !isset($serviceUsers)) {

            throw new CHttpException(403, Yii::t('texts', 'FLASH_ERROR_403_YOU_ARE_NOT_AUTHORISED_TO_VIEW_THIS_PAGE'));
        }
    }

    public static function handleClientSecurity4() {

        if (Wizard2::getActiveStepName() != self::VIEW_CONFIRMATION) {

            throw new CHttpException(403, Yii::t('texts', 'FLASH_ERROR_403_YOU_ARE_NOT_AUTHORISED_TO_VIEW_THIS_PAGE'));
        }
    }

    private static function redirect($stepName, $pathOnly = false) {
        $steps = self::getSteps();
        $step = $steps[$stepName];
        $path = $step['controller'] . '/' . $step['view'];
        if ($pathOnly) {
            return $path;
        } else {
            Yii::app()->controller->redirect(array($path));
        }
    }

    public static function redirectNext($pathOnly = false) {

        $currentStepName = self::getActiveStepName();

        $nextStepName = self::getNextStepName();
        self::setActiveStep($nextStepName);

        //update last step
        if (!Yii::app()->user->isGuest) {

            if (Yii::app()->user->roles === Constants::USER_CLIENT) {

                $client = Client::loadModel(Yii::app()->user->id);

                $wizardCompleted = $client->wizard_completed;

                if ($wizardCompleted != Wizard2::CLIENT_LAST_STEP_INDEX) {
                    $currentCompletion = self::getStepCompletion($currentStepName);

                    if ($currentCompletion > $wizardCompleted) {
                        $client->wizard_completed = $currentCompletion;
                        Yii::app()->user->wizard_completed = $currentCompletion;
                        $client->save(false);
                    }
                }
            }
        }

        if ($currentStepName === self::VIEW_SIGNUP_LOGIN) {
            //remove step
            self::removeStep($currentStepName);
        } else {
            self::setCurrentStepCompleted();
        }

        return self::redirect($nextStepName, $pathOnly);
    }

    public static function redirectPrevious($pathOnly = false) {
        $previousStepName = self::getPreviousStepName();

        self::setActiveStep($previousStepName);
        return self::redirect($previousStepName, $pathOnly);
    }

    public static function redirectCurrent($pathOnly = false) {
        $currentStep = self::getActiveStepName();
        Session::setShowErrors(true);
        return self::redirect($currentStep, $pathOnly);
    }

    public static function adjustBrowser() {

        $stepName = Wizard2::getActiveStepName();
        $currentAction = Yii::app()->controller->action->id;

        if ($currentAction != $stepName) {
            //user pressed browser back button or next
            if ($currentAction != self::VIEW_SIGNUP_LOGIN) {
                Wizard2::setActiveStep($currentAction); //don't want to that when coming back to sign up, step does not exist anymore
            }
            return true;
        } else {
            return false;
        }
    }

}

?>