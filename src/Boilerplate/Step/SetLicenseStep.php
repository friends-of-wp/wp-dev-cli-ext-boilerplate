<?php

namespace FriendsOfWp\BoilerplateDevCliExtension\Boilerplate\Step;

use FriendsOfWp\BoilerplateDevCliExtension\Boilerplate\Configuration;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

/**
 * This command sets the license of the plugin. The user can choose from different
 * licenses. Afterwards a LICENSE file (for GitHub) gets created and the plugins
 * boilerplate file gets enriched with the license information and the license URI.
 */
class SetLicenseStep extends BasicStep
{
    const LICENSE_MIT = 'MIT';
    const LICENSE_GPL = 'GPL';

    const LICENSE_NONE = 'none';

    private $licenseUris = [
        self::LICENSE_MIT => 'https://opensource.org/license/mit/',
        self::LICENSE_GPL => 'https://www.gnu.org/licenses/gpl-3.0.txt',
    ];

    /**
     * @inheritDoc
     */
    public function ask(): void
    {
        $question = new ChoiceQuestion(
            'Please select a licnse (default: ' . self::LICENSE_MIT . ')',
            [self::LICENSE_MIT, self::LICENSE_GPL, self::LICENSE_NONE],
            0
        );
        $question->setErrorMessage('License %s is invalid.');

        $license = $this->askQuestion($question, Configuration::PARAM_PLUGIN_LICENSE);
    }

    /**
     * @inheritDoc
     */
    public function run(): string
    {
        $configuration = $this->getConfiguration();

        $license = $this->getConfiguration()->getParameter(Configuration::PARAM_PLUGIN_LICENSE);

        if ($license != self::LICENSE_NONE) {
            $licenseFile = $configuration->getOutputDir() . '/LICENSE';
            $this->enrichedCopy(__DIR__ . '/templates/licenses/' . $license . '.txt', $licenseFile);
            $bolierplateArray = [
                'LICENSE' => $license,
                'LICENSE_URI' => $this->licenseUris[$license]
            ];
            $this->enrichBoilerplateFile($bolierplateArray);
        } else {
            $bolierplateArray = [
                " * License:           ##LICENSE##\n" => '',
                " * License URI:       ##LICENSE_URI##\n" => ''
            ];
            $this->removeFromBoilerplateFile($bolierplateArray);
        }

        return "License set to " . $license . ' and LICENSE file copied.';
    }
}
