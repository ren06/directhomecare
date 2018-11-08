<?php

class m131228_105721_modify_tbl_carer extends CDbMigration {

    public function up() {

        $this->addColumn('tbl_carer', 'overall_rating', 'INT(7) NOT NULL DEFAULT 0');
        $this->renameColumn('tbl_carer', 'score', 'overall_score');
    }

    public function down() {
        $this->dropColumn('tbl_carer', 'overall_rating');
        $this->renameColumn('tbl_carer', 'overall_score', 'score');
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