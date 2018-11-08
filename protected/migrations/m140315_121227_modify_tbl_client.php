<?php

class m140315_121227_modify_tbl_client extends CDbMigration
{
    public function up() {

        $this->addColumn('tbl_client', 'no_newsletter', 'INT(1) NOT NULL DEFAULT 0');
    }

    public function down() {
        $this->dropColumn('tbl_client', 'no_newsletter');
    }
}