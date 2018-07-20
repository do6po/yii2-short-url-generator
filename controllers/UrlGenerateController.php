<?php

namespace app\controllers;

use app\models\Url as UrlModel;
use app\services\ConversionService;
use app\services\UrlService;
use Yii;
use yii\base\Module;
use yii\captcha\CaptchaAction;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\ErrorAction;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;

class UrlGenerateController extends Controller
{
    /**
     * @var \yii\web\Request
     */
    private $_request;

    /**
     * @var UrlService
     */
    private $_urlService;

    /**
     * @var ConversionService
     */
    private $_conversionService;

    public function __construct(
        string $id,
        Module $module,
        UrlService $urlServices,
        ConversionService $conversionService,
        array $config = []
    )
    {
        $this->_request = Yii::$app->request;
        $this->_conversionService = $conversionService;
        $this->_urlService = $urlServices;

        parent::__construct($id, $module, $config);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => ErrorAction::class,
            ],
            'captcha' => [
                'class' => CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * @return string|Response
     */
    public function actionIndex()
    {
        return $this->actionGenerate();
    }

    /**
     * @return string|Response
     */
    public function actionGenerate()
    {
        $url = new UrlModel();
        if (
            $this->_request->isPost
            && $url->load($this->_request->post())
            && $url->validate()
            && $id = $this->_urlService->create($url)
        ) {
            return $this->redirect(Url::to(['url-info', 'id' => $id]));
        }

        return $this->render('generate', [
            'url' => $url,
            'durations' => UrlModel::durations(),
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionUrlInfo($id)
    {
        $url = $this->findUrl($id);
        return $this->render('info', [
            'url' => $url,
        ]);
    }

    /**
     * @param $short
     * @return Response
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionConvert($short)
    {
        $url = $this->findByShort($short);

        if ($url->isExpired()) {
            return $this->redirect(['url-info', 'id' => $url->id]);
        }

        $this->_conversionService->create($url, $this->_request);

        return $this->redirect($url->long);
    }

    /**
     * @param $id
     * @return null|UrlModel
     * @throws NotFoundHttpException
     */
    public function findUrl($id)
    {
        if (($url = UrlModel::findOne($id)) !== null ) {
            return $url;
        }

        throw new NotFoundHttpException('URL not found!');
    }

    /**
     * @param $short
     * @return UrlModel
     * @throws NotFoundHttpException
     */
    public function findByShort($short)
    {
        if (($url = UrlModel::findByShort($short)) !== null ) {
            return $url;
        }

        throw new NotFoundHttpException('URL not found!');
    }
}
