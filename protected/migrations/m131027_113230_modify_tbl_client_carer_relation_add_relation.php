<?php

class m131027_113230_modify_tbl_client_carer_relation_add_relation extends CDbMigration
{
    public function up() {
        $this->addColumn('tbl_client_carer_relation', 'relation', 'int(1) NOT NULL DEFAULT 0');
        $this->dropColumn('tbl_client_carer_relation', 'team');
        $this->dropColumn('tbl_client_carer_relation', 'favourite');
        $this->dropColumn('tbl_client_carer_relation', 'not_wanted');
    }

    public function down() {
        $this->dropColumn('tbl_client_carer_relation', 'relation');
        $this->addColumn('tbl_client_carer_relation', 'team', 'int(1) NOT NULL DEFAULT 0');
        $this->addColumn('tbl_client_carer_relation', 'favourite', 'int(1) NOT NULL DEFAULT 0');
        $this->addColumn('tbl_client_carer_relation', 'not_wanted', 'int(1) NOT NULL DEFAULT 0');
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