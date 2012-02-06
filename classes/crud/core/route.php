<?php defined('SYSPATH') or die('No direct script access.');

class Crud_Core_Route extends Kohana_Route {

	public static function set_crud($route_name, $uri_prefix, $controller, $edit_alias = false,  $guid='id', $guid_regex = Route::REGEX_SEGMENT) {
		Route::set("{$route_name}_sort", "{$uri_prefix}/sort")
			->defaults(array(
				'controller' => $controller,
				'action'     => 'sort',
		));
		
		Route::set("{$route_name}_index", "{$uri_prefix}(/order/<order>(;<direction>))(/page/<page>(;<per_page>))", array('page' => '\d+', 'per_page' => '\d+', 'direction' => '(desc|asc)'))
			->defaults(array(
				'controller' => $controller,
				'action'     => 'index',
				'guid'       => $guid,
				'page'       => 1,
				'per_page'   => 50,
				'order'      => null,
				'direction'  => 'asc'
		));
		Route::set("{$route_name}_new", "{$uri_prefix}/new")
			->defaults(array(
				'controller' => $controller,
				'action'     => 'new',
				'edit_route' => $route_name . '_edit',
				'guid'       => $guid,
		));
		Route::set("{$route_name}_edit", "{$uri_prefix}/<{$guid}>/edit", array($guid => $guid_regex))
			->defaults(array(
				'controller' => $controller,
				'action'     => 'edit',
				'guid'       => $guid,
		));
		if ($edit_alias) Route::set_alias("{$route_name}_edit_alias", "{$uri_prefix}/<{$guid}>", "{$route_name}_edit");

		Route::set("{$route_name}_delete", "{$uri_prefix}/<{$guid}>/delete(/<confirm>)", array($guid => $guid_regex, 'confirm' => 'confirm'))
			->defaults(array(
				'controller' => $controller,
				'action'     => 'delete',
				'index_route'=> $route_name . '_index',
				'guid'       => $guid,
		));

	}

	public static function set_alias($alias_name, $uri_callback, $target_route_name) {
		$target = Route::get($target_route_name);
		Route::set($alias_name, $uri_callback, $target->regex())->defaults(array(
				'controller' => 'routealias',
				'action'     => 'redirect',
				'route'      => $target_route_name,
				'regex'      => $target->regex(),
				'defaults'   => $target->defaults(),
		));
	}

	public function regex() {
		return $this->_regex;
	}

}
