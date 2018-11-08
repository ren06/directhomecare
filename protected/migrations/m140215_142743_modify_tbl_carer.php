<?php

class m140215_142743_modify_tbl_carer extends CDbMigration {

    public function up() {

        $this->addColumn('tbl_carer', 'show_homepage', 'INT(1) NOT NULL DEFAULT 0');
    }

    public function down() {
        $this->dropColumn('tbl_carer', 'show_homepage');
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