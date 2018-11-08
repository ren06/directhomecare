<?php

class m131106_144510_update_conditions_values extends CDbMigration {

    public function up() {

        $sql = "        
        INSERT INTO tbl_condition VALUES 
  (45, 'administer_drugs', 3, 10),
  (43, 'carer_car', 3, 8),
  (30, 'companionship', 3, 1),
  (53, 'dementia', 5, 4),
  (48, 'dependant', 4, 3),
  (49, 'disabled', 4, 4),
  (46, 'good_shape', 4, 1),
  (31, 'home_cleaning', 3, 2),
  (32, 'meal_preparation', 3, 3),
  (44, 'personal_care', 3, 9),
  (40, 'pet', 3, 5),
  (41, 'public_transport', 3, 6),
  (52, 'severe_memory_loss', 5, 3),
  (51, 'slight_memory_loss', 5, 2),
  (50, 'sound_minded', 5, 1),
  (42, 'user_car', 3, 7),
  (39, 'walking', 3, 4),
  (47, 'walking_difficulties', 4, 2);
";

        $this->execute($sql);
    }

    public function down() {

        $sql = "        
        DELETE FROM tbl_condition WHERE id IN ( 
          (45,43,30,53,48,49,46,31,32,44,40,41,52,50,42,39,47);";

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
