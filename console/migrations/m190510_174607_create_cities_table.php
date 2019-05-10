<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cities}}`.
 */
class m190510_174607_create_cities_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cities}}', [
            'id' => $this->primaryKey()->unsigned(),
            'lft' => $this->smallInteger()->notNull()->unsigned(),
            'rgt' => $this->smallInteger()->notNull()->unsigned(),
            'depth' => $this->tinyInteger()->notNull()->unsigned(),
            'parent_id' => $this->smallInteger()->notNull()->unsigned(),
            'active' => $this->tinyInteger()->notNull()->unsigned(),
            'name' => $this->string()->notNull()->unique(),
        ]);
        $this->insert('{{%cities}}', [
            'lft' => 1,
            'rgt' => 2,
            'depth' => 0,
            'parent_id' => 0,
            'active' => 1,
            'name' => 'Города'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%cities}}');
    }
}
