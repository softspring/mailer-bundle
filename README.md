# Mailer Bundle

![Latest Stable](https://img.shields.io/packagist/v/softspring/mailer-bundle?label=stable&style=flat-square)
![Latest Unstable](https://img.shields.io/packagist/v/softspring/mailer-bundle?label=unstable&style=flat-square&include_prereleases)
![License](https://img.shields.io/packagist/l/softspring/mailer-bundle?style=flat-square)
![PHP Version](https://img.shields.io/packagist/dependency-v/softspring/mailer-bundle/php?style=flat-square)
![Downloads](https://img.shields.io/packagist/dt/softspring/mailer-bundle?style=flat-square)
[![CI](https://img.shields.io/github/actions/workflow/status/softspring/mailer-bundle/ci.yml?branch=6.0&style=flat-square&label=CI)](https://github.com/softspring/mailer-bundle/actions/workflows/ci.yml)

Shared mail template and preview infrastructure for Symfony applications.

The `sfs_mailer:template:list` command loads its template providers only when executed.
If an application overrides its service definition, keep the command name in the `console.command` tag so unrelated console commands do not load Doctrine metadata before cache warmup.

## Armonic

This package is part of [Armonic](https://softspring.es/en/armonic).

## Documentation

[Armonic Documentation](https://armonic.softspring.es/6.0/bundles/mailer-bundle)

## Contributing

Use the standard package commands before sending changes:

```bash
composer fix
composer test
composer test-bc
```

[Report issues](https://github.com/softspring/mailer-bundle/issues) and [send Pull Requests](https://github.com/softspring/mailer-bundle/pulls)

## License

This package is free and released under the [AGPL-3.0 license](LICENSE).
