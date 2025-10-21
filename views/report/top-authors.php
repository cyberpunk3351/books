<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var int $year */
/** @var array<int, array<string, mixed>> $data */
/** @var int[] $years */

$this->title = 'ТОП-10 авторов за ' . $year . ' год';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="report-top-authors">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1><?= Html::encode($this->title) ?></h1>
        <div>
            <?= Html::beginForm(['report/top-authors'], 'get', ['class' => 'form-inline']) ?>
            <div class="input-group">
                <?= Html::dropDownList('year', $year, array_combine($years, $years), ['class' => 'form-select']) ?>
                <button type="submit" class="btn btn-outline-primary">Показать</button>
            </div>
            <?= Html::endForm() ?>
        </div>
    </div>

    <?php if ($data): ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Автор</th>
                        <th>Количество книг</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $index => $row): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= Html::encode($row['full_name']) ?></td>
                            <td><?= Html::encode($row['books_count']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-muted">За выбранный год нет данных.</p>
    <?php endif; ?>
</div>
