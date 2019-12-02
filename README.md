# DnsUpdater 
![GitHub Workflow Status](https://img.shields.io/github/workflow/status/amneale/dns-updater/tests?style=flat-square)
[![Code Climate](https://img.shields.io/codeclimate/maintainability/amneale/dns-updater?style=flat-square)](https://codeclimate.com/github/amneale/dns-updater)
[![Test Coverage](https://img.shields.io/codecov/c/github/amneale/dns-updater?style=flat-square)](https://codeclimate.com/github/amneale/dns-updater/coverage)

## Install
Via Composer
``` bash
$ composer require amneale/dns-updater
```

## Usage
Updating the base A record for a domain using automatic IP resolution
``` bash
$ bin/update-dns domain.name @
```

Updating a record type (e.g. CNAME) with a given value
``` bash
$ bin/update-dns domain.name www --type=CNAME value=domain.name
```

Updating an A record with no interaction and no output (e.g. for a CRON job)
``` bash
$ bin/update-dns domain.name test --adapter=digitalocean --params=<DIGITAL_OCEAN_ACCESS_TOKEN> --quiet
```

For more information about available options, run
``` bash
$ bin/update-dns --help
```


## Testing
Both [phpspec](http://www.phpspec.net) and [behat](http://behat.org) are used to test this library.
``` bash
$ make test
```
