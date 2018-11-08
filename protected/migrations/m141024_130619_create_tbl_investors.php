<?php

class m141024_130619_create_tbl_investors extends CDbMigration
{
    public function up() {
        $sql = "CREATE TABLE tbl_investors(
                email VARCHAR(60) NOT NULL,
                sent INT(1) NOT NULL DEFAULT 0,
                PRIMARY KEY (email)
              )
              ENGINE = INNODB
              CHARACTER SET utf8
              COLLATE utf8_general_ci;";
        $this->execute($sql);
    }

    public function down() {

        $sql = "DROP TABLE tbl_investors";

        $this->execute($sql);
    }

}