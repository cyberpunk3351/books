<?php

use Yii;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Author $model */

$this->title = $model->full_name;
$this->params['breadcrumbs'][] = ['label' => 'Авторы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="author-view">
    <div class="d-flex justify-content-between align-items-start mb-3">
        <h1><?= Html::encode($this->title) ?></h1>
        <div>
            <?= Html::a('Подписаться', ['subscription/create', 'authorId' => $model->id], ['class' => 'btn btn-outline-success']) ?>
            <?php if (!Yii::$app->user->isGuest): ?>
                <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(
                    'Удалить',
                    ['delete', 'id' => $model->id],
                    [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Удалить автора? Все его книги останутся, но связь будет удалена.',
                            'method' => 'post',
                        ],
                    ]
                ) ?>
            <?php endif; ?>
        </div>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'full_name',
            [
                'label' => 'Количество книг',
                'value' => $model->getBooks()->count(),
            ],
        ],
    ]) ?>

    <h3 class="mt-4">Книги автора</h3>
    <?php if ($model->books): ?>
        <div class="list-group">
            <?php foreach ($model->books as $book): ?>
                <?= Html::a(
                    Html::encode($book->title) . ' (' . ($book->published_year ?: 'год не указан') . ')',
                    ['book/view', 'id' => $book->id],
                    ['class' => 'list-group-item list-group-item-action']
                ) ?>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="text-muted">У автора пока нет книг.</p>
    <?php endif; ?>
</div>
