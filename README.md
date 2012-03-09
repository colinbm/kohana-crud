Kohana CRUD
===========

Provides New (**C**reate), Index (**R**ead), Edit (**U**pdate), **D**elete functionality for the given model.

See example/ folder.

Usage
-----

Extends Route class and adds `set_crud()` method. Use like this in your `init.php`:

```php
<?php
Route::set_crud(
    'user',     # route prefix - will create user_index, user_new, user_edit, user_delete    
    'users',    # uri prefix - will create users, users/new, users/<id>/edit, users/<id>/delete    
    'user',     # controller - this controller should extend Controller_Crud    
    true,       # edit alias - if true, also creates /users/<id>, forwarding to /users/<id>/edit    
    'username'  # guid - default is id. routes become /users/<username>/edit, etc 
);
```
Your `controller/user.php` file would then contain:

```php
<?php
class Controller_User extends Controller_Crud {
	protected $model = 'user';
	protected $route = 'user';
	protected $form  = 'Form_User'; # *
	protected $search_fields = array('username', 'first_name', 'last_name', 'email');
}
```

\* See [FormManager](https://github.com/colinbm/kohana-formmanager) module.

See the `examples/views/user` folder for examples of view files. These are Smarty views, but should be easy enough to convert back to PHP. If you're not using my Pagination module, then you can access `$result` instead of `$pagination->result()` and remove all references to `$pagination`.

The markup in the views uses [Bootstrap 2](http://twitter.github.com/bootstrap/).

### Important

There is no authentication provided, so you probably want to extend this in something like Controller_Crud_Admin and add whatever security you want, then extend this class instead.

### Requires

* [Kohana FormManager](https://github.com/colinbm/kohana-formmanager)
* [Kohana Flash](https://github.com/colinbm/kohana-flash)

### Optional
* [Kohana Pagination](https://github.com/colinbm/kohana-pagination)
