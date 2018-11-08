<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of wizard
 *
 * @author I031360
 */
class Wizard {

    const VIEW_DETAILS = 'details';
    const VIEW_TYPE_WORK = 'typeWork';
    const VIEW_PROFILE = 'profile';
    const VIEW_CONFIRMATION = 'confirmation';
    const VIEW_SIGNIN = 'signIn';
    const VIEW_SERVICE_USER = 'serviceUser';
    const VIEW_SERVICE_LOCATION = 'serviceLocation';
    const VIEW_SERVICE_ORDER = 'serviceOrder';
    const VIEW_SERVICE_CARERS = 'serviceCarers';
    const VIEW_SERVICE_PAYMENT = 'servicePayment';
    const VIEW_SERVICE_CONFIRMATION = 'serviceConfirmation';
    const VIEW_QUOTE = 'quote';
    const CARER_LAST_STEP_INDEX = 3;

    //TODO implement later
    //const CLIENT_LAST_STEP_INDEX = 6;

    private static function init($steps, $controllerName) {

        Yii::app()->session['steps'] = $steps;
        Yii::app()->session['controller'] = $controllerName;
    }

    public static function initStepArrayCarer($controllerName) {

        $steps = array(
            Wizard::VIEW_DETAILS => array('actionName' => Wizard::VIEW_DETAILS, 'label' => Yii::t('texts', 'WIZARD_CARER_SIGN'), 'completed' => false, 'active' => true, 'enabled' => true),
            Wizard::VIEW_TYPE_WORK => array('actionName' => Wizard::VIEW_TYPE_WORK, 'label' => Yii::t('texts', 'WIZARD_CARER_TYPE_WORK'), 'completed' => false, 'active' => false, 'enabled' => true),
            Wizard::VIEW_PROFILE => array('actionName' => Wizard::VIEW_PROFILE, 'label' => Yii::t('texts', 'WIZARD_CARER_PROFILE'), 'completed' => false, 'active' => false, 'enabled' => true),
            Wizard::VIEW_CONFIRMATION => array('actionName' => Wizard::VIEW_CONFIRMATION, 'label' => Yii::t('texts', 'WIZARD_CARER_CONFIRMATION'), 'completed' => false, 'active' => false, 'enabled' => true),
        );

        self::init($steps, $controllerName);
    }

    /**
     *      
     */
    public static function initStepArrayClientWzd1SignIn($controllerName) {

        $steps = array(
            Wizard::VIEW_QUOTE => array('actionName' => Wizard::VIEW_QUOTE, 'label' => Yii::t('texts', 'WIZARD_CLIENT_QUOTE'), 'completed' => false, 'active' => true, 'enabled' => true),
            Wizard::VIEW_SIGNIN => array('actionName' => Wizard::VIEW_SIGNIN, 'label' => Yii::t('texts', 'WIZARD_CLIENT_SIGN'), 'completed' => false, 'active' => false, 'enabled' => true),
            Wizard::VIEW_SERVICE_LOCATION => array('actionName' => Wizard::VIEW_SERVICE_LOCATION, 'label' => Yii::t('texts', 'WIZARD_CLIENT_LOCATION'), 'completed' => false, 'active' => false, 'enabled' => true),
            Wizard::VIEW_SERVICE_USER => array('actionName' => Wizard::VIEW_SERVICE_USER, 'label' => Yii::t('texts', 'WIZARD_CLIENT_USERS'), 'completed' => false, 'active' => false, 'enabled' => true),
            Wizard::VIEW_SERVICE_CARERS => array('actionName' => Wizard::VIEW_SERVICE_CARERS, 'label' => Yii::t('texts', 'WIZARD_CLIENT_CARERS'), 'completed' => false, 'active' => false, 'enabled' => true),
            // Wizard::VIEW_SERVICE_ORDER => array('actionName' => Wizard::VIEW_SERVICE_ORDER, 'label' => Yii::t('texts', 'WIZARD_CLIENT_ORDER'), 'completed' => false, 'active' => false, 'enabled' => true),
            Wizard::VIEW_SERVICE_PAYMENT => array('actionName' => Wizard::VIEW_SERVICE_PAYMENT, 'label' => Yii::t('texts', 'WIZARD_CLIENT_PAYMENT'), 'completed' => false, 'active' => false, 'enabled' => true),
            Wizard::VIEW_SERVICE_CONFIRMATION => array('actionName' => Wizard::VIEW_SERVICE_CONFIRMATION, 'label' => Yii::t('texts', 'WIZARD_CLIENT_CONFIRMATION'), 'completed' => false, 'active' => false, 'enabled' => true),
        );

        self::init($steps, $controllerName);
    }

