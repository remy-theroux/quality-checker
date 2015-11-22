Quality checker  ![Travis CI status](https://travis-ci.org/remy-theroux/quality-checker.svg?branch=master "Travis CI status")
===============

A PHP command line quality tools. It lets you check your code quality with a single yaml file.
Supported tools are :
- PHP code sniffer (PHPCS)
- PHP mess detector (PHPMD)
- ... many more are coming

Installing
--
```sh
$ composer require remy-theroux/quality-checker
```

Configuring
--
```sh
$ mv vendor/remy-theroux/quality-checker/.qualitychecker.yml.dist .qualitychecker.yml
```

Full configuration of tasks is available here

```yml
parameters:
  tasks: [phpcs, phpmd]

  # [PHPCS](https://github.com/squizlabs/PHP_CodeSniffer) configuration
  phpcs:
    # Could be PEAR, PHPCS, PSR1, PSR2, Squiz, Zend or a directory with a ruleset './vendor/iadvize/php-convention/phpcs/Iadvize'
    standard: PSR2
    paths: [./src]
    show_warnings: true
    tab_width: 2
    ignore_patterns: []
    sniffs: []
    timeout: 180

  # [PHPMD](http://http://phpmd.org/) configuration
  phpmd:
    paths: [./src/]
    format: text
    rulesets: [cleancode, codesize, controversial, design, naming, unusedcode]
    suffixes: [php]
    timeout: 180
    
  # [PHPUNIT](https://phpunit.de/) configuration, only use phpunit.xml configuration
  phpunit:
    timeout: 180
```

Running
--
Start all configured tasks, a status code 0 is returned if all tasks are successfull else -1 is returned.
Quality checker is searching for .qualitychecker.yml at current location.

```sh
$ ./vendor/bin/qualitychecker
```

TODO
--
* Add logs during execution
* Add PHPCPEC support
* Add BEHAT support
