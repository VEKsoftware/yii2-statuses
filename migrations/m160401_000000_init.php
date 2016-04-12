<?php

use yii\db\Migration;

class m160401_000000_init extends Migration
{
    public function safeUp()
    {
        $this->createTable('statuses_doctypes', [
            'id' => $this->primaryKey(),
            'name' => $this->string(128)->notNull(),
            'symbolic_id' => $this->string(128)->notNull()->unique(),
        ], null);

        $this->createTable('statuses', [
            'id' => $this->primaryKey(),
            'doc_type' => $this->integer()->notNull(),
            'name' => $this->string(128)->notNull()->unique(),
            'description' => $this->string(512),
            'symbolic_id' => $this->string(128)->notNull()->unique(),
        ], null);

        $this->addForeignKey('statuses_doc_type_fkey', 'statuses', 'doc_type', 'statuses_doctypes', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('statuses_links', [
            'status_from' => $this->integer()->notNull(),
            'status_to' => $this->integer()->notNull(),
            'right_tag' => $this->string(128)->notNull(),
        ], null);

        $this->addForeignKey('statuses_links_statuses_id_fk1', 'statuses_links', 'status_from', 'statuses', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('statuses_links_statuses_id_fk2', 'statuses_links', 'status_to', 'statuses', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropTable('reports_conditions');
        $this->dropTable('reports');
    }
}