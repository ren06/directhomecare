<?php

class m140412_134055_modify_tbl_client extends CDbMigration {

    public function up() {

        $this->addColumn('tbl_client', 'terms_conditions', 'INT(1) NOT NULL DEFAULT 0');
    }

    public function down() {
        $this->dropColumn('tbl_client', 'terms_conditions');
    }

}