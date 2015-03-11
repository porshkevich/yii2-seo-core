<?php

/*
 *  @link https://github.com/porshkevich/yii2-seo-core
 *  @copyright Copyright (c) 2015 NeoSonic <neosonic@inbox.ru>
 *  @license http://opensource.org/licenses/MIT
 */

use yii\db\Schema;
use yii\db\Migration;

class m150306_173506_url_alias_table extends Migration
{

    public function safeUp()
    {
		$this->createTable('{{%sc_url_alias}}', [
			'id' => Schema::TYPE_PK,
			'url' => Schema::TYPE_STRING,
			'route' => Schema::TYPE_STRING,
			'params' => Schema::TYPE_TEXT,
			'status' => Schema::TYPE_BOOLEAN,
		]);
    }

    public function safeDown()
    {
		$this->dropTable($table);

		return true;
    }

}
