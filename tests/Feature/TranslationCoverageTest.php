<?php

use Illuminate\Support\Arr;

/**
 * Translation coverage: every key in lang/en must exist in lang/fr.
 * Runs as part of the regular test suite so CI catches drift.
 */
it('covers all english translation keys in french', function () {
    $langPath = base_path('lang');
    $enFiles = glob("{$langPath}/en/*.php") ?: [];

    $missingKeys = [];

    foreach ($enFiles as $enFile) {
        $filename = basename($enFile);
        $frFile = "{$langPath}/fr/{$filename}";

        // Skip if the FR file hasn't been created yet
        if (! file_exists($frFile)) {
            $missingKeys[] = "lang/fr/{$filename} does not exist";

            continue;
        }

        /** @var array<string, mixed> $enKeys */
        $enKeys = require $enFile;
        /** @var array<string, mixed> $frKeys */
        $frKeys = require $frFile;

        $enFlat = Arr::dot($enKeys);
        $frFlat = Arr::dot($frKeys);

        foreach (array_keys($enFlat) as $key) {
            if (! array_key_exists($key, $frFlat)) {
                $missingKeys[] = "lang/fr/{$filename}: missing key '{$key}'";
            } elseif (empty($frFlat[$key])) {
                $missingKeys[] = "lang/fr/{$filename}: empty translation for '{$key}'";
            }
        }
    }

    expect($missingKeys)->toBeEmpty(
        "Missing or empty French translations:\n".implode("\n", $missingKeys)
    );
});
