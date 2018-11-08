<?php

class m131119_170940_modify_tbl_mission_payment_add_date extends CDbMigration {

    public function up() {
        
        $this->addColumn('tbl_mission_payment', 'transaction_date', 'DATETIME');
    }

    public function down() {
        $this->dropColumn('tbl_mission_payment', 'transaction_date');
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