    /**
     * Steps with details instead of sign in
     */
    public static function initStepArrayClientWzd1Details($controllerName) {

        $steps = array(
            Wizard::VIEW_QUOTE => array('actionName' => Wizard::VIEW_QUOTE, 'label' => Yii::t('texts', 'WIZARD_CLIENT_QUOTE'), 'completed' => false, 'active' => true, 'enabled' => true),
            Wizard::VIEW_SIGNIN => array('actionName' => Wizard::VIEW_SIGNIN, 'label' => Yii::t('texts', 'WIZARD_CLIENT_DETAILS'), 'completed' => false, 'active' => false, 'enabled' => true),
            Wizard::VIEW_SERVICE_LOCATION => array('actionName' => Wizard::VIEW_SERVICE_LOCATION, 'label' => Yii::t('texts', 'WIZARD_CLIENT_LOCATION'), 'completed' => false, 'active' => false, 'enabled' => true),
            Wizard::VIEW_SERVICE_USER => array('actionName' => Wizard::VIEW_SERVICE_USER, 'label' => Yii::t('texts', 'WIZARD_CLIENT_USERS'), 'completed' => false, 'active' => false, 'enabled' => true),
            Wizard::VIEW_SERVICE_CARERS => array('actionName' => Wizard::VIEW_SERVICE_CARERS, 'label' => Yii::t('texts', 'WIZARD_CLIENT_CARERS'), 'completed' => false, 'active' => false, 'enabled' => true),
            // Wizard::VIEW_SERVICE_ORDER => array('actionName' => Wizard::VIEW_SERVICE_ORDER, 'label' => Yii::t('texts', 'WIZARD_CLIENT_ORDER'), 'completed' => false, 'active' => false, 'enabled' => true),
            Wizard::VIEW_SERVICE_PAYMENT => array('actionName' => Wizard::VIEW_SERVICE_PAYMENT, 'label' => Yii::t('texts', 'WIZARD_CLIENT_PAYMENT'), 'completed' => false, 'active' => false, 'enabled' => true),
            Wizard::VIEW_SERVICE_CONFIRMATION => array('actionName' => Wizard::VIEW_SERVICE_CONFIRMATION, 'label' => Yii::t('texts', 'WIZARD_CLIENT_CONFIRMATION'), 'completed' => false, 'active' => false, 'enabled' => true),
        );

        self::init($steps, $controllerName);
    }

    /**
     * Init array for client wizard2
     */
    public static function initStepArrayClientWzd2($controllerName) {

        $steps = array(
            Wizard::VIEW_QUOTE => array('actionName' => Wizard::VIEW_QUOTE, 'label' => Yii::t('texts', 'WIZARD_CLIENT_QUOTE'), 'completed' => false, 'active' => true, 'enabled' => true),
            Wizard::VIEW_SERVICE_LOCATION => array('actionName' => Wizard::VIEW_SERVICE_LOCATION, 'label' => Yii::t('texts', 'WIZARD_CLIENT_2_LOCATION'), 'completed' => false, 'active' => false, 'enabled' => true),
            Wizard::VIEW_SERVICE_USER => array('actionName' => Wizard::VIEW_SERVICE_USER, 'label' => Yii::t('texts', 'WIZARD_CLIENT_2_USERS'), 'completed' => false, 'active' => false, 'enabled' => true),
            Wizard::VIEW_SERVICE_CARERS => array('actionName' => Wizard::VIEW_SERVICE_CARERS, 'label' => Yii::t('texts', 'WIZARD_CLIENT_2_CARERS'), 'completed' => false, 'active' => false, 'enabled' => true),
            // Wizard::VIEW_SERVICE_ORDER => array('actionName' => Wizard::VIEW_SERVICE_ORDER, 'label' => Yii::t('texts', 'WIZARD_CLIENT_2_ORDER'), 'completed' => false, 'active' => false, 'enabled' => true),
            Wizard::VIEW_SERVICE_PAYMENT => array('actionName' => Wizard::VIEW_SERVICE_PAYMENT, 'label' => Yii::t('texts', 'WIZARD_CLIENT_2_PAYMENT'), 'completed' => false, 'active' => false, 'enabled' => true),
            Wizard::VIEW_SERVICE_CONFIRMATION => array('actionName' => Wizard::VIEW_SERVICE_CONFIRMATION, 'label' => Yii::t('texts', 'WIZARD_CLIENT_2_CONFIRMATION'), 'completed' => false, 'active' => false, 'enabled' => true),
        );

        self::init($steps, $controllerName);
    }

