<?php

/*
 *  @link https://github.com/porshkevich/yii2-seo-core
 *  @copyright Copyright (c) 2015 NeoSonic <neosonic@inbox.ru>
 *  @license http://opensource.org/licenses/MIT
 */

namespace porshkevich\seocore\models;

use Yii;

/**
 * This is the model class for table "sc_url_meta".
 *
 * @author NeoSonic <neosonic@inbox.ru>
 *
 * @property integer $id
 * @property string $url
 * @property string $title
 * @property string $keywords
 * @property string $description
 * @property boolean $status
 */
class UrlMeta extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sc_url_meta}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status'], 'boolean'],
			[['status'], 'default', 'value'=>1],
            [['url', 'title', 'keywords'], 'string', 'max' => 255],
			[['description'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'Url',
            'title' => 'Title',
            'keywords' => 'Keywords',
            'description' => 'Description',
            'status' => 'Status',
        ];
    }

	/**
	 *
	 * @param string $url
	 * @return static
	 */
	public static function findByUrl($url) {
		return static::findByCondition(['url'=>$url]);
	}
}
