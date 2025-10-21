<?php

use app\models\Book;
use Yii;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\search\BookSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var array<int,string> $authorList */

$this->title = 'Каталог книг';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="book-index">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1><?= Html::encode($this->title) ?></h1>
        <?php if (!Yii::$app->user->isGuest): ?>
            <?= Html::a('Добавить книгу', ['create'], ['class' => 'btn btn-success']) ?>
        <?php endif; ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'cover_path',
                'label' => 'Обложка',
                'format' => 'raw',
                'value' => static function (Book $model) {
                    if (!$model->cover_path) {
                        return Html::tag('span', '—', ['class' => 'text-muted']);
                    }
                    $url = Yii::getAlias('@web/' . $model->cover_path);
                    return Html::img($url, ['style' => 'max-width:80px', 'class' => 'img-thumbnail']);
                },
                'filter' => false,
                'contentOptions' => ['style' => 'width: 100px;'],
            ],
            [
                'attribute' => 'title',
                'format' => 'raw',
                'value' => static function (Book $model) {
                    return Html::a(Html::encode($model->title), ['view', 'id' => $model->id]);
                },
            ],
            [
                'attribute' => 'authorId',
                'label' => 'Автор',
                'value' => static function (Book $model) {
                    return implode(', ', ArrayHelper::getColumn($model->authors, 'full_name'));
                },
                'filter' => $authorList,
            ],
            'published_year',
            'isbn',
            [
                'attribute' => 'created_at',
                'format' => ['datetime', 'php:d.m.Y H:i'],
                'filter' => false,
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'visibleButtons' => [
                    'update' => !Yii::$app->user->isGuest,
                    'delete' => !Yii::$app->user->isGuest,
                ],
            ],
        ],
    ]); ?>
</div>
