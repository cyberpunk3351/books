<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\forms\SubscriptionForm $model */
/** @var app\models\Author $author */

$this->title = 'Подписка на автора ' . $author->full_name;
$this->params['breadcrumbs'][] = ['label' => 'Авторы', 'url' => ['author/index']];
$this->params['breadcrumbs'][] = ['label' => $author->full_name, 'url' => ['author/view', 'id' => $author->id]];
$this->params['breadcrumbs'][] = 'Подписка';
?>

<div class="subscription-create">
    <h1><?= Html::encode($this->title) ?></h1>

    <p class="text-muted">Мы отправим SMS, когда у автора появятся новые книги. Используется тестовый режим SMS Pilot (отправка в лог).</p>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'phone')->textInput(['placeholder' => '+71234567890']) ?>

    <?= $form->field($model, 'subscriberName')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Подписаться', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Отмена', ['author/view', 'id' => $author->id], ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
