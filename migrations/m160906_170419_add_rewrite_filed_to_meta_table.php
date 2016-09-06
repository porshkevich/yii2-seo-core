<?php

use yii\db\Migration;

class m160906_170419_add_rewrite_filed_to_meta_table extends Migration
{
    public function safeUp()
    {
		$this->addColumn('{{%sc_url_meta}}', 'rewrite', yii\db\Schema::TYPE_BOOLEAN);
    }

    public function safeDown()
    {
		$this->dropColumn('{{%sc_url_meta}}', 'rewrite');
    }

}
