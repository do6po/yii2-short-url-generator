<?php
/**
 * Created by PhpStorm.
 * User: Boiko Sergii
 * Date: 19.07.2018
 * Time: 22:41
 */

use supplyhog\ClipboardJs\ClipboardJsWidget;
use yii\bootstrap\Alert;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var \app\models\Url $url */
/** @var $this yii\web\View */
/** @var \yii\data\ActiveDataProvider $conversionDataProvider */
/** @var \app\models\ConversionSearch $conversionSearch */

$this->title = Yii::t('app', 'Your short URL');

$shortUrl = Url::to([sprintf('/%s', $url->short)], true);

?>

<h1><?= $this->title ?> </h1>

<div class="row">
    <?php if ($url->isExpired()): ?>
        <div class="col-md-12">
            <?= Alert::widget([
                'body' => Yii::t('app', 'This URL is expired'),
                'options' => [
                    'class' => 'alert-warning',
                ],
            ]) ?>
        </div>
    <?php endif; ?>
    <div class="row">
        <div class="col-md-8">
            <?= Html::input('text', 'short-url', $shortUrl, [
                'class' => 'form-control input-lg',
                'disabled' => 'disabled',
                'title' => $url->long,
            ]) ?>
        </div>
        <div class="col-md-2">
            <?= ClipboardJsWidget::widget([
                'id' => 'copyShortURL',
                'label' => Yii::t('app', 'Copy short URL'),
                'text' => $shortUrl,
                'htmlOptions' => [
                    'class' => 'btn btn-warning btn-lg',
                    'disabled' => $url->isExpired() ? 'disabled' : null,
                ],
            ]) ?>
        </div>
        <div class="col-md-2">
            <?= ClipboardJsWidget::widget([
                'id' => 'copyThisPageURL',
                'label' => Yii::t('app', 'Copy statistic URL'),
                'text' => Url::current([], true),
                'htmlOptions' => [
                    'class' => 'btn btn-default btn-lg',
                ],
            ]) ?>
        </div>
    </div>
</div>

<?php if ($conversionDataProvider->count) : ?>
    <div class="row">
        <div class="col-md-12">
            <h2><?= Yii::t('app', 'Conversion stats') ?></h2>
            <?= GridView::widget([
                'dataProvider' => $conversionDataProvider,
                'tableOptions' => [
                    'class' => 'table table-striped table-hover',
                ],
                'columns' => [
                    [
                        'attribute' => 'created_at',
                        'label' => Yii::t('app', 'Redirected on'),
                        'format' => ['datetime'],
                    ],
                    'ipAddress',
                    'country',
                    'city',
                ],
            ]) ?>
        </div>
    </div>
<?php endif ?>



