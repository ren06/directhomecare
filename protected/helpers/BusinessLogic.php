<?php

class BusinessLogic {
    const ID_LENGTH = 7;

    public static function getReference($data) {

        $id = $data->id;

        $length = strlen($id);
        $max = self::ID_LENGTH - 2 - $length;
        $result = $id;

        for ($i = 0; $i < $max; $i++) {

            $result = '0' . $result;
        }

        if ($data instanceof Booking || $data instanceof BookingHourly || $data instanceof BookingLiveIn) {
            $result = '10' . $result;
        } elseif ($data instanceof Mission) {
            $result = '20' . $result;
        } elseif ($data instanceof ClientTransaction) {
            $result = '11' . $result;
        } elseif ($data instanceof CarerTransaction) {
            $result = '21' . $result;
        }
        elseif($data instanceof MissionPayment){
            $result = '30' .$result;
        }

        return $result;
    }

}

?>