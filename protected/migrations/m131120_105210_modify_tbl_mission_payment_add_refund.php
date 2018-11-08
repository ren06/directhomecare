<?php

class m131120_105210_modify_tbl_mission_payment_add_refund extends CDbMigration {

    public function up() {
        $this->addColumn('tbl_mission_payment', 'refund', 'INT(1) NOT NULL DEFAULT 0');
    }

    public function down() {
        $this->dropColumn('tbl_mission_payment', 'refund');
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