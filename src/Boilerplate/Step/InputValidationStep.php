<?php

namespace FriendsOfWp\BoilerplateDevCliExtension\Boilerplate\Step;

use FriendsOfWp\BoilerplateDevCliExtension\Boilerplate\Step\Exception\UnableToCreateException;
use FriendsOfWp\BoilerplateDevCliExtension\Command\BoilerplateCreateCommand;
use Symfony\Component\Console\Question\Question;

/**
 * @todo it should be possible to configure via a config file and not only via command
 *       line parameters.
 *
 * @todo ask for @since. This should always be the newest WordPress version (taken from the WP API).
 *       This API should also be used to validate the version number.
 *
 * @todo ask for license
 */
class InputValidationStep extends BasicStep
{
    /**
     * @inheritDoc
     */
    public function ask(): void
    {
        if (file_exists($this->getInput()->getArgument(BoilerplateCreateCommand::INPUT_OUTPUT_DIR))) {
            $this->getOutput()->writeln('');
            $overWrite = $this->getQuestionHelper()->ask($this->getInput(), $this->getOutput(), new Question('The output dir is already existing. Do you want to overwrite it (yes/no)? '));
            if ($overWrite === 'n' || $overWrite === "no") {
                throw new UnableToCreateException('Output directory already exists and will not be overwritten.');
            }
        }

        $outputDir = $this->getInput()->getArgument('outputDir');
        $this->getConfiguration()->setOutputDir($outputDir);
    }

    /**
     * @inheritDoc
     */
    public function run(): string
    {
        return 'Validating command line parameters';
    }
}
