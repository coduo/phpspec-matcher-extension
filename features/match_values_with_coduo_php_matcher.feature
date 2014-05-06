Feature: Match values with Coduo php matcher
  In order to use Coduo php matcher
  I need to enable PHPSpecMatcherExtension in phpspec.yml file

  Scenario: Positive match with Coduo matcher
    Given the PHPSpecMatcherExtension is enabled
    When I write a spec "spec/Coduo/UserSpec.php" with following code
    """
<?php

namespace spec\Coduo;

use PhpSpec\ObjectBehavior;

class UserSpec extends ObjectBehavior
{
    function it_always_have_salt()
    {
        $this->getSalt()->shouldMatchPattern('@string@');
        $this->getSalt()->shouldNotMatchPattern('@integer@');
    }
}
    """
    And I write a class "src/Coduo/User.php" with following code
    """
<?php

namespace Coduo;

class User
{
    private $salt;

    public function __construct()
    {
        $this->salt = uniqid();
    }

    public function getSalt()
    {
        return $this->salt;
    }
}
    """
    And I run phpspec
    Then it should pass

  Scenario: Negative match with Coduo matcher
    Given the PHPSpecMatcherExtension is enabled
    When I write a spec "spec/Coduo/NumberGeneratorSpec.php" with following code
    """
<?php

namespace spec\Coduo;

use PhpSpec\ObjectBehavior;

class NumberGeneratorSpec extends ObjectBehavior
{
    function it_generate_random_number()
    {
        $this->getRandomNumber()->shouldMatchPattern('@string@');
    }
}
    """
    And I write a class "src/Coduo/NumberGenerator.php" with following code
    """
<?php

namespace Coduo;

class NumberGenerator
{
    public function getRandomNumber()
    {
        return 4; // chosen by fair dice roll
                  // guaranteed to be random
                  // http://xkcd.com/221/
    }
}
    """
    And I run phpspec
    Then it should fail
    And I should see '"4" does not match "@string@"'


