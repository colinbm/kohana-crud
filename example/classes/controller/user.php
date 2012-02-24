<?php defined('SYSPATH') or die('No direct access allowed.');

class Controller_User extends Controller_Crud {
	public static $model = 'user';
	public static $route = 'user';
	public static $form  = 'Form_User';
	public static $search_fields = array('username', 'first_name', 'last_name', 'email');
}
