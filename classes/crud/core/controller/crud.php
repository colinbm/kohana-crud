<?php defined('SYSPATH') or die('No direct script access.');

class Crud_Core_Controller_Crud extends Controller_Auto_Template {
	
	const AUTH_ERROR      = 'You do not have sufficient rights.';
	
	const EDIT_SUCCESS    = 'The {item} has been saved.';
	const EDIT_FAILURE    = 'The {item} couldn\'t be saved. Please check for errors below.';
	const NEW_SUCCESS     = 'The {item} has been created.';
	const NEW_FAILURE     = 'The {item} couldn\'t be created. Please check for errors below.';
	const DELETE_SUCCESS  = 'The {item} has been deleted.';
	
	
	public static $model = 'base';
	
	public static $order_by;
	
	public static $route;
	
	public static $route_params = array();
	
	public static $form;

	public static $search_form;

	public static $item_name;
	
	
	public function before() {
		try {
			parent::before();
		} catch (View_Exception $e) {
			$defaults = $this->request->route()->defaults();
			if ($defaults['action'] == 'sort') {
				$this->auto_render = false;
			} else {
				throw $e;
			}
		}
		$this->authorize();
		
	}
	
	protected function authorize() {
		if (!Auth::instance()->logged_in('admin')) {
			if (class_exists('Flash')) {
				Flash::error($this->message($this::AUTH_ERROR));
			}
			$this->request->redirect(Route::url('default'));
		}
	}
	
	public function action_index($factory=null) {
		if (!$factory) $factory = ORM::factory($this::$model);
		if ($order = $this->request->param('order')) {
			$objects->order_by($order, $this->request->param('direction'));
		}

		if ($this::$search_form) {
			/* @var $search_form Form_Search */
			$search_form = new $this::$search_form();
			if ($search_form->is_submitted()) {
				$search_form->apply_filters($factory);
			}
			$this->template->content->search_form = $search_form;
		}

		if (class_exists('Pagination')) {
			$this->template->content->pagination = new Pagination($factory, $this->request->param('page'), $this::$route.'_index', $this::$route_params, $this->request->param('per_page'));
		} else {
			$this->template->content->result = $factory->find_all();
		}
		
	}
	
	public function action_edit($factory=null, $redirect_params=array()) {
		
		if (!$factory) $factory = ORM::factory($this::$model);
		$object = $factory->where($this->request->param('guid'), '=', $this->request->param($this->request->param('guid')))->find();
		$form = new $this::$form($object->id);

		if ($form->is_submitted()) {
			// id should never be changed.
			if ($object->id) $form->set_value('id', $object->id);
			
			if ($form->submit()) {
				Flash::success(__($this->message($this::EDIT_SUCCESS)));
				$this->request->redirect(Route::get($this->request->param('edit_route'))->uri(array_merge($redirect_params, array($this->request->param('guid') => $form->object->{$this->request->param('guid')}))));
			} else {
				Flash::error(__($this->message($this::EDIT_FAILURE)));
			}
			
		}

		$this->template->content->object = $object;
		$this->template->content->form = $form;
		
	}
	
	public function action_new($override=array(), $redirect_params=array()) {
		$form = new $this::$form();
		
		if ($form->is_submitted()) {
			foreach($override as $k => $v) {
				$form->set_value($k, $v);
			}
			if ($form->submit()) {
				Flash::success(__($this->message($this::NEW_SUCCESS)));
				$this->request->redirect(Route::get($this->request->param('edit_route'))->uri(array_merge($redirect_params, array($this->request->param('guid') => $form->object->{$this->request->param('guid')}))));
			} else {
				Flash::error(__($this->message($this::NEW_FAILURE)));
			}
		}

		$this->template->content->form = $form;

	}

	public function action_delete($redirect_params=array(), $factory=null) {
		if (!$factory) $factory = ORM::factory($this::$model);
		$object = $factory->where($this->request->param('guid'), '=', $this->request->param($this->request->param('guid')))->find();

		if ($this->request->method() == Request::POST && $this->request->param('confirm') === 'confirm') {
			Flash::success(__($this->message($this::DELETE_SUCCESS)));
			$object->delete();
			$this->request->redirect(Route::get($this->request->param('index_route'))->uri($redirect_params));
		}

		$this->template->content->object = $object;

	}
	
	public function action_sort() {
		
		parse_str($_REQUEST['order'], $order);
		$ids_in_new_order = Arr::get($order, $this::$model, array());
		
		$current_order = array();
		foreach(ORM::factory($this::$model)->select('order')->where('id', 'IN', $ids_in_new_order)->find_all() as $object) {
			$current_order[] = $object->order;
		}
		
		// if we have unique order values
		if ($current_order != array_unique($current_order)) {
			$current_order = range(1, count($current_order));
		}
		
		foreach($ids_in_new_order as $key => $id) {
			$item = ORM::factory($this::$model, $id);
			$item->order = $current_order[$key];
			$item->save();
		}
		
		echo "OK\n";
		
	}

	protected function message($string) {
		$item_name = $this::$item_name ? $this::$item_name : $this::$model;
		return str_replace('{item}', $item_name, $string);
	}
	
	
	
}