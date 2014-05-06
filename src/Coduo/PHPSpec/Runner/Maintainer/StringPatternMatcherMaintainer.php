<?php

namespace  Coduo\PHPSpec\Runner\Maintainer;

use Coduo\PHPMatcher\Matcher as CoduoMatcher;
use Coduo\PHPSpec\Matcher\StringPatternMatcher;
use PhpSpec\Loader\Node\ExampleNode;
use PhpSpec\Runner\Maintainer\MaintainerInterface;
use PhpSpec\SpecificationInterface;
use PhpSpec\Runner\MatcherManager;
use PhpSpec\Runner\CollaboratorManager;

class StringPatternMatcherMaintainer implements MaintainerInterface
{
    /**
     * @param ExampleNode $example
     *
     * @return bool
     */
    public function supports(ExampleNode $example)
    {
        return true;
    }

    /**
     * @param ExampleNode            $example
     * @param SpecificationInterface $context
     * @param MatcherManager         $matchers
     * @param CollaboratorManager    $collaborators
     */
    public function prepare(ExampleNode $example, SpecificationInterface $context,
                            MatcherManager $matchers, CollaboratorManager $collaborators)
    {
        $scalarMatchers = new CoduoMatcher\ChainMatcher(array(
            new CoduoMatcher\ExpressionMatcher(),
            new CoduoMatcher\TypeMatcher(),
            new CoduoMatcher\ScalarMatcher(),
            new CoduoMatcher\WildcardMatcher()
        ));
        $arrayMatcher = new CoduoMatcher\ArrayMatcher($scalarMatchers);
        $matcher = new CoduoMatcher(new CoduoMatcher\ChainMatcher(array(
            $scalarMatchers,
            $arrayMatcher,
            new CoduoMatcher\JsonMatcher($arrayMatcher)
        )));

        $matchers->add(new StringPatternMatcher($matcher));
    }

    /**
     * @param ExampleNode            $example
     * @param SpecificationInterface $context
     * @param MatcherManager         $matchers
     * @param CollaboratorManager    $collaborators
     */
    public function teardown(ExampleNode $example, SpecificationInterface $context,
                             MatcherManager $matchers, CollaboratorManager $collaborators)
    {
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return 50;
    }
}
