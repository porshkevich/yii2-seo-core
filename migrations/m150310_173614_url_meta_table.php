<?php

/*
 *  @link https://github.com/porshkevich/yii2-seo-core
 *  @copyright Copyright (c) 2015 NeoSonic <neosonic@inbox.ru>
 *  @license http://opensource.org/licenses/MIT
 */

use yii\db\Schema;
use yii\db\Migration;

class m150310_173614_url_meta_table extends Migration
{
    public function safeUp()
    {
		$this->createTable('{{%sc_url_meta}}', [
			'id' => Schema::TYPE_PK,
			'url' => Schema::TYPE_STRING,
			'title' => Schema::TYPE_STRING,
			'keywords' => Schema::TYPE_STRING,
			'description' => Schema::TYPE_TEXT,
			'status' => Schema::TYPE_BOOLEAN,
		]);
    }

    public function safeDown()
    {
		$this->dropTable('{{%sc_url_meta}}');

		return true;
    }

}
