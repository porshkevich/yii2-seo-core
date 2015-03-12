<?php

/*
 *  @link https://github.com/porshkevich/yii2-seo-core
 *  @copyright Copyright (c) 2015 NeoSonic <neosonic@inbox.ru>
 *  @license http://opensource.org/licenses/MIT
 */

namespace porshkevich\seocore\controllers;

use yii\web\Controller;
/**
 * Description of DefaultController
 *
 * @author NeoSonic <neosonic@inbox.ru>
 */
class DefaultController  extends Controller {

	public function actionIndex()
    {
        return $this->render('index');
    }
}
