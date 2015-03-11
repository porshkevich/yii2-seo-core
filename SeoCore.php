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
 */
class SeoCore extends Component {

	/**
	 * Relevant only when using a bootstrap
	 */
	public $autoreg = true;

	private $_keywords;
	private $_description;


	public function init() {
		if ($this->autoreg) {
			$view = Yii::$app->view;
			if ($view instanceof View) {
				$view->on(View::EVENT_END_PAGE, [$this, 'viewEndPageHandler']);
			}

			Yii::$app->urlManager->addRules([['class'=> SeoUrlRule::className()]]);
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
			if ($meta->title)
				$view->title = $meta->title;



		}
	}

	public function getKeywords() {
		return $this->_keywords;
	}

	public function addKeywords($value, $append = true) {
		if (is_array($value))
			$value = implode (', ', $value);

		if ($append)
			$this->_keywords .= $value;
		else
			$this->_keywords = $value . $this->_keywords;
	}

}
