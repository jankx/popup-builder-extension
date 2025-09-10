<?php

namespace App\Providers\PopupBuilder;

use Jankx\Foundation\Application;

/**
 * PopupBuilder Service
 *
 * This service handles the core PopupBuilder functionality
 * and provides methods for managing popup operations.
 *
 * @package App\Providers\PopupBuilder
 * @since 1.0.0
 */
class PopupBuilderService
{
    /**
     * The application instance.
     *
     * @var \Jankx\Foundation\Application
     */
    protected $app;

    /**
     * Create a new service instance.
     *
     * @param  \Jankx\Foundation\Application  $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Get the application instance.
     *
     * @return \Jankx\Foundation\Application
     */
    public function getApp()
    {
        return $this->app;
    }

    /**
     * Initialize the PopupBuilder service
     *
     * @return void
     */
    public function init()
    {
        // Service initialization logic can be added here
    }

    /**
     * Get PopupBuilder version
     *
     * @return string
     */
    public function getVersion()
    {
        return \App\Providers\PopupBuilder\PopupBuilderServiceProvider::VERSION;
    }

    /**
     * Check if PopupBuilder is active
     *
     * @return bool
     */
    public function isActive()
    {
        return defined('POPUP_BUILDER_BLOCK_PLUGIN_VERSION');
    }
}
