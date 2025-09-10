<?php

namespace App\Providers\PopupBuilder;

use Jankx\Foundation\Application;
use Jankx\Support\Providers\ServiceProvider;

/**
 * PopupBuilder Service Provider
 *
 * This service provider handles PopupBuilder functionality integration
 * into the Jankx Framework. It initializes the PopupBuilder classes
 * and manages the popup system lifecycle.
 *
 * @package App\Providers\PopupBuilder
 * @since 1.0.0
 */
class PopupBuilderServiceProvider extends ServiceProvider
{
    /**
     * The version number of the PopupBuilder integration.
     *
     * @var string
     */
    const VERSION = '2.1.2';

    /**
     * Register any application services.
     *
     * @param  \Jankx\Foundation\Application  $app
     * @return void
     */
    public function register(Application $app)
    {
        // Define PopupBuilder constants
        $this->defineConstants();

        // Register PopupBuilder service
        $app->singleton('popupbuilder.service', function ($app) {
            return new \App\Providers\PopupBuilder\PopupBuilderService($app);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @param  \Jankx\Foundation\Application  $app
     * @return void
     */
    public function boot(Application $app)
    {
        // Load the scoped vendor autoload file
        $this->loadVendorAutoload();

        // Initialize PopupBuilder functionality
        $this->registerPopupBuilderHooks();
    }

    /**
     * Define PopupBuilder constants
     *
     * @return void
     */
    protected function defineConstants()
    {
        if (!defined('POPUP_BUILDER_BLOCK_PLUGIN_VERSION')) {
            define('POPUP_BUILDER_BLOCK_PLUGIN_VERSION', self::VERSION);
        }

        if (!defined('POPUP_BUILDER_BLOCK_PLUGIN_URL')) {
            define('POPUP_BUILDER_BLOCK_PLUGIN_URL', get_template_directory_uri() . '/includes/app/PopupBuilder/');
        }

        if (!defined('POPUP_BUILDER_BLOCK_PLUGIN_DIR')) {
            define('POPUP_BUILDER_BLOCK_PLUGIN_DIR', get_template_directory() . '/includes/app/PopupBuilder/');
        }

        if (!defined('POPUP_BUILDER_BLOCK_INC_DIR')) {
            define('POPUP_BUILDER_BLOCK_INC_DIR', get_template_directory() . '/includes/app/PopupBuilder/includes/');
        }

        if (!defined('POPUP_BUILDER_BLOCK_DIR')) {
            define('POPUP_BUILDER_BLOCK_DIR', get_template_directory() . '/includes/app/PopupBuilder/build/blocks/');
        }

        if (!defined('POPUP_BUILDER_BLOCK_API_URL')) {
            define('POPUP_BUILDER_BLOCK_API_URL', 'https://wpmet.com/plugin/popupkit/wp-content/plugins/');
        }

        // Enable Pro features
        if (!defined('POPUP_BUILDER_BLOCK_PRO_PLUGIN_VERSION')) {
            define('POPUP_BUILDER_BLOCK_PRO_PLUGIN_VERSION', '2.1.2');
        }
    }

    /**
     * Load vendor autoload file
     *
     * @return void
     */
    protected function loadVendorAutoload()
    {
        $autoloadPath = POPUP_BUILDER_BLOCK_PLUGIN_DIR . 'scoped/vendor/scoper-autoload.php';
        if (file_exists($autoloadPath)) {
            require_once $autoloadPath;
        }
    }

    /**
     * Register PopupBuilder-specific hooks
     *
     * @return void
     */
    protected function registerPopupBuilderHooks()
    {
        // Load text domain
        add_action('init', [$this, 'loadTextDomain'], 1);

        // Initialize PopupBuilder classes
        add_action('init', [$this, 'initializePopupBuilder'], 5);

        // Handle activation/deactivation
        add_action('after_switch_theme', [$this, 'activatedTheme']);
        add_action('switch_theme', [$this, 'deactivatedTheme']);
    }

    /**
     * Load text domain
     *
     * @return void
     */
    public function loadTextDomain()
    {
        load_theme_textdomain(
            'popup-builder-block',
            get_template_directory() . '/languages'
        );
    }

    /**
     * Initialize PopupBuilder classes
     *
     * @return void
     */
    public function initializePopupBuilder()
    {
        /**
         * Fires before the initialization of the PopupBuilder.
         *
         * This action hook allows developers to perform additional tasks before the PopupBuilder has been initialized.
         * @since 1.0.0
         */
        do_action('pbb/before_init');

        // Initialize PopupBuilder classes
        if (class_exists('App\PopupBuilder\Admin\Admin')) {
            new \App\PopupBuilder\Admin\Admin();
        }
        if (class_exists('App\PopupBuilder\Config\Init')) {
            new \App\PopupBuilder\Config\Init();
        }
        if (class_exists('App\PopupBuilder\Hooks\Init')) {
            new \App\PopupBuilder\Hooks\Init();
        }
        if (class_exists('App\PopupBuilder\Routes\Init')) {
            new \App\PopupBuilder\Routes\Init();
        }
        if (class_exists('App\PopupBuilder\Libs\Init')) {
            new \App\PopupBuilder\Libs\Init();
        }
    }

    /**
     * Handle theme activation
     *
     * @return void
     */
    public function activatedTheme()
    {
        if (class_exists('App\PopupBuilder\Helpers\DataBase')) {
            \App\PopupBuilder\Helpers\DataBase::createDB();
        }
        flush_rewrite_rules();
    }

    /**
     * Handle theme deactivation
     *
     * @return void
     */
    public function deactivatedTheme()
    {
        $timestamp = wp_next_scheduled('pbb_daily_event');
        if ($timestamp) {
            wp_unschedule_event($timestamp, 'pbb_daily_event');
        }
        flush_rewrite_rules();
    }
}