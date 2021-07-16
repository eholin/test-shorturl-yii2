<?php

namespace app\api\modules\v1\controllers;

use app\api\modules\v1\models\ShortUrl;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\helpers\Url;

class LinkController extends Controller
{

    public function init()
    {
        parent::init();
        $this->enableCsrfValidation = false;
    }

    /**
     * Renders the index view for the module
     * @return string[]
     */
    public function actionIndex()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        return (['result' => 'success']);
    }

    /**
     * @return array
     */
    public function actionCreate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $request = Yii::$app->request;

        $url = (!empty($request->post('url'))) ? $request->post('url') : null;

        $short = new ShortUrl();
        $short->url = $url;
        $short->save();

        $siteurl = Url::base(true);

        return (['result' => 'success', 'url' => $siteurl . '/' . $short->short]);
    }

    public function actionStats($hash)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $short = ShortUrl::find()->where(['short' => $hash])->one();
        if ($short) {
            return (['result' => 'success', 'url' => $short->url, 'redirects' => $short->redirects]);
        }

        return (['result' => 'error']);
    }

    /**
     * @param $hash
     */
    public function actionRedirect($hash)
    {
        $short = ShortUrl::find()->where(['short' => $hash])->one();

        if ($short) {
            $short->updateCounters(['redirects' => 1]);
            $this->redirect($short->url);
        }

        $this->redirect('site/error');
    }
}
