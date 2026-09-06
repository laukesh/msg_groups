<?php

namespace App\Models\Concerns;

trait AutoGeneratesDocumentCode
{
    abstract protected function codeField(): string;

    abstract protected function codePrefix(): string;

    public static function formatDocumentCode(string $prefix, int $id): string
    {
        return $prefix . '-' . str_pad((string) $id, 6, '0', STR_PAD_LEFT);
    }

    protected static function bootAutoGeneratesDocumentCode(): void
    {
        static::creating(function (self $model) {
            $field = $model->codeField();

            if (blank($model->{$field})) {
                $model->{$field} = $model->codePrefix() . '-TMP-' . uniqid('', true);
            }
        });

        static::created(function (self $model) {
            $field = $model->codeField();
            $prefix = $model->codePrefix();
            $value = (string) $model->{$field};

            if (str_starts_with($value, $prefix . '-TMP-')) {
                $model->updateQuietly([
                    $field => self::formatDocumentCode($prefix, $model->id),
                ]);
            }
        });
    }
}
