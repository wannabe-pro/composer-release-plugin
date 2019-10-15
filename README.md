Composer plugin that allows generate and update builds witch package.

When installed, this plugin will look for a "build-plugin" ("build-plugin" for "-dev" mode) key in the composer configuration's "extra" section.
The value for this key is a set of options configuring the plugin.

Configuration value is named list of build targets names. For every name specified:

* "builder" - the builder class name, it's required attribute of building configuration;
* "composer" - the composer package json for specific build, this may be a file name in package or infile-fragment;
* "map" - the rules set for file mapping in build target.

Plugin duplicate composer install and update actions for shadow copy of package on virtual path used specific composer package of build if is set.
After that action plugin map files in virtual patch and give it iterator for specified builder by name.

```json
{
    "require": {
        "wanna-be-pro/composer-release": "*"
    },
    "extra": {
        "build-plugin": {
            "ProductionBuildTargetName": {
                "builder": "SpecificBuilderName",
                "composer": "SpecificComposerPackage",
                "mapper": "SpecificFilesMapRules",
                "converter": "SpecificConverterName"
            }
        },
        "build-plugin-dev": {
            "DevelopmentBuildTargetName": {
                "builder": "SpecificBuilderName",
                "composer": "SpecificComposerJson",
                "mapper": "SpecificFilesMapRules",
                "converter": "SpecificFilesConverterRules"
            }
        }
    }
}
```

Mapper rule check source file relative path and apply template name for it.
If template name not set or false then its exclude file from release.
By default template name equivalent source and all files will be excluded if not otherwise.

```json
{
    "/^README.md/": true,
    "/(.*)\\.php$/": "$1.inc",
    "/(^|\\/)\\./": false
}
```

This package preset builder named `copy` for simple copy files.
All builders will extend `WannaBePro\Composer\Plugin\Release\Builder` and registered by `WannaBePro\Composer\Plugin\Release\Plugin::addBuilder($name, $builder)` in composer-plugin.

Mapper allow external rules file to manipulate of targets.
Set mapper key as numeric value and napper value as source file name.
This rule file accept `TargetIterator` as  `$iterator` var.
You may append iterator of new targets for builder as `$iterator->getInnerIterator()->append()`.
Use `FiltredFile` as items for disable another filtration rules on this files.

By default `converter` read file as-is. You may set rules like for mapper, but in value set converter config:

```json
{
    "/\\.php$/": [
      "convert.iconv.utf-8.cp1251"
    ],
    "/\\.gitkeep$/": "null"
}
```
