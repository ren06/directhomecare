<?php

class m140215_113409_create_tbl_postcode_coordinate extends CDbMigration {

    public function up() {
        $sql = "CREATE TABLE tbl_postcode_coordinate(
                post_code VARCHAR(10) NOT NULL,
                longitude DOUBLE NOT NULL,
                latitude DOUBLE NOT NULL,
                PRIMARY KEY (post_code)
              )
              ENGINE = INNODB
              CHARACTER SET utf8
              COLLATE utf8_general_ci;";
        $this->execute($sql);
    }

    public function down() {

        $sql = "DROP TABLE tbl_postcode_coordinate";

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