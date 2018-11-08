<?php

class m131001_142801_create_tbl_rating_article extends CDbMigration {

    public function up() {
        
        $this->createTable('tbl_rating_article', array(
            'page_name' => 'varchar(30)',            
            'number_entries' => 'int(5) NOT NULL',
            'average' => 'float(3,1) NOT NULL',
        ));
        
        $this->addPrimaryKey('PK', 'tbl_rating_article', 'page_name');
    }

    public function down() {

        $this->dropTable('tbl_rating_article');
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