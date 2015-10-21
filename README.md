Quality checker
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
$ mv qualitychecker.yml.dist qualitychecker.yml
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
```


Running
--
Start all configured tasks, a status code 0 is returned if all tasks are ok else -1 is returned

```sh
$ ./vendor/bin/qualitychecker start
```

