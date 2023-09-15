# CHANGELOG

## [v5.1.0](https://github.com/softspring/mailer-bundle/releases/tag/v5.1.0)

### Upgrading

*Nothing to do on upgrading*

### Commits

- [c7d0acc](https://github.com/softspring/mailer-bundle/commit/c7d0accade76c4e80ec9ca8e9c8343b59ec2652c): Upgrade softspring dependencies to ^5.1
- [d8250c5](https://github.com/softspring/mailer-bundle/commit/d8250c5c95086482f49d88d3423d76262fda8495): Style fix
- [6307ca0](https://github.com/softspring/mailer-bundle/commit/6307ca0b5d3e65aa6f960851d519c68f903d4517): Updates for SF6
- [9997404](https://github.com/softspring/mailer-bundle/commit/9997404ce53a810afe88b18305230fd6e000d9ce): Fix code style for newer php-cs-fixer versions
- [ff367e1](https://github.com/softspring/mailer-bundle/commit/ff367e1099ecebcfd1888f7676f7762913b75ae8): Configure dependabot and phpmd
- [159ff2a](https://github.com/softspring/mailer-bundle/commit/159ff2a8d34125d21bdfb29d0ffb55d394e802cc): Update changelog for v5.0.6
- [280a1f4](https://github.com/softspring/mailer-bundle/commit/280a1f40445b8e5780d74a340643a68d770031c7): Configure new 5.1 development version
- [9f65721](https://github.com/softspring/mailer-bundle/commit/9f6572116342b2af523c3eb9c72d0d873e280751): Add 5.1 branch alias to composer.json

### Changes

```
 .github/dependabot.yml                             | 11 +++++
 .github/workflows/php.yml                          |  4 +-
 .github/workflows/phpmd.yml                        | 57 ++++++++++++++++++++++
 CHANGELOG.md                                       |  4 --
 README.md                                          |  2 +-
 composer.json                                      | 11 +++--
 docs/1_installation.md                             |  4 +-
 src/Command/ListTemplatesCommand.php               |  2 +-
 .../Compiler/ResolveDoctrineTargetEntityPass.php   |  2 +-
 .../Compiler/TemplateLoadersCompilerPass.php       |  2 +-
 src/DependencyInjection/SfsMailerExtension.php     |  4 +-
 src/Form/Admin/SendTestForm.php                    |  4 +-
 src/Model/EmailHistoryInterface.php                |  6 ---
 src/Template/Loader/ParameterTemplateLoader.php    | 12 ++---
 14 files changed, 92 insertions(+), 33 deletions(-)
```

## [v5.0.5](https://github.com/softspring/mailer-bundle/releases/tag/v5.0.5)

### Upgrading

*Nothing to do on upgrading*

### Commits

- [2f11c94](https://github.com/softspring/mailer-bundle/commit/2f11c94929544e499886c05e82afaa28ff85cd11): Update changelog

### Changes

```
 CHANGELOG.md | 26 +++++++++++++-------------
 1 file changed, 13 insertions(+), 13 deletions(-)
```

## [v5.0.4](https://github.com/softspring/mailer-bundle/releases/tag/v5.0.4)

*Nothing has changed since last v5.0.3 version*

## [v5.0.3](https://github.com/softspring/mailer-bundle/releases/tag/v5.0.3)

*Nothing has changed since last v5.0.2 version*

## [v5.0.2](https://github.com/softspring/mailer-bundle/releases/tag/v5.0.2)

*Nothing has changed since last v5.0.1 version*

## [v5.0.1](https://github.com/softspring/mailer-bundle/releases/tag/v5.0.1)

*Nothing has changed since last v5.0.0 version*

## [v5.0.0](https://github.com/softspring/mailer-bundle/releases/tag/v5.0.0)

*Previous versions are not in changelog*
