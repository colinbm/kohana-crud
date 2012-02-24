<?php

class Form_User extends FormManager {

	protected $model = 'user';

	public function submit() {
		$success = parent::submit();
		if ($success) {
			$this->save_object();
		}
		return $success;
	}
	
}