<?php

declare(strict_types=1);

return (new \drupol\PhpCsFixerConfigsPhp\Config\Php71())
    ->withRulesFromYaml('./resources/phpcsfixer.rules.yml');
