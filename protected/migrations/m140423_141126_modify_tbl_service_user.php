<?php

class m140423_141126_modify_tbl_service_user extends CDbMigration {

    public function up() {

        $this->addColumn('tbl_service_user', 'data_type', "INT(1) NOT NULL DEFAULT 0 COMMENT 'master data 0, transactional data 1' AFTER id ");
       // $this->addColumn('tbl_service_user_condition', 'data_type', "INT(1) NOT NULL DEFAULT 0 COMMENT 'master data 0, transactional data 1' AFTER id_service_user ");
       // $this->addColumn('tbl_client_service_user', 'data_type', "INT(1) NOT NULL DEFAULT 0 COMMENT 'master data 0, transactional data 1' AFTER id_service_user ");
    }

    public function down() {
        $this->dropColumn('tbl_service_user', 'data_type');
//        $this->dropColumn('tbl_service_user_condition', 'data_type');
//        $this->dropColumn('tbl_client_service_user', 'data_type');
    }

}