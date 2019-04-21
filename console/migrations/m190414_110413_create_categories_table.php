<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%categories}}`.
 */
class m190414_110413_create_categories_table extends Migration {
    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        $this->createTable('{{%categories}}', [
            'id' => $this->primaryKey()->unsigned(),
            'lft' => $this->smallInteger()->notNull()->unsigned(),
            'rgt' => $this->smallInteger()->notNull()->unsigned(),
            'depth' => $this->tinyInteger()->notNull()->unsigned(),
            'parent_id' => $this->smallInteger()->notNull()->unsigned(),
            'active' => $this->tinyInteger()->defaultValue(1)->notNull()->unsigned(),
            'name' => $this->string()->notNull()->unique(),
        ]);
        $this->insert('{{%categories}}', [
            'lft' => 1,
            'rgt' => 2,
            'depth' => 0,
            'parent_id' => 0,
            'name' => 'Категории'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('{{%categories}}');
    }
}
