<?php
/**
 * Created by PhpStorm.
 * User: Boiko Sergii
 * Date: 18.07.2018
 * Time: 23:43
 */

use app\models\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/** @var Url $url */
/** @var $this yii\web\View */
/** @var array $durations */

$title = Yii::t('app', 'Create short url');
$this->title = $title;
?>
    <h1> <?= $title ?> </h1>
<?php $form = ActiveForm::begin(['id' => 'url-generate-form']) ?>
    <div class="row">
        <div class="col-md-7">
            <?= $form->field($url, 'long')
                ->textInput([
                    'class' => 'form-control input-lg',
                    'placeholder' => Yii::t('app', 'Copy your long URL here'),
                ])->label(false) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($url, 'duration')
                ->dropDownList($durations, [
                    'class' => 'form-control input-lg',
                    'options' => [
                        Url::DAY => ['selected' => true],
                    ],
                ])->label(false) ?>
        </div>
        <div class="col-md-2">
            <?= Html::submitButton(Yii::t('app', 'Reduce'), ['class' => 'btn btn-success btn-lg btn-block']) ?>
        </div>
    </div>
<?php ActiveForm::end();
