<?php

class m150130_162520_create_tbl_job_conversation_message extends CDbMigration {

    public function up() {

        $sql = "
            CREATE TABLE directhomecare.tbl_job(
            id INT(11) NOT NULL AUTO_INCREMENT,
            id_client INT(11) NOT NULL,
            post_code VARCHAR(10) NOT NULL,
            gender_carer INT(1) NOT NULL COMMENT '1 = female, 2 = male, 3 = both',
            who_for INT(1) NOT NULL COMMENT '0 =other, 1=myself',
            language VARCHAR(20) NOT NULL,
            first_name_user VARCHAR(50) DEFAULT NULL,
            last_name_user VARCHAR(50) DEFAULT NULL,
            gender_user INT(1) NOT NULL,
            age_group INT(1) NOT NULL COMMENT 'children = 0, young adult = 1, adult = 2, elderly =3 ',
            mental_health INT(2) NOT NULL,
            physical_health INT(2) NOT NULL,
            message VARCHAR(255) NOT NULL,
            status INT(1) NOT NULL COMMENT '0 = OK, 1 = assigned, 2 = cancelled',
            created DATETIME NOT NULL,
            modified DATETIME NOT NULL,
            PRIMARY KEY (id),
            CONSTRAINT FK_tbl_job_tbl_client_id FOREIGN KEY (id_client)
            REFERENCES directhomecare.tbl_client (id) ON DELETE RESTRICT ON UPDATE RESTRICT
          )
          ENGINE = INNODB
          AUTO_INCREMENT = 29
          AVG_ROW_LENGTH = 682
          CHARACTER SET utf8
          COLLATE utf8_general_ci;";

        $this->execute($sql);

        $sql = "CREATE TABLE directhomecare.tbl_conversation(
                id INT(11) NOT NULL AUTO_INCREMENT,
                id_carer INT(11) NOT NULL,
                id_client INT(11) NOT NULL,
                archived INT(1) NOT NULL,
                created DATETIME NOT NULL,
                modified DATETIME NOT NULL,
                PRIMARY KEY (id),
                INDEX FK_tbl_message_carer_tbl_carer_id (id_carer),
                INDEX FK_tbl_message_carer_tbl_client_id (id_client)
              )
              ENGINE = INNODB
              AUTO_INCREMENT = 221
              AVG_ROW_LENGTH = 74
              CHARACTER SET utf8
              COLLATE utf8_general_ci;";

        $this->execute($sql);

        $sql = "CREATE TABLE directhomecare.tbl_message(
            id INT(11) NOT NULL AUTO_INCREMENT,
            id_conversation INT(11) NOT NULL,
            type INT(1) NOT NULL COMMENT '0: text message, 1: job posting, 2: booking, 4:admin message',
            author INT(1) NOT NULL COMMENT '1: client 2: carer: 3:admin',
            is_read INT(1) NOT NULL COMMENT '0: visible 1: hidden',
            visible_by INT(1) NOT NULL COMMENT '1: carer 2: client : 4: all',
            archived INT(1) NOT NULL,
            id_job INT(11) DEFAULT NULL,
            message VARCHAR(255) DEFAULT NULL,
            id_booking INT(11) DEFAULT NULL,
            created DATETIME NOT NULL,
            modified DATETIME NOT NULL,
            PRIMARY KEY (id),
            CONSTRAINT FK_tbl_message_tbl_booking_id FOREIGN KEY (id_booking)
            REFERENCES directhomecare.tbl_booking (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
            CONSTRAINT FK_tbl_message_tbl_conversation_id FOREIGN KEY (id_conversation)
            REFERENCES directhomecare.tbl_conversation (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
            CONSTRAINT FK_tbl_message_tbl_job_id FOREIGN KEY (id_job)
            REFERENCES directhomecare.tbl_job (id) ON DELETE RESTRICT ON UPDATE RESTRICT
          )
          ENGINE = INNODB
          AUTO_INCREMENT = 286
          AVG_ROW_LENGTH = 277
          CHARACTER SET utf8
          COLLATE utf8_general_ci;";

        $this->execute($sql);

        $sql = "CREATE TABLE directhomecare.tbl_job_activity(
                id INT(11) NOT NULL AUTO_INCREMENT,
                id_job INT(11) NOT NULL,
                activity INT(2) NOT NULL,
                PRIMARY KEY (id),
                CONSTRAINT FK_tbl_job_activity_tbl_job_id FOREIGN KEY (id_job)
                REFERENCES directhomecare.tbl_job (id) ON DELETE RESTRICT ON UPDATE RESTRICT
              )
              ENGINE = INNODB
              AUTO_INCREMENT = 1
              CHARACTER SET utf8
              COLLATE utf8_general_ci;";

        $this->execute($sql);
    }

    public function down() {

        return false;
    }

}