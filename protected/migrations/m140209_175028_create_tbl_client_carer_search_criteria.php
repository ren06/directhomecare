<?php

class m140209_175028_create_tbl_client_carer_search_criteria extends CDbMigration {

    public function up() {
        $sql = 'CREATE TABLE tbl_client_carer_search_criteria(
                id INT(11) NOT NULL AUTO_INCREMENT,
                id_client INT(11) NOT NULL,
                criteria_name VARCHAR(50) NOT NULL,
                criteria_value VARCHAR(50) NOT NULL,
                PRIMARY KEY (id),
                CONSTRAINT FK_tbl_client_search_tbl_client_id FOREIGN KEY (id_client)
                REFERENCES directhomecare.tbl_client (id) ON DELETE RESTRICT ON UPDATE RESTRICT
              )
              ENGINE = INNODB
              AUTO_INCREMENT = 1
              CHARACTER SET utf8
              COLLATE utf8_general_ci;';

        $this->execute($sql);
    }

    public function down() {

        $sql = "DROP TABLE tbl_client_carer_search_criteria";

        $this->execute($sql);
    }

// Use safeUp/safeDown to do migration with transaction
    public function safeUp() {
        
    }

}