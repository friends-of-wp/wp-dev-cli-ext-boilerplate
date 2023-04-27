<?php

namespace FriendsOfWp\BoilerplateDevCliExtension\Boilerplate\Step;

class CopyTemplatesStep extends BasicStep
{
    public function run(): string
    {
        $destinationDirectory = $this->getConfiguration()->getOutputDir();
        $sourceDirectory = __DIR__ . '/../../../boilerplate-template/';

        $this->prepareDirectory($destinationDirectory);

        $this->recurseCopy($sourceDirectory, $destinationDirectory);

        return "Copied boilerplate templates from 'boilerplate' to '$destinationDirectory'.";
    }

    /**
     * Create the directory if not existing
     *
     * @param string $directory
     * @return void
     */
    private function prepareDirectory(string $directory): void
    {
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }
    }

    private function recurseCopy(string $sourceDirectory, string $destinationDirectory): void
    {
        $directory = opendir($sourceDirectory);

        if (is_dir($destinationDirectory) === false) {
            mkdir($destinationDirectory);
        }

        while (($file = readdir($directory)) !== false) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            if (is_dir("$sourceDirectory/$file") === true) {
                $this->recurseCopy("$sourceDirectory/$file", "$destinationDirectory/$file");
            } else {
                copy("$sourceDirectory/$file", "$destinationDirectory/$file");
            }
        }

        closedir($directory);
    }
}
