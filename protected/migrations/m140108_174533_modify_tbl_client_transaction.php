<?php

class m140108_174533_modify_tbl_client_transaction extends CDbMigration {

    public function up() {

        $this->addColumn('tbl_client_transaction', 'id_mission', 'INT(11) DEFAULT NULL');
        $this->renameColumn('tbl_client_transaction', 'paid_cash', 'paid_card');
        $this->renameColumn('tbl_client_transaction', 'paid_credit', 'paid_voucher');
        $this->renameColumn('tbl_client_transaction', 'credit', 'refund_voucher');
        $this->renameColumn('tbl_client_transaction', 'credit_balance', 'voucher_balance');
        $this->renameColumn('tbl_client_transaction', 'reimbursed', 'refund_card');
    }

    public function down() {
        $this->dropColumn('tbl_client_transaction', 'overall_rating');

        $this->renameColumn('tbl_client_transaction', 'paid_card', 'paid_cash');
        $this->renameColumn('tbl_client_transaction', 'paid_voucher', 'paid_credit');
        $this->renameColumn('tbl_client_transaction', 'refund_voucher', 'credit');
        $this->renameColumn('tbl_client_transaction', 'voucher_balance', 'credit_balance');
        $this->renameColumn('tbl_client_transaction', 'refund_card', 'reimbursed');
    }

    /*
      // Use safeUp/safeDown to do migration with transaction
      public function safeUp()
      {
      }

      public function safeDown()
      {
      }
     */
}