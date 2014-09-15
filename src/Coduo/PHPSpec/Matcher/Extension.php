<?php

namespace Coduo\PhpSpec\Matcher;

use Coduo\PHPMatcher\Matcher;
use Coduo\PhpSpec\Matcher\Runner\Maintainer\StringPatternMatcherMaintainer;
use PhpSpec\Extension\ExtensionInterface;
use PhpSpec\ServiceContainer;

class Extension implements ExtensionInterface
{
    /**
     * @param ServiceContainer $container
     */
    public function load(ServiceContainer $container)
    {
        $container->set('runner.maintainers.string_pattern_matcher_maintainer', function ($c) {
            return new StringPatternMatcherMaintainer();
        });
    }
}
