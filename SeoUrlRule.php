<?php

/*
 *  @link https://github.com/porshkevich/yii2-seo-core
 *  @copyright Copyright (c) 2015 NeoSonic <neosonic@inbox.ru>
 *  @license http://opensource.org/licenses/MIT
 */

namespace porshkevich\seocore;

use Yii;
use yii\base\Object;
use yii\web\UrlRuleInterface;
use yii\base\InvalidConfigException;
use yii\caching\Cache;

/**
 * Description of SeoUrlRule
 *
 * @author NeoSonic <neosonic@inbox.ru>
 */
class SeoUrlRule extends Object implements UrlRuleInterface {

	/**
	 * UrlAliases model name
	 * @var string
	 */
	public $aliasesClassname = 'porshkevich\seocore\models\UrlAlias';
	public $connectionID = 'db';
	public $routePrefix	 = 'route_';
	public $cache		 = 'cache';

	public function init() {
		if ($this->cache) {
			if (is_string($this->cache))
				$this->cache = Yii::$app->get($this->cache, false);
			elseif (!$this->cache instanceof Cache)
				throw new InvalidConfigException("Property 'cache' must be set to cache id or an Cache");
		}
	}

	public function createUrl($manager, $route, $params) {
		if ($this->cache) {
			$hash	 = $this->getRouteCacheHash($route, $params);
			$url	 = Yii::$app->cache->get($hash);
			if ($url)
				return $url;
		}

		$alias = call_user_func($this->aliasesClassname . '::findByRoute', $route, $params);

		if ($alias) {
			$url	 = $alias->url;
			$params	 = array_diff_assoc($params, $alias->params);
			if (!empty($params) && ($query	 = http_build_query($params)) !== '') {
				$url .= '?' . $query;
			}
			if ($this->cache) {
				Yii::$app->cache->set($hash, $url);
			}
			return $url;
		}
		return false;
	}

	/**
	 *
	 * @param \yii\web\UrlManager $manager
	 * @param \yii\web\Request $request
	 * @return array|boolean
	 */
    public function parseRequest($manager, $request) {
		$pathInfo	 = $request->pathInfo;
		$suffix		 = $manager->suffix;
		if ($suffix !== '' && $pathInfo !== '') {
			$n = strlen($suffix);
			if (substr_compare($pathInfo, $suffix, -$n, $n) === 0) {
				$pathInfo = substr($pathInfo, 0, -$n);
				if ($pathInfo === '') {
					// suffix alone is not allowed
					return false;
				}
			} else {
				return false;
			}
		}
		$alias = call_user_func($this->aliasesClassname . '::findByUrl', $pathInfo);
		if ($alias) {
			Yii::trace("Request parsed with URL rule: {$alias->id}", __METHOD__);
			return [
				$alias->route,
				$alias->params,
			];
		}

		if (empty($request->getQueryParams())) {
			$alias = call_user_func($this->aliasesClassname . '::findByRoute', $route, []);
			if($alias)
				throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
		}
		return false;
	}

	public function getRouteCacheHash($route, $params) {
		return $this->routePrefix . md5($route . http_build_query($params));
	}

}
