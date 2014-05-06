# PHPSpec2 coduo matcher extension

This extension integrate [Coduo PHP Matcher](https://github.com/coduo/php-matcher) into [PHPSpec](https://github.com/phpspec/phpspec)

##Installation

Add to your composer.json

```
require: {
   "coduo/phpspec-matcher-extension": "dev-master",
   "coduo/php-to-string": "1.0.*@dev"
}
```

##Usage

Configure phpspec

```
# phpspec.yml
extensions:
  - Coduo\PhpSpec\MatcherExtension
```

Write spec:

```php
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
```

From now you should be able to use additional matcher ``matchPattern`` in PHPSpec.
