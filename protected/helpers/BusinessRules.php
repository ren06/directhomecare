<?php

/**
 * Expose business rules
 */
class BusinessRules {

    /**
     * Number of days to give feedback
     * @return int days
     */
    public static function getDelayToGiveFeedbackInDays() {

        return 3;
    }

    /**
     * Number of hours to give feedback
     * NOT USED AT THE MOMENT 28/01/2014
     * @return type hours
     */
    public static function getDelayToGiveFeedbackInHours() {

        return self::getDelayToGiveFeedbackInDays() * 24;
    }

    /**
     * Minimum delay to book a live in service. In Days
     */
    public static function getNewBookingDelayLiveInDays() {

        return 7;
    }

    /**
     * Minimum delay to book a hourly service. In Days
     */
    public static function getNewBookingDelay_Hourly_OneOff_InDays() {

        return 2;
    }

    /**
     * Minimum delay to book a hourly service - 2 TO 14. In Days
     */
    public static function getNewBookingDelay_Hourly_Fourteen_InDays() {

        return 2;
    }

    /**
     * Minimum delay to book a hourly service - REGULARLY. In Days
     */
    public static function getNewBookingDelay_Hourly_Regularly_InDays() {

        return 6;
    }

    /**
     * Minimum delay to book a hourly service ONE-OFF. In Hours
     */
    public static function getNewBookingDelay_Hourly_OneOff_InHours() {

        return self::getNewBookingDelay_Hourly_OneOff_InDays() * 24;
    }

    /**
     * Minimum delay to book a hourly service - 2 TO 14. In Hours
     */
    public static function getNewBookingDelay_Hourly_Fourteen_InHours() {

        return self::getNewBookingDelay_Hourly_Fourteen_InDays() * 24;
    }

    /**
     * Minimum delay to book a hourly service - REGULARLY. In Hours
     */
    public static function getNewBookingDelay_Hourly_Regularly_InHours() {

        return self::getNewBookingDelay_Hourly_Regularly_InDays() * 24;
    }

    /**
     *  Minimum delay to book a live in service. In Hours
     */
    public static function getNewBookingDelayLiveInHours() {

        return self::getNewBookingDelayLiveInDays() * 24;
    }

    /**
     * Minimum duration for a live in service
     * @return type days
     */
    public static function getNewBookingLiveInMinimumDurationInDays() {

        return 2;
    }

    /**
     * Minimum duration for a live in service - hours
     * @return type hours
     */
    public static function getNewBookingLiveInMinimumDurationInHours() {

        return self::getNewBookingLiveInMinimumLengthInDays() * 24;
    }

    /**
     * Minimum duration for a live in service - days
     * @return type hours
     */
    public static function getNewBookingLiveInMinimumLengthInDays() {

        return 2;
    }

    /**
     * 
     * Number of hours a mission is displayed to the carers in apply screen
     * 
     * @return int
     */
    public static function getNewMissionAdvertisedTimeInHours() {

        return 48;
    }

    /**
     * Number of hours a carer has to confirm a mission is was selected for - otherwise somebody else may get the job
     * 
     * @return int hours
     */
    public static function getTimeToConfirmSelectedMissionInHours() {

        return 24;
    }

    /**
     * Number of days in advance a client can cancel a live-in mission
     * 
     * @return int
     */
    public static function getClientAbortMissionLiveInTimeLimitInDays() {

        return 2;
    }

    /**
     * Number of days in advance (before beginning of the first mission) the cron job will take the client payment
     * 
     * @return int
     */
    public static function getCronPaymentInAdvanceInDays() {

        return 14;
    }

    /**
     * Number of hours in advance (before beginning of the first mission) the cron job will take the client payment
     * 
     * @return int
     */
    public static function getCronPaymentInAdvanceInHours() {

        return self::getCronPaymentInAdvanceInDays() * 24;
    }

    /**
     * Number of days before a credit card expires -> send email, 1st reminder
     * 
     * @return int
     */
    public static function getBeforeCreditCardExpiryBeforeEmail1InDays() {

        return 7;
    }

