<?php

/*
 *  @link https://github.com/porshkevich/yii2-seo-core
 *  @copyright Copyright (c) 2015 NeoSonic <neosonic@inbox.ru>
 *  @license http://opensource.org/licenses/MIT
 */

namespace porshkevich\seocore;

use Yii;
use yii\base\Component;
use yii\web\View;
use porshkevich\seocore\models\UrlMeta;

/**
 * Description of SeoCore
 *
 * @author NeoSonic <neosonic@inbox.ru>
 *
 * @property-read string $keywords
 * @property string $description
 */
class SeoCore extends Component {

	/**
	 * Relevant only when using a bootstrap
	 */
	public $autoreg = true;

	/**
	 *
	 * @var string
	 */
	private $_keywords;

	/**
	 *
	 * @var string
	 */
	private $_description;


	public function init() {
		if ($this->autoreg) {
			$view = Yii::$app->view;
			if ($view instanceof View) {
				$view->on(View::EVENT_END_PAGE, [$this, 'viewEndPageHandler']);
			}

			Yii::$app->urlManager->addRules([['class'=> SeoUrlRule::className()]], false);
		}
	}

	/**
	 *
	 * @param \yii\base\ViewEvent $event
	 */
	public function viewEndPageHandler($event) {
		$this->register($event->sender);
	}

	/**
	 *
	 * @param View $view
	 */
	public function register($view) {
		if ($meta = UrlMeta::findByUrl(Yii::$app->request->pathInfo)) {
			if ($meta->rewrite) {
				$view->title = $meta->title;
				$this->_keywords = $meta->keywords;
				$this->_description = $meta->description;
			}
			else {
				if ($meta->title)
					$view->title = $meta->title;
				$this->addKeywords($meta->keywords);
				if ($meta->description)
					$this->description = $meta->description;
			}
		}

		$this->registerKeywords($view, $this->_keywords);
		$this->registerDescription($view, $thia->_description);
	}

	private function registerKeywords($view, $keywords) {
		$view->registerMetaTag(['name'=>'keywords', 'content'=>$keywords], 'keywords');
	}

	private function registerDescription($view, $description) {
		$view->registerMetaTag(['name'=>'description', 'content'=>$description], 'description');
	}

	/**
	 *
	 * @return string
	 */
	public function getKeywords() {
		return $this->_keywords;
	}

	/**
	 *
	 * @param string|array $value
	 * @param boolean $append
	 */
	public function addKeywords($value, $append = true) {
		if (is_array($value))
			$value = implode (',', $value);
		if ($value) {
			if ($append)
				$this->_keywords .= $value;
			else
				$this->_keywords = $value . $this->_keywords;
		}
	}

	/**
	 *
	 * @return st
	 */
	public function getDescription() {
		return $this->_description;
	}

	public function setDescription($value) {
		$this->_description = $value;
	}

}
