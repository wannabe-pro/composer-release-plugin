<?php

namespace WannaBePro\Composer\Plugin\Release;

use ArrayIterator;
use Composer\Factory;
use Composer\Json\JsonFile;
use Composer\Package\Locker;
use Composer\Plugin\PluginInterface;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;
use Composer\Util\Filesystem;
use WannaBePro\Composer\Plugin\Release\Copy\Builder as CopyBuilder;
use WannaBePro\Composer\Plugin\Release\Mapper\Mapper;
use WannaBePro\Composer\Plugin\Release\Mapper\MapperIterator;
use WannaBePro\Composer\Plugin\Release\Mapper\Rule;
use WannaBePro\Composer\Plugin\Release\Mapper\RuleIterator;

/**
 * Composer plugin that allows generate and update builds witch package.
 *
 * When installed, this plugin will look for a "build-plugin" ("build-plugin" for "-dev" mode) key in the composer
 * configuration's "extra" section. The value for this key is a set of options configuring the plugin.
 *
 * Configuration value is named list of build targets names. For every name specified:
 * * "builder" - the builder class name, it's required attribute of building configuration;
 * * "composer" - the composer package json for specific build, this may be a file name in package or infile-fragment;
 * * "map" - the rules set for file mapping in build target.
 *
 * Plugin duplicate composer install and update actions for shadow copy of package on virtual path used specific
 * composer package of build if is set. After that action plugin map files in virtual patch and give it iterator for
 * specified builder by name.
 *
 * @code
 * {
 *     "require": {
 *         "wanna-be-pro/composer-release": "*"
 *     },
 *     "extra": {
 *         "build-plugin": {
 *             "ProductionBuildTargetName": {
 *                 "builder": "SpecificBuilderName",
 *                 "composer": "SpecificComposerPackage",
 *                 "mapper": "SpecificFilesMapRules"
 *             }
 *         },
 *         "build-plugin-dev": {
 *             "DevelopmentBuildTargetName": {
 *                 "builder": "SpecificBuilderName",
 *                 "composer": "SpecificComposerJson",
 *                 "mapper": "SpecificFilesMapRules"
 *             }
 *         }
 *     }
 * }
 * @endcode
 */
class Plugin implements PluginInterface, EventSubscriberInterface
{
    /**
     * @var string[] The builders classes.
     */
    protected static $builders = [
        'copy' => CopyBuilder::class,
    ];

    /**
     * Add builder.
     *
     * @param string $name The builder name.
     * @param string $builder The builder class name.
     */
    public static function addBuilder($name, $builder)
    {
        if (class_exists($builder, true) && in_array(Builder::class, class_parents($builder))) {
            self::$builders[$name] = $builder;
        }
    }

    /**
     * The callback priority.
     */
    const CALLBACK_PRIORITY = 60000;

    /**
     * @var \Composer\Composer The composer.
     */
    protected $composer;

    /**
     * @var \Composer\IO\IOInterface The IO interface.
     */
    protected $io;

    /**
     * @inheritdoc
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        $this->composer = $composer;
        $this->io = $io;
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            ScriptEvents::POST_INSTALL_CMD => ['onCmd', self::CALLBACK_PRIORITY],
            ScriptEvents::POST_UPDATE_CMD  => ['onCmd', self::CALLBACK_PRIORITY],
        ];
    }

    /**
     * Install/update command action handler.
     *
     * @param Event $event
     */
    public function onCmd(Event $event)
    {
        $extra = $this->composer->getPackage()->getExtra();
        $key = $event->isDevMode() && array_key_exists('build-plugin-dev', $extra)
            ? 'build-plugin-dev'
            : 'build-plugin';
        $mappers = $this->parseConfig(array_key_exists($key, $extra) && is_array($extra[$key]) ? $extra[$key] : []);
        foreach ($mappers as $mapper) {
            $builder = $mapper->getBuilder();
            $builder->build($mapper, $event->getName() === ScriptEvents::POST_UPDATE_CMD);
        }
    }

    /**
     * Parse releases.
     *
     * @param array $config The config.
     *
     * @return MapperIterator
     */
    protected function parseConfig(array $config)
    {
        return new MapperIterator(
            new ArrayIterator(
                array_map(
                    function ($name, $release) {
                        return is_array($release) ? $this->parseRelease($name, $release) : null;
                    },
                    array_keys($config),
                    array_values($config)
                )
            )
        );
    }

    /**
     * Parse release.
     *
     * @param string $name The release name.
     * @param array $release The release config.
     *
     * @return Mapper|null
     */
    protected function parseRelease($name, array $release)
    {
        $config = array_key_exists('composer', $release) ? $release['composer'] : Factory::getComposerFile();
        $relativePath = $this->composer->getConfig()->get('vendor-dir') . DIRECTORY_SEPARATOR . $name;
        (new Filesystem())->ensureDirectoryExists($relativePath);
        $path = realpath($relativePath);
        $composer = (new Factory())->createComposer($this->io, $config, true, $path);
        $composer->setAutoloadGenerator(new AutoloadGenerator($composer->getEventDispatcher(), $this->io));
        $composer->setLocker(new Locker(
            $this->io,
            new JsonFile($path . DIRECTORY_SEPARATOR . 'composer.lock'),
            $composer->getRepositoryManager(),
            $composer->getInstallationManager(),
            JsonFile::encode(is_string($config) && is_file($config) ? (new JsonFile($config))->read() : $config)
        ));
        $mapper = array_key_exists('mapper', $release) ? $release['mapper'] : [];
        $builder = array_key_exists('builder', $release) ? $release['builder'] : null;
        $isBuilder = is_string($builder) && array_key_exists($builder, self::$builders);

        return $isBuilder
            ? new Mapper(
                new self::$builders[$builder]($name, $composer, $this->io),
                new RuleIterator(
                    new ArrayIterator(
                        array_map(
                            function ($pattern, $result) {
                                return new Rule($pattern, $result);
                            },
                            array_keys($mapper),
                            array_values($mapper)
                        )
                    )
                ),
                $this->composer
            )
            : null;
    }
}
