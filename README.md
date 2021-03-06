Quality checker  ![Travis CI status](https://travis-ci.org/remy-theroux/quality-checker.svg?branch=master "Travis CI status")
===============

A PHP command line quality tools. It lets you check your code quality with a single yaml file.
Supported tools are :
- PHP code sniffer - [PHPCS](https://github.com/squizlabs/PHP_CodeSniffer)
- PHP mess detector - [PHPMD](http://http://phpmd.org/)
- PHP unit - [PHPUNIT](https://phpunit.de/)
- PHP spec - [PHPSPEC](http://phpspec.readthedocs.org/en/latest/)
- ... many more are coming

Installing
--
```sh
$ composer require --dev remy-theroux/quality-checker
```

You must require each tool you want to use in your own project.
Binaries will be executed from your vendor/bin directory.

Configuring
--
```sh
$ mv vendor/remy-theroux/quality-checker/.qualitychecker.yml.dist .qualitychecker.yml
```

Full configuration of tasks is available here

```yml
parameters:
  tasks: [phpcs, phpmd]

  # PHPCS configuration
  phpcs:
    # Could be PEAR, PHPCS, PSR1, PSR2, Squiz, Zend or a directory with a ruleset './vendor/iadvize/php-convention/phpcs/Iadvize'
    standard: PSR2
    paths: [./src]
    show_warnings: true
    tab_width: 2
    ignore_patterns: []
    sniffs: []
    timeout: 180

  # PHPMD configuration
  phpmd:
    paths: [./src/]
    format: text
    rulesets: [cleancode, codesize, controversial, design, naming, unusedcode]
    suffixes: [php]
    timeout: 180
    
  # PHPUNIT configuration, only use phpunit.xml configuration file
  phpunit:
    timeout: 180
    
  # PHPSPEC configuration, only use a yml configuration file, file name can be configured
  phpspec:
    config: ./path/to/config/phpspec.yml (default to .phpspec.yml)
    verbose: true
    quiet: true
    timeout: 180
```

Running
--
Start all configured tasks, a status code 0 is returned if all tasks are successfull else -1 is returned.
Quality checker is searching for .qualitychecker.yml at current location.

```sh
$ ./vendor/bin/qualitychecker
```

Contact
--
Feel free to contact us on github for improvment, bugs or simply to hug us.

TODO
--
* Add logs during execution
* Add BEHAT support

