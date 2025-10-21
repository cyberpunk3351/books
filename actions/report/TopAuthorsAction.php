<?php

namespace app\actions\report;

use app\components\TopAuthorReportService;
use yii\base\Action;

class TopAuthorsAction extends Action
{
    public TopAuthorReportService $reportService;
    public string $view = 'top-authors';

    public function run(?int $year = null)
    {
        $year = $year ?: (int) date('Y');
        $data = $this->reportService->getTopAuthors($year);

        return $this->controller->render($this->view, [
            'year' => $year,
            'data' => $data,
            'years' => $this->reportService->getAvailableYears(),
        ]);
    }
}
