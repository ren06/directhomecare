<?php

class m140114_123258_modify_tbl_carer extends CDbMigration {

    public function up() {
        $this->addColumn('tbl_carer', 'deactivated', "INT(1) NOT NULL DEFAULT 0 COMMENT '0: activated 1: deactivated'");
        $this->addColumn('tbl_carer', 'no_job_alerts', "INT(1) NOT NULL DEFAULT 0 COMMENT '0: job alerts 1: no job alerts'");
    }

    public function down() {
        $this->dropColumn('tbl_carer', 'deactivated');
        $this->dropColumn('tbl_carer', 'no_job_alerts');
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