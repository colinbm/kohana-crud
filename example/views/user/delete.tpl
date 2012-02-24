<h1>Delete User <small>{$object->username}</small></h1>

<div class="alert alert-block alert-error">

	<h2>{$object->username} {if $object->first_name}({$object->first_name} {$object->last_name}){/if}</h2>

	<p>Are you sure you want to delete this user? This cannot be undone!</p>

	<div class="alert-actions">
		<a class="btn btn-small btn-info" href="{url route=user_index}">No, take me back</a>
		<form class="inline" action="{url route=user_delete username=$object->username confirm=confirm}" method="post"><button class="btn btn-small btn-danger">Yes, delete this user</button></form>
	</div>
</div>