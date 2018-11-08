<?php

class m131027_092706_modify_tbl_carer_score_field extends CDbMigration {

    public function up() {
        $this->addColumn('tbl_carer', 'score', 'int(7) NOT NULL DEFAULT 0');
    }

    public function down() {
        $this->dropColumn('tbl_carer', 'score');
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