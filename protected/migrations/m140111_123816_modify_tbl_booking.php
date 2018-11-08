<?php

class m140111_123816_modify_tbl_booking extends CDbMigration {

    public function up() {
        $this->addColumn('tbl_booking', 'subtype', "INT(2) NOT NULL DEFAULT 0 COMMENT '0: ONE-OFF, 1: 2TO14, 2: REGULARLY'");
    }

    public function down() {
        $this->dropColumn('tbl_booking', 'subtype');
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