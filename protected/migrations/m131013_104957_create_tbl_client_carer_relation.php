<?php

class m131013_104957_create_tbl_client_carer_relation extends CDbMigration {

    public function up() {
//        $this->createTable('tbl_client_carer_relation', array(
//            'id' => "pk COMMENT 'Unique Id'",
//            'id_carer' => "INT(11) NOT NULL COMMENT 'Carer Id - foreign key'",
//            'id_client' => "INT(11) NOT NULL COMMENT 'Client Id - foreign key'",
//            'favourite' => "INT(1) NOT NULL DEFAULT 0 COMMENT '0 false, true 1, whether carer is favourite or client. If set to 1 team is set to 1 and not wanted to 0.'",
//        ));
//
//        $this->addForeignKey('FK_carer', 'tbl_client_carer_relation', 'id_carer', 'tbl_carer', 'id');
//        $this->addForeignKey('FK_client', 'tbl_client_carer_relation', 'id_client', 'tbl_client', 'id');


        $sql = "CREATE TABLE directhomecare.tbl_client_carer_relation(
             id INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique Id',
            id_carer INT(11) NOT NULL COMMENT 'Carer Id - foreign key',
            id_client INT(11) NOT NULL COMMENT 'Client Id - foreign key',
            favourite INT(1) NOT NULL DEFAULT 0 COMMENT '0 false, true 1, whether carer is favourite or client. If set to 1 team is set to 1 and not wanted to 0.',
            team INT(1) NOT NULL DEFAULT 0 COMMENT '0 false, true 1, whether carer is in client''s team. If set to 1 not wanted is set to 0.',
            not_wanted INT(1) NOT NULL DEFAULT 0 COMMENT '0 false, true 1, whether client does not want to see this carer. If set to 1 favourite an team must be 0',
            rating_punctuality INT(1) DEFAULT NULL COMMENT '0 to 5 rating, NULL no rating yet',
            rating_initialive INT(1) DEFAULT NULL COMMENT '0 to 5 rating, NULL no rating yet',
            rating_kindness INT(1) DEFAULT NULL COMMENT '0 to 5 rating, NULL no rating yet',
            rating_presentation INT(1) DEFAULT NULL COMMENT '0 to 5 rating, NULL no rating yet',
            rating_skills INT(1) DEFAULT NULL COMMENT '0 to 5 rating, NULL no rating yet',
            rating_overall INT(1) DEFAULT NULL COMMENT '0 to 5 rating, NULL no rating yet',
            PRIMARY KEY (id),
            CONSTRAINT FK_tbl_client_carer_relation_tbl_carer_id FOREIGN KEY (id_carer)
            REFERENCES directhomecare.tbl_carer (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
            CONSTRAINT FK_tbl_client_carer_relation_tbl_client_id FOREIGN KEY (id_client)
            REFERENCES directhomecare.tbl_client (id) ON DELETE RESTRICT ON UPDATE RESTRICT
          )";

        Yii::app()->db->createCommand($sql)->execute();
    }

    public function down() {
        $this->dropTable('tbl_client_carer_relation');
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