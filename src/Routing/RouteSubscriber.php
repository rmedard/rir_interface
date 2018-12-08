<?php
/**
 * Created by PhpStorm.
 * User: medar
 * Date: 08/12/2018
 * Time: 01:18
 */

namespace Drupal\rir_interface\Routing;


use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

class RouteSubscriber extends RouteSubscriberBase {

    /**
     * Alters existing routes for a specific collection.
     *
     * @param \Symfony\Component\Routing\RouteCollection $collection
     *   The route collection for adding routes.
     */
    protected function alterRoutes(RouteCollection $collection) {
        $admin_routes = ['view.adverts.page_manage_adverts'];
        foreach ($collection->all() as $name => $route) {
            if (in_array($name, $admin_routes)) {
                $route->setOption('_admin_route', true);
            }
        }
    }
}