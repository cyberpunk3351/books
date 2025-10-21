<?php

namespace app\components;

use app\models\Author;
use app\models\Book;
use app\models\BookAuthor;
use yii\base\Component;
use yii\db\Query;

class TopAuthorReportService extends Component
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public function getTopAuthors(int $year, int $limit = 10): array
    {
        $authorTable = Author::tableName();
        $bookTable = Book::tableName();
        $bookAuthorTable = BookAuthor::tableName();

        $query = (new Query())
            ->select([
                'author_id' => 'a.id',
                'full_name' => 'a.full_name',
                'books_count' => 'COUNT(b.id)',
            ])
            ->from(['a' => $authorTable])
            ->innerJoin(['ba' => $bookAuthorTable], 'ba.author_id = a.id')
            ->innerJoin(['b' => $bookTable], 'b.id = ba.book_id')
            ->groupBy(['a.id', 'a.full_name'])
            ->orderBy(['books_count' => SORT_DESC, 'a.full_name' => SORT_ASC])
            ->limit($limit);

        if ($year > 0) {
            $query->andWhere(['b.published_year' => $year]);
        }

        return $query->all();
    }

    /**
     * @return int[]
     */
    public function getAvailableYears(): array
    {
        $years = (new Query())
            ->select(['published_year'])
            ->from(Book::tableName())
            ->where(['not', ['published_year' => null]])
            ->groupBy(['published_year'])
            ->orderBy(['published_year' => SORT_DESC])
            ->column();

        if (!$years) {
            $years = [(int) date('Y')];
        }

        return array_map('intval', $years);
    }
}
