# sitemon
benchmarks given websites and comparing performance of the first website against the others.
sends email if benchmarked site is slower then at least on of the other sites.
sends text message(sms) if benchmarked site is twice as slow as at least on of the others.
there is just info in console about sending text, an email is actually sent.

## installation
```
git clone https://github.com/blasbike/sitemon.git
cd sitemon
composer install
```

## config
setup email address in Sitemon\Sitemon.php

## run from console
```
./bin/sitemon site1 [site2 ...]
```

## run on a web
navigate to
```
http://localhost/sitemon/public
```
type in one URL into the input and some URLs to the textarea, Submit.


## tests
```
./vendor/bin/phpunit --colors tests/
```

