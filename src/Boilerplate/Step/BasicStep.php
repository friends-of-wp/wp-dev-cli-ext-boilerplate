<?php

namespace FriendsOfWp\BoilerplateDevCliExtension\Boilerplate\Step;

use FriendsOfWp\BoilerplateDevCliExtension\Boilerplate\Configuration;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * This class is the basic class for all steps.
 */
abstract class BasicStep implements Step
{
    private Configuration $configuration;
    private InputInterface $input;
    private OutputInterface $output;
    private QuestionHelper $questionHelper;

    /**
     * The constructor
     */
    public function __construct(Configuration $configuration, InputInterface $input, OutputInterface $output, QuestionHelper $questionHelper)
    {
        $this->configuration = $configuration;
        $this->input = $input;
        $this->output = $output;
        $this->questionHelper = $questionHelper;
    }

    /**
     * @return QuestionHelper
     */
    public function getQuestionHelper(): QuestionHelper
    {
        return $this->questionHelper;
    }

    /**
     * @inheritDoc
     */
    public function ask(): void
    {

    }

    /**
     * This function writes a standardized warning to the command line.
     */
    protected function writeWarning(string $message)
    {
        $output = $this->getOutput();
        $output->writeln('');
        $output->writeln('<bg=yellow>' . $message . '</>');
        $output->writeln('');
    }

    /**
     * Return the plugin configuration.
     */
    protected function getConfiguration(): Configuration
    {
        return $this->configuration;
    }

    /**
     * Return the input interface.
     */
    protected function getInput(): InputInterface
    {
        return $this->input;
    }

    /**
     * Return the output interface.
     */
    protected function getOutput(): OutputInterface
    {
        return $this->output;
    }

    /**
     * Copy a file and replace placeholders.
     */
    protected function enrichedCopy(string $from, string $to, $enrichArray = [], $limiters = '##')
    {
        $defaultArray = [
            'CURRENT_YEAR' => date('Y')
        ];

        $fullEnrichArray = array_merge($enrichArray, $defaultArray);

        $content = file_get_contents($from);

        foreach ($fullEnrichArray as $search => $replacement) {
            $content = str_replace($limiters . $search . $limiters, $replacement, $content);
        }

        file_put_contents($to, $content);
    }

    protected function enrichFile(string $file, array $enrichArray, $limiters = '##')
    {
        $this->enrichedCopy($file, $file, $enrichArray, $limiters);
    }

    /**
     * Enrich the boilerplate file.
     *
     * This function can not be called after the RenameMasterFileStep or RenamePluginDirStep were called.
     */
    protected function enrichBoilerplateFile(array $enrichArray): void
    {
        $this->enrichFile($this->getConfiguration()->getPluginBootstrapFile(), $enrichArray);
    }

    /**
     * Remove parts of the boilerplate file.
     */
    protected function removeFromBoilerplateFile(array $removeArray): void
    {
        $this->enrichFile($this->getConfiguration()->getPluginBootstrapFile(), $removeArray, '');
    }

    /**
     * Ask a "Symfony question". This function will cache the result and avoids that the
     * user has to anwer twice. It also takes care of the config file and will not ask
     * for parameters that are already defined in the config file.
     */
    protected function askQuestion(Question $question, string $identifier = null, $ignoreConfigurationParameter = false)
    {
        $configuration = $this->getConfiguration();

        if (!$ignoreConfigurationParameter && $identifier && $configuration->hasParameter($identifier)) {
            return $configuration->getParameter($identifier);
        } else {
            $answer = $this->getQuestionHelper()->ask($this->getInput(), $this->getOutput(), $question);
            if ($identifier) {
                $configuration->setParameter($identifier, $answer);
            }
            return $answer;
        }
    }

    /**
     * Ask a simple yes/no question.
     */
    protected function askYesNoQuestion(string $messageWithQuestionmark, string $identifier = null): bool
    {
        $answer = $this->askQuestion(new Question($messageWithQuestionmark . '(yes/no)? '), $identifier);


        if (!$answer) {
            $result = true;
        } else {
            $answer = strtolower($answer);
            if ($answer === 'n' || $answer === "no") {
                $result = false;
            } else {
                $result = true;
            }
        }

        if ($identifier) {
            $this->getConfiguration()->setParameter($identifier, $result);
        }

        return $result;
    }
}
