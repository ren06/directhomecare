<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class EmailsNewsletters {

    // --- FOR CLIENT --
    public static function client_SomeCarersNearYou($email, $carers, $consoleCommand) {

        $subject = Yii::t('emails', 'CARER_FOR_YOU_SUBJECT');
        $emailTitle = Yii::t('emails', 'CARER_FOR_YOU');

        //get random carers

        $buttonBookUrl = $consoleCommand->createAbsoluteUrl('site/home');
        $emailContent = $consoleCommand->renderFile(Yii::app()->basePath . '/views/newsletter/_twoCarerProfiles.php',
                array('carers' => $carers, 'buttonBookUrl' => $buttonBookUrl, 'consoleCommand' => $consoleCommand), true, false);


        $unsubscribeLink = $consoleCommand->createAbsoluteUrl('site/unsubscribeNewsletter/user/' . Constants::USER_CLIENT);
        $emailButtonLink = $consoleCommand->createAbsoluteUrl('site/home');

        $emailButtonText = Yii::t('emails', 'EMAIL_BUTTON_MORE_INFORMATION');
        $params = array('emailTitle' => $emailTitle,
            'emailContent' => $emailContent,
            'emailButtonLink' => $emailButtonLink,
            'emailButtonText' => $emailButtonText,
            'unsubscribeLink' => $unsubscribeLink,
            'buttonBookUrl' => $buttonBookUrl,
        );
        $html = $consoleCommand->renderFile(Yii::app()->basePath . '/views/newsletter/newsletterTemplate.php', $params, true);

        $to = $email;
        return Emails::sendMail($html, $subject, $to, null, null);
    }

    // --- FOR CARERS ---
    /**
     * 
     * @param type $email carer email
     * @param type $missingDocuments array of missing documents
     * @return type
     */
    public static function carer_CompleteYourProfile($email, $firstName, $missingDocuments, $consoleCommand) {

        $subject = Yii::t('emails', 'APPLY_FOR_JOBS');
        $emailTitle = Yii::t('emails', 'COMPLETE_YOUR_PROFILE');

        //get random carers
        $emailContent = '<br /><br />' . $firstName . ', ' . Yii::t('emails', 'MISSING_DOCUMENTS') . '<br />';

        $emailContent .= '<ol>';

        foreach ($missingDocuments as $missingDocument) {

            $emailContent .= '<li>' . $missingDocument . '</li>';
        }

        $emailContent .= '</ol>';

        $emailContent .= 'You will start receiving jobs once your profile has been completed.';

        $buttonViewProfile = Yii::app()->params['server']['fullUrl'] . '/' . Emails::constructURL('carer', 'maintainProfile', $email);
        $unsubscribeLink = $consoleCommand->createAbsoluteUrl('site/unsubscribeNewsletter/user/' . Constants::USER_CARER);
        $emailButtonLink = $consoleCommand->createAbsoluteUrl('site/home');

        $emailButtonText = Yii::t('emails', 'EMAIL_BUTTON_VIEW_MY_PROFILE');
        $params = array('emailTitle' => $emailTitle,
            'emailContent' => $emailContent,
            'emailButtonLink' => $emailButtonLink,
            'emailButtonText' => $emailButtonText,
            'unsubscribeLink' => $unsubscribeLink,
            'buttonBookUrl' => $buttonViewProfile,
        );

        $html = $consoleCommand->renderFile(Yii::app()->basePath . '/views/newsletter/newsletterTemplate.php', $params, true);

        $to = $email;
        return Emails::sendMail($html, $subject, $to, null, null);
    }

}

?>
