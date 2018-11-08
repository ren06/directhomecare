<?php

class m140325_184804_modify_tbl_postcode_coordinate extends CDbMigration {

    public function up() {
        $this->addColumn('tbl_postcode_coordinate', 'city', 'VARCHAR(50)');
    }

    public function down() {
        $this->dropColumn('tbl_postcode_coordinate', 'city');
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