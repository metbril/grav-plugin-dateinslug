<?php
namespace Grav\Plugin;

use Grav\Common\Plugin;
use Grav\Plugin\Admin\Admin;
use RocketTheme\Toolbox\Event\Event;

/**
 * Class DateInSlugPlugin
 * @package Grav\Plugin
 */
class DateInSlugPlugin extends Plugin
{
    /**
     * @return array
     *
     * The getSubscribedEvents() gives the core a list of events
     *     that the plugin wants to listen to. The key of each
     *     array section is the event that the plugin listens to
     *     and the value (in the form of an array) contains the
     *     callable (or function) as well as the priority. The
     *     higher the number the higher the priority.
     */
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0]
        ];
    }

    /**
     * Initialize the plugin
     */
    public function onPluginsInitialized()
    {
        // Don't proceed if we are in the admin plugin
        if ($this->isAdmin()) {
            return;
        }

        // Enable the main event we are interested in
        $this->enable([
            'onPageProcessed' => ['onPageProcessed', 0]
        ]);
    }

    /**
     * Do some work for this event, full details of events can be found
     * on the learn site: http://learn.getgrav.org/plugins/event-hooks
     *
     * @param Event $e
     */
    public function onPageProcessed(Event $e)
    {
        $page = $e['page'];

        // only parse pages with 'item' template (blog entries)
        $template = $page->template();
        if (!($template == 'item')) {
            return;
        }

        $header = $page->header();
        $date = isset($header->date) ? date('Y/m/d', strtotime($header->date)) : date('Y/m/d');
        $slug = $page->slug();
        $parent = $page->parent();
        // if ($parent->home()) {
        //     $parent_route = "";
        // } 
        // else {
        //     $parent_route = $parent->route();
        // }
        $parent_route = "";
        $route = $parent_route.'/'.$date.'/'.$slug;
        $header->routes['default'] = $route;
        $page->route($route);
    }
}