    /**
     * Number of days before a credit card expires -> send email, 2nd reminder
     * 
     * @return int
     */
    public static function getBeforeCreditCardExpiryBeforeEmail2InDays() {

        return 3;
    }

    /**
     * Number of days before a credit card expires -> send email, 3rd reminder
     * 
     * @return int
     */
    public static function getBeforeCreditCardExpiryBeforeEmail3InDays() {

        return 1;
    }

    /**
     * Number of missions = number of days inside a live in Payment
     * 
     * @return int
     */
    public static function getLiveInMissionPaymentNumberDays() {

        return 14;
    }

    /**
     * Minimum duration of a hourly mission - in hours
     * 
     * @return int
     */
    public static function getHourlyMissionMinimumDurationInHours() {

        return 1;
    }

    /**
     * Minimum duration of a hourly mission - in days
     * 
     * @return int
     */
    public static function getHourlyMissionMinimumDurationInMinutes() {

        return self::getHourlyMissionMinimumDurationInHours() * 60;
    }

    /**
     * Short notice threshold to cancel the mission and pay more
     * 
     * @return type
     */
    public static function getClientCancelServiceShortNoticeInHours() {

        return 7 * 24;
    }

    /**
     * Number of hours before mission after which it's not possible to cancel the service
     * @return int hours
     */
    public static function getClientCancelServiceBeforeStartInHours() {
        return 48;
    }

    /**
     * Percentage back for missions cancelled short notice
     * @return int
     */
    public static function getClientCancelServiceShortNoticeMoneyLostPercentage() {
        return 25;
    }

    /**
     * Percentage back for missions cancelled medium notice
     * @return int
     */
    public static function getClientCancelServiceMediumNoticeMoneyLostPercentage() {
        return 10;
    }

    /**
     * Percentage back for missions cancelled long notice
     * @return int
     */
    public static function getClientCancelServiceLongNoticeMoneyLostPercentage() {

        return 0;
    }

    /**
     * Number of hours before which a mission can be re-advertise - live in 
     * @return type
     */
    public static function getMissionReadvertiseLiveInMissionInHours() {

        return 6 * 24;
    }

    /**
     * Number of hours before mission start after which it's not possible to cancel a mission by admin
     * 
     * @return int hours
     */
    public static function getCancelByAdminHoursBeforeMissionStart() {

        return 48;
    }

    /**
     * 
     * A hourly work payment can comprise up to 14 days of hourly work
     * 
     * @return int days
     */
    public static function getHourlyBookingPaymentMaximumDays() {

        return 14;
    }

    /**
     * Used in tooltip 'it takes up to n days to find a carer'
     * @return int
     */
    public static function getDelayToFindCarerInDays() {

        return 5;
    }

    /**
     * Weighting for score when a carer has a favourite relation with client
     * @return int
     */
    public static function getCarerScoreFavouriteCoeff() {

        return 2;
    }

    /**
     * Weighting for score when a carer has a selected relation with client
     * @return int
     */
    public static function getCarerScoreTeamCoeff() {

        return 1;
    }

    /**
     * Weighting for score when a carer has a not wanted relation with client
     * @return int
     */
    public static function getCarerScoreNotWantedCoeff() {

        return -1;
    }

    /**
     * Returns the maximum of carers to show at once
     * @return int
     */
    public static function getCarerSelectionShowMoreCarersNumber() {

        return 10;
    }

    /**
     * Minimum number of carers that needs to be selected in step 'select carers'
     * 
     * @return int number of carers
     */
    public static function getCarerSelectionMinimumSelected() {

        return 1;
    }

    //In miles
    public static function getCarerMaximumWorkHourlyRadius() {

        return 40;
    }

    //In miles
    public static function getCarerMaximumWorkLiveInRadius() {

        return 250;
    }

    public static function getReferralAmount() {

        return 1;
    }

    public static function getRefereeAmount() {

        return 20;
    }

}

?>