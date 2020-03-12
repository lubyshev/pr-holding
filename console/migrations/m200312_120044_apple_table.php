<?php /** @noinspection PhpClassNamingConventionInspection */
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
                'id'                   => $this->primaryKey(),
                'username'             => $this->string()->notNull()->unique(),
                'auth_key'             => $this->string(32)->notNull(),
                'password_hash'        => $this->string()->notNull(),
                'password_reset_token' => $this->string()->unique(),
                'email'                => $this->string()->notNull()->unique(),

                'status'     => $this->smallInteger()->notNull()->defaultValue(10),
                'created_at' => $this->integer()->notNull(),
                'updated_at' => $this->integer()->notNull(),
            ], $tableOptions);
        } else {
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