    public static function deleteStep($stepName) {

        $steps = Yii::app()->session['steps'];

        unset($steps[$stepName]);

        Yii::app()->session['steps'] = $steps;
    }

    public static function setStepCompleted($stepName) {

        $steps = Yii::app()->session['steps'];

        $steps[$stepName]['completed'] = true;

        Yii::app()->session['steps'] = $steps;
    }

    public static function changeLabel($stepName, $label) {

        $steps = Yii::app()->session['steps'];

        $steps[$stepName]['label'] = $label;

        Yii::app()->session['steps'] = $steps;
    }

    public static function setStepActive($stepName) {

        //if entered step does not have its previous step completed
        //redirect to first page

        $steps = Yii::app()->session['steps'];

        if (isset($steps)) {

            foreach ($steps as &$step) {

                if ($step['actionName'] == $stepName) {
                    $step['active'] = true;
                } else {
                    $step['active'] = false;
                }
            }

            Yii::app()->session['steps'] = $steps;
        }
    }

    public static function getStepActive() {

        //if entered step does not have its previous step completed
        //redirect to first page

        $steps = Yii::app()->session['steps'];

        if (isset($steps)) {

            foreach ($steps as &$step) {

                if ($step['active'] == true) {
                    return $step['actionName'];
                }
            }
        }
    }

    public static function getStepCompleted($stepName) {

        $steps = Yii::app()->session['steps'];

        return ($steps[$stepName]['completed']);
    }

    public static function getPreviousStepCompleted($stepName) {

        $steps = Yii::app()->session['steps'];

        if (isset($steps)) {

            $keys = array_keys($steps);

            $key = array_search($stepName, $keys);

            if ($key == 0) { //first step : no previous step so always true
                return true;
            } else {

                $previousStep = $steps[$keys[$key - 1]];

                return $previousStep['completed'];
            }
        } else {
            return false;
        }
    }

    private static function getStepIndex($stepName) {

        $steps = Yii::app()->session['steps'];

        $keys = array_keys($steps);

        return array_search($stepName, $keys);
    }

    public static function getCompletedStepIndex($stepName) {

        $steps = Yii::app()->session['steps'];

        $keys = array_keys($steps);

        return array_search($stepName, $keys) + 1;
    }

    public static function getNextStep($stepName) {

        $steps = Yii::app()->session['steps'];

        $currentStepIndex = self::getStepIndex($stepName);

        $newArray = array_keys($steps);
        $stepName = $newArray[$currentStepIndex + 1];

        return $stepName;
    }

    public static function getPreviousStep($stepName) {

        $steps = Yii::app()->session['steps'];

        $currentStepIndex = self::getStepIndex($stepName);

        $newArray = array_keys($steps);
        $stepName = $newArray[$currentStepIndex - 1];

        return $stepName;
    }

    public static function isStepAllCompleted() {

        if (self::isStarted()) {

            $steps = Yii::app()->session['steps'];

            $allCompleted = true;

            foreach ($steps as $step) {

                if ($step['completed'] == false) {

                    $allCompleted = false;
                    break;
                }
            }

            return $allCompleted;
        } else {
            return false;
        }
    }

    public static function isStepAfter($currentStepName, $stepName) {

        $steps = Yii::app()->session['steps'];

        if (self::getStepIndex($currentStepName) < self::getStepIndex($stepName))
            return true;
        else {
            return false;
        }
    }

    public static function isStepBefore($currentStepName, $stepName) {

        $steps = Yii::app()->session['steps'];

        if (self::getStepIndex($currentStepName) > self::getStepIndex($stepName))
            return true;
        else {
            return false;
        }
    }

    public static function isStarted() {

        return isset(Yii::app()->session['steps']);
    }

    public static function clearWizardSteps() {

        unset(Yii::app()->session['steps']);
    }

