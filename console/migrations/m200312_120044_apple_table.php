<?php
/** @noinspection PhpClassNamingConventionInspection */

declare(strict_types=1);

use yii\db\Migration;

/**
 * Class m200312_120044_apple_table
 */
class m200312_120044_apple_table extends Migration
{
    public function up()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
            $this->createTable('{{%apple}}', [
                'id'         => $this->primaryKey()->unsigned(),
                'color'      => "ENUM('green', 'red', 'yellow') NOT NULL",
                'state'      => "ENUM('on_tree', 'on_ground', 'rotten') NOT NULL DEFAULT 'on_tree'",
                'created_at' => $this->integer()->notNull()
                    ->defaultValue(new \yii\db\Expression('NOW()')),
                'fall_at'    => $this->integer()->null(),
                'size'       => $this->decimal(7, 6)->notNull()->defaultValue(1),
            ], $tableOptions);
            $this->createIndex('created_at', '{{%apple}}', 'created_at');
        } else {
            // Из-за ENUM()
            throw new \yii\db\Exception("Реализовано только для MySql.");
        }
    }

    public function down()
    {
        if ($this->db->driverName === 'mysql') {
            $this->dropTable('{{%apple}}');
        }

        return true;
    }

}
