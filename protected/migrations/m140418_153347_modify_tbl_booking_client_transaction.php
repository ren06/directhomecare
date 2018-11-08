<?php

/**
 * Important migration: 
 * - credit card id in a booking in optional (will only be used for recurring)
 * - get rid of id_mission_payment in a mission
 * - add booking id and mission id in tbl_client_transaction
 * 
 *  still have to use mission payment for legacy and recurring
 */
class m140418_153347_modify_tbl_booking_client_transaction extends CDbMigration {

    public function up() {

        $transaction = $this->getDbConnection()->beginTransaction();
        try {
            $this->addColumn('tbl_client_transaction', 'card_last_digits', 'CHAR(4) DEFAULT NULL AFTER voucher_balance');
            $this->addColumn('tbl_client_transaction', 'bank_reference', 'VARCHAR(50) DEFAULT NULL AFTER card_last_digits ');
            $this->addColumn('tbl_client_transaction', 'id_booking', 'INT(11) DEFAULT NULL AFTER id_mission_payment');
            $this->addColumn('tbl_client_transaction', 'total', 'FLOAT(7,2) NOT NULL AFTER type');

            //$this->execute("ALTER tbl_client_transaction MODIFY COLUMN id_mission AFTER id_booking");

            $this->addForeignKey('FK_tbl_client_transaction_tbl_booking_id', 'tbl_client_transaction', 'id_booking', 'tbl_booking', 'id', 'RESTRICT', 'RESTRICT');
            $this->addForeignKey('FK_tbl_client_transaction_tbl_mission_id', 'tbl_client_transaction', 'id_mission', 'tbl_mission', 'id', 'RESTRICT', 'RESTRICT');

            $this->dropForeignKey('FK_tbl_mission_payment_tbl_credit_card_id', 'tbl_mission_payment');

            $this->alterColumn('tbl_mission_payment', 'id_credit_card', 'INT(11) DEFAULT NULL');
            $this->alterColumn('tbl_client_transaction', 'id_mission_payment', 'INT(11) DEFAULT NULL');
            $this->alterColumn('tbl_booking', 'id_credit_card', 'INT(11) DEFAULT NULL');
            $this->alterColumn('tbl_mission', 'id_mission_payment', 'INT(11) DEFAULT NULL');

            $transaction->commit();
        } catch (Exception $e) {
            echo "Exception: " . $e->getMessage() . "\n";
            $transaction->rollback();
            return false;
        }
    }

    public function down() {

        $this->dropForeignKey('FK_tbl_client_transaction_tbl_booking_id', 'tbl_client_transaction');
        $this->dropForeignKey('FK_tbl_client_transaction_tbl_mission_id', 'tbl_client_transaction');

        $this->dropColumn('tbl_client_transaction', 'card_last_digits');
        $this->dropColumn('tbl_client_transaction', 'bank_reference');
        $this->dropColumn('tbl_client_transaction', 'id_booking');
        $this->dropColumn('tbl_client_transaction', 'total');

        $this->alterColumn('tbl_mission_payment', 'id_credit_card', 'INT(11) NOT NULL');
        $this->alterColumn('tbl_client_transaction', 'id_mission_payment', 'INT(11) NOT NULL');
        $this->alterColumn('tbl_booking', 'id_credit_card', 'INT(11) NOT NULL');
        $this->alterColumn('tbl_mission', 'id_mission_payment', 'INT(11) NOT NULL');
    }

}