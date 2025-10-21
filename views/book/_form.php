<?php

use Yii;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Book $model */
/** @var array<int,string> $authorList */
?>

<div class="book-form">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'isbn')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'published_year')->input('number', ['min' => 0, 'max' => date('Y') + 1]) ?>

    <?php if (empty($authorList)): ?>
        <div class="alert alert-warning">Добавьте хотя бы одного автора перед созданием книги.</div>
    <?php endif; ?>

    <?= $form->field($model, 'authorIds')->listBox($authorList, ['multiple' => true, 'size' => 6]) ?>

    <?= $form->field($model, 'coverFile')->fileInput() ?>

    <?php if ($model->cover_path): ?>
        <div class="mb-3">
            <p>Текущая обложка:</p>
            <img src="<?= Html::encode(Yii::getAlias('@web/' . $model->cover_path)) ?>" alt="Обложка" class="img-thumbnail" style="max-width: 200px;">
        </div>
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Отмена', ['index'], ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
