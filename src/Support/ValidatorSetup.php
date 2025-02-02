<?php

namespace CourierDZ\Support;

use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory as ValidatorFactory;

class ValidatorSetup
{
    /**
     * Create a new ValidatorFactory instance.
     *
     * @param  string|null  $langPath  Path to language files, defaults to 'lang/' directory.
     * @param  string  $locale  The default locale (default is 'fr').
     */
    public static function makeValidator(?string $langPath = null, string $locale = 'fr'): ValidatorFactory
    {
        // Step 1: Set default language files directory
        $langPath ??= __DIR__.'/lang';

        // Step 2: Create a FileLoader instance to load language files
        // The FileLoader is responsible for loading language files from the specified directory
        $fileLoader = new FileLoader(new Filesystem, $langPath);

        // Step 3: Create a Translator instance with the loader and locale
        // The Translator is responsible for translating text using the loaded language files
        $translator = new Translator($fileLoader, $locale);

        // Step 4: Create and return a ValidatorFactory instance
        // The ValidatorFactory is the main entry point for the validation process
        return new ValidatorFactory($translator, new Container);
    }
}
