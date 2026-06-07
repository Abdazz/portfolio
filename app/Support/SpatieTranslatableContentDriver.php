<?php

namespace App\Support;

use Filament\Support\Contracts\TranslatableContentDriver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Translatable\HasTranslations;

class SpatieTranslatableContentDriver implements TranslatableContentDriver
{
    public function __construct(protected string $activeLocale) {}

    public function isAttributeTranslatable(string $model, string $attribute): bool
    {
        if (! in_array(HasTranslations::class, class_uses_recursive($model))) {
            return false;
        }

        return in_array($attribute, app($model)->getTranslatableAttributes());
    }

    /** @return array<string, mixed> */
    public function getRecordAttributesToArray(Model $record): array
    {
        $data = $record->attributesToArray();

        foreach ($this->translatableAttributes($record) as $attribute) {
            /** @phpstan-ignore method.notFound */
            $data[$attribute] = $record->getTranslation($attribute, $this->activeLocale, false);
        }

        return $data;
    }

    /** @param  class-string<Model>  $model
     * @param  array<string, mixed>  $data
     */
    public function makeRecord(string $model, array $data): Model
    {
        $record = new $model;
        $translatable = $this->translatableAttributesForModel($model);

        foreach ($data as $key => $value) {
            if (in_array($key, $translatable)) {
                /** @phpstan-ignore method.notFound */
                $record->setTranslation($key, $this->activeLocale, $value ?? '');
            } else {
                $record->{$key} = $value;
            }
        }

        return $record;
    }

    public function setRecordLocale(Model $record): Model
    {
        if (in_array(HasTranslations::class, class_uses_recursive($record))) {
            /** @phpstan-ignore method.notFound */
            $record->setLocale($this->activeLocale);
        }

        return $record;
    }

    /** @param  array<string, mixed>  $data */
    public function updateRecord(Model $record, array $data): Model
    {
        $translatable = $this->translatableAttributes($record);

        foreach ($data as $key => $value) {
            if (in_array($key, $translatable)) {
                /** @phpstan-ignore method.notFound */
                $record->setTranslation($key, $this->activeLocale, $value ?? '');
            } else {
                $record->{$key} = $value;
            }
        }

        $record->save();

        return $record;
    }

    /**
     * @template TModel of Model
     *
     * @param  Builder<TModel>  $query
     * @return Builder<TModel>
     */
    public function applySearchConstraintToQuery(Builder $query, string $column, string $search, string $whereClause, ?bool $isSearchForcedCaseInsensitive = null): Builder
    {
        $search = mb_strtolower($search);
        $rawClause = $whereClause === 'where' ? 'whereRaw' : 'orWhereRaw';
        $extract = $this->jsonExtractExpression($column);

        return $query->{$rawClause}("lower({$extract}) like ?", ["%{$search}%"]);
    }

    private function jsonExtractExpression(string $column): string
    {
        return match (DB::connection()->getDriverName()) {
            'pgsql' => "{$column}->>'{$this->activeLocale}'",
            'sqlite' => "JSON_EXTRACT({$column}, '$.{$this->activeLocale}')",
            default => "JSON_UNQUOTE(JSON_EXTRACT({$column}, '$.{$this->activeLocale}'))",
        };
    }

    /** @return list<string> */
    private function translatableAttributes(Model $record): array
    {
        return method_exists($record, 'getTranslatableAttributes')
            ? $record->getTranslatableAttributes()
            : [];
    }

    /**
     * @param  class-string<Model>  $model
     * @return list<string>
     */
    private function translatableAttributesForModel(string $model): array
    {
        $instance = app($model);

        return method_exists($instance, 'getTranslatableAttributes')
            ? $instance->getTranslatableAttributes()
            : [];
    }
}
