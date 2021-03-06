Elcweb CommonBundle 
======================
[![Build Status](https://img.shields.io/travis/elcweb/common-bundle.svg)](https://travis-ci.org/elcweb/common-bundle)
[![Total Downloads](https://img.shields.io/packagist/dt/elcweb/common-bundle.svg)](https://packagist.org/packages/elcweb/common-bundle)
[![Latest Stable Version](https://img.shields.io/packagist/v/elcweb/common-bundle.svg)](https://packagist.org/packages/elcweb/common-bundle)

Installation
------------
Install the latest version with

```bash
$ composer require elcweb/common-bundle
```

Usage
-----
To store all DateTime as UTC DateTime add this to config.yml

```
doctrine:
    dbal:
        types:
            datetime: Elcweb\CommonBundle\DBAL\Types\UTCDateTimeType
```

License
-------

    The MIT License (MIT)

    Copyright (c) 2013 Etienne Lachance <et@etiennelachance.com>

    Permission is hereby granted, free of charge, to any person obtaining a copy
    of this software and associated documentation files (the "Software"), to deal
    in the Software without restriction, including without limitation the rights
    to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
    copies of the Software, and to permit persons to whom the Software is
    furnished to do so, subject to the following conditions:

    The above copyright notice and this permission notice shall be included in
    all copies or substantial portions of the Software.

    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
    IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
    FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
    AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
    LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
    OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
    THE SOFTWARE.
