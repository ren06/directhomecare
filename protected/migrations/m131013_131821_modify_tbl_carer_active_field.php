<?php

class m131013_131821_modify_tbl_carer_active_field extends CDbMigration {

    public function up() {
        $this->addColumn('tbl_carer', 'active', 'int(1) NOT NULL DEFAULT 0');
    }

    public function down() {
        $this->dropColumn('tbl_carer', 'active');
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