<?php
/**
 * Created by PhpStorm.
 * User: Boiko Sergii
 * Date: 19.07.2018
 * Time: 22:41
 */

use supplyhog\ClipboardJs\ClipboardJsWidget;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var \app\models\Url $url */
/** @var $this yii\web\View */

$this->title = Yii::t('app', 'Your short URL');

$shortUrl = Url::to([sprintf('/%s', $url->short)], true);

?>

    <h1><?= $this->title ?> </h1>

    <div class="row">
        <div class="col-md-10">
            <?= Html::input('text', 'short-url', $shortUrl, [
                'class' => 'form-control input-lg',
                'disabled' => 'disabled',
            ]) ?>
        </div>
        <div class="col-md-2">
            <?= ClipboardJsWidget::widget([
                'text' => $shortUrl,
                'htmlOptions' => [
                    'class' => 'btn btn-warning btn-lg',
                ],
            ]) ?>
        </div>
    </div>
<?php
