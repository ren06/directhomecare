<?php

class m140330_133306_modify_tbl_client_prospect extends CDbMigration {

    public function up() {

        $this->alterColumn('tbl_client_carer_search_criteria', 'id_client', 'INT(11)');
        $this->addColumn('tbl_client_carer_search_criteria', 'id_session', 'VARCHAR(80)');
        
        $this->addForeignKey('FK_tbl_client_search_tbl_client_prospect_id_session', 'tbl_client_carer_search_criteria', 
                'id_session', 'tbl_client_prospect', 'sessionID', 'RESTRICT', 'RESTRICT');
    }

    public function down() {
       
        $this->dropColumn('tbl_client_carer_search_criteria', 'id_session');
    }

}