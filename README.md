Themey-Gen
=================

This is a theme generator and integrator for [Yii2](https://github.com/yiisoft/yii2) framework applications. It can 
generate files an classes required to seamless integrate an HTML template with the
Yii2 Application.

[![Latest Stable Version](https://poser.pugx.org/coreit/themey-gen/v/stable)](https://packagist.org/packages/coreit/themey-gen)
[![Total Downloads](https://poser.pugx.org/coreit/themey-gen/downloads)](https://packagist.org/packages/coreit/themey-gen)
[![Latest Unstable Version](https://poser.pugx.org/coreit/themey-gen/v/unstable)](https://packagist.org/packages/coreit/themey-gen)
[![License](https://poser.pugx.org/coreit/themey-gen/license)](https://packagist.org/packages/coreit/themey-gen)
[![Dependency Status](https://www.versioneye.com/user/projects/585e6c304b26f6003ec144fc/badge.svg)](https://www.versioneye.com/user/projects/585e6c304b26f6003ec144fc)

Installation
------------
The preferred way to install this tool through [composer](http://getcomposer.org/download/).

Either run

```
composer require coreit/themey-gen
```
or add

```json
"coreit/themey-gen" : "*"
```

to the require section of your application's `composer.json` file.

Usage
-----
The tool is accessible through terminal, to access it globally you can add `/bin` into your $PATH
You can then run it in command line using `themey`

Before running the command the current directory should be that of the required application either `frontend` or `backend` for the 
advanced Yii2 template or root directory of the basic template.

Contributing
------------
Themey-Gen is an open source project, you can fork the project and submit pull requests.

Resources
---------
  * [Report issues](https://github.com/ramaj93/themey-gen/issues) and
    [send Pull Requests](https://github.com/ramaj93/themey-gen/pulls)
    in the [main Themey-Gen repo](https://github.com/ramaj93/themey-gen)

Credits
-------

*`Symphony\Console` is a PHP Console application framework. Find sources and license at https://github.com/symfony/symfony
*`paquettg/php-html-parser` An HTML DOM parser. It allows you to manipulate HTML. Find tags on an HTML page with selectors just like jQuery.
you can find it at https://github.com/paquettg/php-html-parser

License
-------

The MIT License (MIT). Please see [License File](LICENSE) for more information.
