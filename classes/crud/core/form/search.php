<?php

class Crud_Core_Form_Search extends FormManager {

	public $method = 'get';
	public $custom_view = 'formmanager/search';

	public function apply_filters($factory) {
		self::submit();
		foreach ($this->fields as $field) {
			$field['error'] = false;
			$field['error_text'] = '';
			if ($field['value']) {
				$factory->where($field['column_name'], 'LIKE', '%'.$field['value'].'%');
			}
		}
	}

}