    public static function generateWizard() {

        $steps = Yii::app()->session['steps'];

        if (isset($steps)) {
            $result = '<div class="rc-container-button">' . "\n";
            $i = 0;

            $disabled = false;
            $ARROW_SIGN = '<span class="rc-arrow">&#32;&#32;&#62;&#32;&#32;</span>';

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
                        $cssClass = 'rc-wzd-active button';
                    } elseif ($step['completed'] == true) {
                        $cssClass = 'rc-wzd-previous button';
                    } else {
                        $cssClass = 'rc-wzd-button button disabled';
                        $disabled = true;
                    }
                } elseif ($i == $size - 1) {
                    $arrowSign = null;
                    if ($step['active'] == true) {
                        $cssClass = 'rc-wzd-active button';
                    } elseif ($step['completed'] == true) {
                        $cssClass = 'rc-wzd-previous button';
                    } else {
                        $cssClass = 'rc-wzd-button button disabled';
                        $disabled = true;
                    }
                } else {
                    $arrowSign = $ARROW_SIGN;
                    if ($step['active'] == true) {
                        $cssClass = 'rc-wzd-active button';
                    } elseif ($step['completed'] == true) {
                        $cssClass = 'rc-wzd-previous button';
                    } else {
                        $cssClass = 'rc-wzd-button button disabled';
                        $disabled = true;
                    }
                }

                $htmlOptions = array('class' => $cssClass . ' rc-180px-width', 'submit' => array(Yii::app()->session['controller'] . '/' . Wizard::getStepActive(), 'toView' => $step['actionName']));

                //SPECIAL CASES STYLING
                //SPECIAL CASE FOR SIGN IN
                if ($step['enabled'] == false) {

                    //if step is active create button
                    if ($step['active'] == true) {

                        $button = CHtml::submitButton($step['label'], $htmlOptions) . $arrowSign;
                    } else {

                        //greyed all the time when not active
                        $button = '<input type="submit" class="button disabled" value="' . $step['label'] . '">' . $arrowSign;
                    }
                }
                //when the button is disabled (step not reached) or the wizard is finished
                elseif ($disabled || Wizard::isStepAllCompleted() == true) {

                    if ($i == (count($steps) - 1)) { //last step
                        if (Wizard::isStepAllCompleted()) {
                            //Display normally the last button (Confirmation)
                            $button = CHtml::submitButton($step['label'], $htmlOptions) . $arrowSign;
                        } else {
                            $button = '<input type="submit" class="button disabled" value="' . $step['label'] . '">' . $arrowSign;
                        }
                    } else {
                        //grey all other buttons
                        $button = '<input type="submit" class="button disabled" value="' . $step['label'] . '">' . $arrowSign;
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

    public static function handleWizardStepCompleted($client, $view) {

        $currentStepIndex = Wizard::getCompletedStepIndex($view);
        $wizardCompleted = $client->wizard_completed;

        if ($currentStepIndex > $wizardCompleted) {
            $client->wizard_completed = $currentStepIndex;
            $client->save(false);
        }
    }

    public static function handleClientSecurity2() {

        //check if at least 3 carers and a valid quote
        $selectedCarers = count(Session::getSelectedCarers());
        $quote = Session::getSelectedValidQuote();

        if ($selectedCarers < BusinessRules::getCarerSelectionMinimumSelected() || !isset($quote)) {

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

    public static function handleClientSecurity($view) {

        $wizardStarted = Wizard::isStarted();
        $wizardPreviousStepCompleted = Wizard::getPreviousStepCompleted($view);
        $wizardAllStepsCompleted = Wizard::isStepAllCompleted();

        //debug
        $steps = Yii::app()->session['steps'];

        if (!$wizardStarted || $wizardPreviousStepCompleted == false || ( $wizardAllStepsCompleted && $view != Wizard::VIEW_SERVICE_CONFIRMATION)) {
            //if user enters ULR in browser wihtout starting the wizard
            throw new CHttpException(403, Yii::t('texts', 'FLASH_ERROR_403_YOU_ARE_NOT_AUTHORISED_TO_VIEW_THIS_PAGE'));
        }
        //if user enters ULR in browser wihtout starting the wizard
//        elseif (!isset(Yii::app()->user->id) && $view != Wizard::VIEW_QUOTE) {
//
//            $this->redirect(array(Wizard::VIEW_DETAILS));
//        }
//        //if wizard not started or future step entered: the user entered the URL manually
//        if (!Wizard::isStarted()) {
//
//            throw new CHttpException(403, Yii::t('texts', 'FLASH_ERROR_403_YOU_ARE_NOT_AUTHORISED_TO_VIEW_THIS_PAGE'));
//        } elseif (Wizard::getPreviousStepCompleted($view) == false) {
//
//            throw new CHttpException(403, YYii::t('texts', 'FLASH_ERROR_403_YOU_ARE_NOT_AUTHORISED_TO_VIEW_THIS_PAGE'));
//        } 
//        elseif (Wizard::getStepCompleted(Wizard::VIEW_SERVICE_CONFIRMATION)) {
//
//            //user has already completed the wizard
//            $this->redirect(array('site/index'));
//        }
    }

}

?>