<?php

namespace Coduo\PhpSpec;

use Coduo\PHPMatcher\Matcher;
use Coduo\PhpSpec\Runner\Maintainer\StringPatternMatcherMaintainer;
use PhpSpec\Extension\ExtensionInterface;
use PhpSpec\ServiceContainer;

class MatcherExtension implements ExtensionInterface
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
