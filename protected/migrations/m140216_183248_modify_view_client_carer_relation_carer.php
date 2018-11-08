<?php

class m140216_183248_modify_view_client_carer_relation_carer extends CDbMigration {

    public function up() {
        $sql = "ALTER VIEW view_client_carer_relation_carer AS
                SELECT `a`.`id` AS `id`
                     , `a`.`active` AS `active`
                     , `a`.`deactivated` AS `deactivated`
                     , `a`.`overall_score` AS `overall_score`
                     , `a`.`overall_rating` AS `overall_rating`
                     , `a`.`work_with_male` AS `work_with_male`
                     , `a`.`work_with_female` AS `work_with_female`
                     , `a`.`nationality` AS `nationality`
                     , `a`.`live_in` AS `live_in`
                     , `a`.`hourly_work` AS `hourly_work`
                     , `a`.`gender` AS `gender`
                     , `a`.`hourly_work_radius` AS `hourly_work_radius`
                     , `b`.`relation` AS `relation`
                     , `b`.`id_client` AS `id_client`
                     , `c`.`longitude` AS `longitude`
                     , `c`.`latitude` AS `latitude`
                FROM
                  ((`tbl_carer` `a`
                LEFT JOIN `tbl_client_carer_relation` `b`
                ON ((`a`.`id` = `b`.`id_carer`)))
                LEFT JOIN `tbl_address` `c`
                ON ((`a`.`id_address` = `c`.`id`)));";

        $this->execute($sql);
    }

    public function down() {
        
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