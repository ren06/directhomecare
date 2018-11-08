<?php

class m131215_105004_create_tbl_booking_carers extends CDbMigration {

    public function up() {

//        $sql = "CREATE TABLE tbl_booking_carers(
//                id INT(11) NOT NULL AUTO_INCREMENT,
//                id_booking INT(11) NOT NULL,
//                id_carer INT(11) NOT NULL,
//                relation INT(1) NOT NULL DEFAULT 0,
//                PRIMARY KEY (id),
//                CONSTRAINT FK_tbl_booking_carers_tbl_booking_id FOREIGN KEY (id_booking)
//                REFERENCES tbl_booking (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
//                CONSTRAINT FK_tbl_booking_carers_tbl_carer_id FOREIGN KEY (id_carer)
//                REFERENCES tbl_carer (id) ON DELETE RESTRICT ON UPDATE RESTRICT
//              )";

        $sql = "RENAME TABLE tbl_booking_carer TO tbl_booking_carers";
        
        $this->execute($sql);
    }

    public function down() {

         $sql = "RENAME TABLE tbl_booking_carers TO tbl_booking_carer";

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