<?php

use Behat\Behat\Context\BehatContext;
use Behat\Gherkin\Node\PyStringNode;
use Symfony\Component\Filesystem\Filesystem;
use PhpSpec\Console\Application;

class PHPSpecContext extends BehatContext
{
    /**
     * @var string
     */
    private $workDir;

    /**
     * @var ApplicationTester
     */
    private $applicationTester;

    /**
     * @BeforeScenario
     */
    public function createWorkDir()
    {

        $this->workDir = sprintf(
            '%s/%s/',
            sys_get_temp_dir(),
            uniqid('PHPSpecMatcherExtension')
        );
        $fs = new Filesystem();
        $fs->mkdir($this->workDir, 0777);
        chdir($this->workDir);
    }

    /**
     * @AfterScenario
     */
    public function removeWorkDir()
    {
        $fs = new Filesystem();
        $fs->remove($this->workDir);
    }

    /**
     * @Given /^the PHPSpecMatcherExtension is enabled$/
     */
    public function thePhpspecmatcherextensionIsEnabled()
    {
        $phpspecyml = <<<YML
extensions:
  - Coduo\PhpSpec\MatcherExtension
YML;

        file_put_contents($this->workDir.'phpspec.yml', $phpspecyml);
    }

    /**
     * @Given /^(?:|the )(?:spec |class )file "(?P<file>[^"]+)" contains:$/
     */
    public function theFileContains($file, PyStringNode $string)
    {
        $dirname = dirname($file);
        if (!file_exists($dirname)) {
            mkdir($dirname, 0777, true);
        }

        file_put_contents($file, $string->getRaw());

        require_once($file);
    }

    /**
     * @When /^I write a (?:spec|class) "([^"]*)" with following code$/
     */
    public function iWriteASpecWithFollowingCode($file, PyStringNode $codeContent)
    {
        $dirname = dirname($file);
        if (!file_exists($dirname)) {
            mkdir($dirname, 0777, true);
        }

        file_put_contents($file, $codeContent->getRaw());

        require_once($file);
    }

    /**
     * @Given /^I run phpspec$/
     */
    public function iRunPhpspec()
    {
        $this->applicationTester = $this->createApplicationTester();
        $this->applicationTester->run('run --no-interaction');
    }

    /**
     * @Then /^it should pass$/
     */
    public function itShouldPass()
    {
        expect($this->applicationTester->getResult())->toBe(0);
    }

    /**
     * @Then /^it should fail$/
     */
    public function itShouldFail()
    {
        expect($this->applicationTester->getResult())->toBe(2);
    }

    /**
     * @Given /^I should see \'([^\']*)\'$/
     */
    public function iShouldSeeFollowingOutput($message)
    {
        expect($this->applicationTester->getDisplay())->toMatch('/'.preg_quote($message, '/').'/sm');
    }

    /**
     * @return ApplicationTester
     */
    private function createApplicationTester()
    {
        $application = new Application('2.0-dev');
        $application->setAutoExit(false);

        return new ApplicationTester($application);
    }
}
