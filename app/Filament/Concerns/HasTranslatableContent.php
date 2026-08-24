<?php

namespace App\Filament\Concerns;

use App\Support\SpatieTranslatableContentDriver;
use Filament\Actions\Action;
use Filament\Actions\Action as FilamentAction;
use Filament\Support\Enums\Size;

trait HasTranslatableContent
{
    public string $activeLocale = 'en';

    public function getFilamentTranslatableContentDriver(): ?string
    {
        return SpatieTranslatableContentDriver::class;
    }

    public function getActiveSchemaLocale(): ?string
    {
        return $this->activeLocale;
    }

    public function setLocale(string $locale): void
    {
        $this->activeLocale = $locale;

        /** @phpstan-ignore function.alreadyNarrowedType */
        if (method_exists($this, 'fillForm')) {
            $this->fillForm();
        }
    }

    /** @return list<FilamentAction> */
    public function getLocaleActions(): array
    {
        return array_map(
            fn (string $locale) => Action::make("locale_{$locale}")
                ->label(strtoupper($locale))
                ->size(Size::Small)
                ->color(fn () => $this->activeLocale === $locale ? 'primary' : 'gray')
                ->action(fn () => $this->setLocale($locale)),
            ['en', 'fr'],
        );
    }
}
