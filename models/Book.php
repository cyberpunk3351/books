<?php

namespace app\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class Book extends ActiveRecord
{
    /**
     * @var int[]
     */
    public array $authorIds = [];

    public ?UploadedFile $coverFile = null;

    public static function tableName(): string
    {
        return '{{%book}}';
    }

    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => fn () => time(),
            ],
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
        ];
    }

    public function rules(): array
    {
        return [
            [['title'], 'required'],
            [['description'], 'string'],
            [['published_year'], 'integer', 'min' => 0, 'max' => (int) date('Y') + 1],
            [['title'], 'string', 'max' => 255],
            [['isbn'], 'string', 'max' => 32],
            [['cover_path'], 'string', 'max' => 512],
            [['isbn'], 'unique'],
            [['authorIds'], 'required', 'message' => 'Выберите хотя бы одного автора'],
            [['authorIds'], 'each', 'rule' => ['integer']],
            [['coverFile'], 'file', 'extensions' => ['png', 'jpg', 'jpeg', 'webp'], 'maxSize' => 5 * 1024 * 1024, 'skipOnEmpty' => true],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'title' => 'Название',
            'description' => 'Описание',
            'isbn' => 'ISBN',
            'published_year' => 'Год выпуска',
            'cover_path' => 'Обложка',
            'authorIds' => 'Авторы',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getBookAuthors(): ActiveQuery
    {
        return $this->hasMany(BookAuthor::class, ['book_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getAuthors(): ActiveQuery
    {
        return $this->hasMany(Author::class, ['id' => 'author_id'])->via('bookAuthors');
    }

    public function afterFind(): void
    {
        parent::afterFind();
        $this->authorIds = ArrayHelper::getColumn($this->bookAuthors, 'author_id');
    }

    public function afterSave($insert, $changedAttributes): void
    {
        parent::afterSave($insert, $changedAttributes);

        if ($this->authorIds !== null) {
            $this->syncAuthors($this->authorIds);
        }
    }

    private function syncAuthors(array $authorIds): void
    {
        $authorIds = array_unique(array_filter($authorIds, static fn ($id) => $id !== '' && $id !== null));
        $currentIds = ArrayHelper::getColumn($this->getAuthors()->select('id')->asArray()->all(), 'id');

        $toAdd = array_diff($authorIds, $currentIds);
        $toRemove = array_diff($currentIds, $authorIds);

        foreach ($toAdd as $authorId) {
            $author = Author::findOne((int) $authorId);
            if ($author !== null) {
                $this->link('authors', $author);
            }
        }

        if ($toRemove) {
            foreach ($this->authors as $author) {
                if (in_array((int) $author->id, $toRemove, true)) {
                    $this->unlink('authors', $author, true);
                }
            }
        }

        $this->authorIds = $authorIds;
        $this->populateRelation('authors', Author::findAll($authorIds));
    }

    public function beforeSave($insert): bool
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if ($this->coverFile instanceof UploadedFile) {
            if (!$this->storeCoverFile()) {
                return false;
            }
        }

        return true;
    }

    private function storeCoverFile(): bool
    {
        $uploadDir = Yii::getAlias('@webroot/uploads/books');
        FileHelper::createDirectory($uploadDir);

        $fileName = uniqid('cover_', true) . '.' . $this->coverFile->extension;
        $filePath = $uploadDir . DIRECTORY_SEPARATOR . $fileName;

        if ($this->coverFile->saveAs($filePath)) {
            $this->deleteCoverFile();
            $this->cover_path = 'uploads/books/' . $fileName;
            return true;
        }

        return false;
    }

    public function deleteCoverFile(): void
    {
        if (!$this->cover_path) {
            return;
        }

        $absolutePath = Yii::getAlias('@webroot/' . ltrim($this->cover_path, '/'));
        if (is_file($absolutePath)) {
            @unlink($absolutePath);
        }
    }

    public function afterDelete(): void
    {
        $this->deleteCoverFile();
        parent::afterDelete();
    }
}
