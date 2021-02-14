<?php
$routes->group('/api', function($routes)
{
	$routes->group('pu', function($routes)
	{
		$class = 'Api\Pu\\';
		$routes->get('(:any)', $class.'Get::$1');
		$routes->post('(:any)', $class.'Post::$1');
		$routes->put('(:any)', $class.'Put::$1');
		$routes->delete('(:any)', $class.'Delete::$1');
	});
	$routes->group('pr', function($routes)
	{
		$class = 'Api\Pr\\';
		$routes->get('(:any)', $class.'Get::$1');
		$routes->post('(:any)', $class.'Post::$1');
		$routes->put('(:any)', $class.'Put::$1');
		$routes->delete('(:any)', $class.'Delete::$1');
	});
});
?>