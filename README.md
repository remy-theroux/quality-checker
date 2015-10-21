Quality checker
===============

A PHP command line quality tools. It lets you check your code quality with a single yaml file.
Supported tools are :
- PHP code sniffer (PHPCS)
- PHP mess detector (PHPMD)
- ... many more are coming

Installing & running application
--
```
$ composer require remy-theroux/quality-checker
```

Configuring application
--
```
$ mv qualitychecker.yml.dist qualitychecker.yml
```

Running your application
--
Start all configured tasks
```
$ ./vendor/bin/qualitchecker start
```

