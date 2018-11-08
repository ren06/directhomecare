<?php

class m131028_210711_create_view_client_carer_relation_carer extends CDbMigration {

    public function up() {

        $sql = "CREATE VIEW view_client_carer_relation_carer AS
            SELECT    `a`.`id` AS `id`
                    , `a`.`active` AS `active`
                    , `a`.`score` AS `score`
                    , `a`.`work_with_male` AS `work_with_male`
                    , `a`.`work_with_female` AS `work_with_female`
                    , `a`.`nationality` AS `nationality`
                    , `a`.`live_in` AS `live_in`
                    , `a`.`hourly_work` AS `hourly_work`
                    , `a`.`gender` AS `gender`
                    , `b`.`relation` AS `relation`
                    , `b`.`id_client` AS `id_client`
            FROM
              (`tbl_carer` `a`
            LEFT JOIN `tbl_client_carer_relation` `b`
            ON ((`a`.`id` = `b`.`id_carer`)))";

        $this->execute($sql);
    }

    public function down() {

        $sql = "DROP VIEW view_client_carer_relation_carer";

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