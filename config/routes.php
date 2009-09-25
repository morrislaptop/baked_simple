<?php
	// Connect everything to the CMS
	$routes = Cache::read('routes_list');
	if ( Configure::read() || $routes === false )
	{
		$routes = array();

		// Controllers
	    $controllers = Configure::listObjects('controller');
	    foreach ($controllers as &$value) {
	        $routes[] = Inflector::underscore($value);
		}

		// Plugins
	    $plugins = Configure::listObjects('plugin');
	    foreach ($plugins as &$value) {
	        $routes[] = Inflector::underscore($value);
		}

	    $routes[] = 'admin';
	    $routes = implode('/|', $routes);
	    Cache::write('routes_list', $routes);
	}
	Router::connect('(?!' . $routes . ')(.*)', array('plugin' => 'baked_simple', 'controller' => 'nodes', 'action' => 'display'));
?>