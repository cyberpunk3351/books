<?php

use Yii;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Book $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Книги', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="book-view">
    <div class="d-flex justify-content-between align-items-start mb-3">
        <h1><?= Html::encode($this->title) ?></h1>
        <div>
            <?php if (!Yii::$app->user->isGuest): ?>
                <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(
                    'Удалить',
                    ['delete', 'id' => $model->id],
                    [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Удалить книгу?',
                            'method' => 'post',
                        ],
                    ]
                ) ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <?php if ($model->cover_path): ?>
                <img src="<?= Html::encode(Yii::getAlias('@web/' . $model->cover_path)) ?>" alt="Обложка" class="img-fluid rounded shadow-sm">
            <?php else: ?>
                <div class="text-muted">Обложка отсутствует</div>
            <?php endif; ?>
        </div>
        <div class="col-md-8">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'title',
                    'isbn',
                    'published_year',
                    [
                        'label' => 'Авторы',
                        'value' => implode(', ', array_map(static fn ($author) => $author->full_name, $model->authors)),
                    ],
                    [
                        'attribute' => 'description',
                        'format' => 'ntext',
                    ],
                ],
            ]) ?>
        </div>
    </div>

    <h3>Авторы</h3>
    <div class="row">
        <?php foreach ($model->authors as $author): ?>
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title"><?= Html::encode($author->full_name) ?></h5>
                        <?= Html::a('Профиль автора', ['author/view', 'id' => $author->id], ['class' => 'card-link']) ?>
                        <?= Html::a('Подписаться', ['subscription/create', 'authorId' => $author->id], ['class' => 'card-link text-success']) ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
