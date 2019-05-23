Composer plugin that allows generate and update builds witch package.

When installed, this plugin will look for a "build-plugin" ("build-plugin" for "-dev" mode) key in the composer configuration's "extra" section.
The value for this key is a set of options configuring the plugin.

Configuration value is named list of build targets names. For every name specified:

* "builder" - the builder class name, it's required attribute of building configuration;
* "composer" - the composer package json for specific build, this may be a file name in package or infile-fragment;
* "map" - the rules set for file mapping in build target.

Plugin duplicate composer install and update actions for shadow copy of package on virtual path used specific composer package of build if is set.
After that action plugin map files in virtual patch and give it iterator for specified builder by class name extending `WannaBePro\Composer\Plugin\Release\Builder`.

```json
{
    "require": {
        "wanna-be-pro/composer-release": "*"
    },
    "extra": {
        "build-plugin": {
            "ProductionBuildTargetName": {
                "builder": "SpecificBuilderClassName",
                "composer": "SpecificComposerPackage",
                "mapper": "SpecificFilesMapRules"
            }
        },
        "build-plugin-dev": {
            "DevelopmentBuildTargetName": {
                "builder": "SpecificBuilderClassName",
                "composer": "SpecificComposerJson",
                "mapper": "SpecificFilesMapRules"
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
    "/.*/": false,
    "/^README.md/": true,
    "/(.*)\\.php$/": "$1.inc"
}
```
