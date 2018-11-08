<?php

class m140315_183112_modify_tbl_clientprospect_carer extends CDbMigration {

    public function up() {

        $this->addColumn('tbl_client_prospect', 'no_newsletter', 'INT(1) NOT NULL DEFAULT 0');
        $this->addColumn('tbl_carer', 'no_newsletter', 'INT(1) NOT NULL DEFAULT 0');
    }

    public function down() {
        $this->dropColumn('tbl_client_prospect', 'no_newsletter');
        $this->addColumn('tbl_carer', 'no_newsletter', 'INT(1) NOT NULL DEFAULT 0');
    }

}