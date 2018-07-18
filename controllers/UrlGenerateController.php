<?php

namespace app\controllers;

use Yii;
use yii\base\Module;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;

class UrlGenerateController extends Controller
{
    /**
     * @var \yii\web\Request
     */
    private $_request;

    public function __construct(string $id, Module $module, array $config = [])
    {
        $this->_request = Yii::$app->request;
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
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
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
     * Login action.
     *
     * @return Response|string
     */
    public function actionGenerate()
    {
        return $this->render('generate', []);
    }
}
