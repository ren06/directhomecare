<?php

class m131031_190443_create_tbl_booking_carer extends CDbMigration {

    public function up() {

        $sql = "CREATE TABLE tbl_booking_carer(
                id INT(11) NOT NULL AUTO_INCREMENT,
                id_booking INT(11) NOT NULL,
                id_carer INT(11) NOT NULL,
                relation INT(1) NOT NULL,
                PRIMARY KEY (id),
                CONSTRAINT FK_tbl_booking_carer_tbl_booking_id FOREIGN KEY (id_booking)
                REFERENCES directhomecare.tbl_booking (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
                CONSTRAINT FK_tbl_booking_carer_tbl_carer_id FOREIGN KEY (id_carer)
                REFERENCES directhomecare.tbl_carer (id) ON DELETE RESTRICT ON UPDATE RESTRICT
              )";
        
        $this->execute($sql);
    }

    public function down() {

        $sql = "DROP VIEW tbl_booking_carer";

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