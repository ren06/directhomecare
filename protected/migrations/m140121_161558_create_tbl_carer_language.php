<?php

class m140121_161558_create_tbl_carer_language extends CDbMigration {

    public function up() {
        $sql = "CREATE TABLE tbl_carer_language(
                id INT(11) NOT NULL AUTO_INCREMENT,
                id_carer INT(11) NOT NULL,
                language VARCHAR(20) NOT NULL,
                level INT(1) NOT NULL,
                PRIMARY KEY (id),
                CONSTRAINT FK_tbl_carer_language_tbl_carer_id FOREIGN KEY (id_carer)
                REFERENCES directhomecare.tbl_carer (id) ON DELETE RESTRICT ON UPDATE RESTRICT)";

        $this->execute($sql);
    }

    public function down() {

        $sql = "DROP TABLE tbl_carer_language";

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