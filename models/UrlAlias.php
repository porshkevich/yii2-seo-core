<?php

/*
 *  @link https://github.com/porshkevich/yii2-seo-core
 *  @copyright Copyright (c) 2015 NeoSonic <neosonic@inbox.ru>
 *  @license http://opensource.org/licenses/MIT
 */

namespace porshkevich\seocore\models;

use Yii;

/**
 * This is the model class for table "sc_url_alias".
 *
 * @author NeoSonic <neosonic@inbox.ru>
 *
 * @property integer $id
 * @property string $url
 * @property string $route
 * @property string $params
 * @property boolean $status
 */
class UrlAlias extends \yii\db\ActiveRecord {

	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return '{{%sc_url_alias}}';
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[['status'], 'boolean'],
			[['status'], 'default', 'value' => 1],
			[['url', 'route'], 'string', 'max' => 255],
			[['params'], 'safe'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [
			'id'	 => 'ID',
			'url'	 => 'Url',
			'route'	 => 'Route',
			'params' => 'Params',
			'status' => 'Status',
		];
	}

	/**
	 * @inheritdoc
	 */
	public function beforeSave($insert) {
		if (parent::beforeSave($insert)) {

			if (is_array($this->params))
				$this->params = http_build_query($this->params);
			return true;
		} else
			return false;
	}

	/**
	 *
	 * @param string $route
	 * @param array $params
	 * @return static
	 */
	public static function findByRoute($route, $params) {
		$routes = static::find()->where(['route' => $route])->orderBy('params')->all();

		function array_compare_assoc($needle, $array) {
			foreach ($needle as $key => $value)
				if (!isset($array[$key]) || $array[$key] !== $value)
					return false;
			return true;
		}

		if (count($routes) == 1) {
			$route = reset($routes);
			parse_str($route->params, $route->params);
			if (!$params || (!$route->params || array_compare_assoc($route->params, $params))) {
				return $route;
			}
		} elseif (count($routes)) {
			if (!$params && !(reset($routes)->params))
				return reset($routes);
			elseif ($params) {
				foreach ($routes as $route) {
					parse_str($route->params, $route->params);
				}
				usort($routes, function($a, $b) {
					return count($b->params) - count($a->params);
				});
				foreach ($routes as $route) {
					if (array_compare_assoc($route->params, $params))
						return $route;
				}
			}
		}
	}

	/**
	 *
	 * @param string $url
	 * @return static
	 */
	public static function findByUrl($url) {
		return static::findByCondition(['url' => $url]);
	}

}
