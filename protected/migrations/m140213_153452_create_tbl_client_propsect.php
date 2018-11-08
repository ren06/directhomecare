<?php

class m140213_153452_create_tbl_client_propsect extends CDbMigration {

    public function up() {
        $sql = 'CREATE TABLE tbl_client_prospect(
                sessionID VARCHAR(80) NOT NULL,
                email_address_step1 VARCHAR(80) NOT NULL,
                email_address_step2 VARCHAR(80) DEFAULT NULL,               
                created DATETIME NOT NULL,       
                PRIMARY KEY (sessionID)
              )
              ENGINE = INNODB
              CHARACTER SET utf8
              COLLATE utf8_general_ci;';

        $this->execute($sql);
    }

    public function down() {

        $sql = "DROP TABLE tbl_client_prospect";

        $this->execute($sql);
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