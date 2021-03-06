<?php declare(strict_types=1);

namespace Lmc\CodingStandard\Sniffs;

use PHP_CodeSniffer\Config;
use PHP_CodeSniffer\Files\LocalFile;
use PHP_CodeSniffer\Ruleset;
use PHPUnit\Framework\TestCase;

abstract class AbstractSniffTestCase extends TestCase
{
    /**
     * Inspired by https://payton.codes/2017/12/15/creating-sniffs-for-a-phpcs-standard/#writing-tests
     */
    protected function applyFixturesToSniff(string $fixtureFile): LocalFile
    {
        $config = new Config();
        $ruleset = new Ruleset($config);

        $ruleset->registerSniffs([$this->getSniffFile()], [], []);
        $ruleset->populateTokenListeners();

        return new LocalFile($fixtureFile, $ruleset, $config);
    }

    /**
     * @param array<int, string> $expectedErrors
     * @param array<int, array> $actualErrors
     */
    protected function assertErrors(array $expectedErrors, array $actualErrors): void
    {
        $actualLinesToErrorsMap = [];
        foreach ($actualErrors as $line => $error) {
            $error = reset($error);
            $errorMessage = reset($error)['message'];
            $actualLinesToErrorsMap[$line] = $errorMessage;
        }

        $this->assertSame($expectedErrors, $actualLinesToErrorsMap);
    }

    abstract protected function getSniffFile(): string;
}
