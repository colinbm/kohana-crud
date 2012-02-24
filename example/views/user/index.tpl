<h1>Users</h1>

<div class="dropdown" id="search">
	<a class="btn dropdown-toggle {if $search_form->is_submitted()}btn-info{/if}" data-toggle="dropdown" href="#search">Search <b class="caret"></b> </a>
	<div class="dropdown-menu">
		{$search_form->render()}
	</div>
</div>

<p><a href="{url route=user_new}" class="btn btn-success">add a new user</a></p>

{$pagination->render()}

{if $pagination->count()}
	
	<table class="table table-bordered table-striped table-responsive">
		<thead>
			<tr>
				<th class="avatar"></th>
				<th>Username</th>
				<th>First name</th>
				<th>Last name</th>
				<th>Email</th>
				<th class="narrow"></th>
			</tr>
		</thead>
		<tbody>
			{foreach from=$pagination->result() item=user}
				<tr>
					<td class="avatar"><img src="{$user->avatar_url}" alt="{$user->username}" class="avatar" /></td>
					<td><a href="{url route=user_edit username=$user->username}">{$user->username}</a></td>
					<td>{$user->first_name}</td>
					<td>{$user->last_name}</td>
					<td>{$user->email}</td>
					<td class="narrow">
						<a class="label label-success" href="{url route=user_edit username=$user->username}">edit</a>
						<a class="label label-important" href="{url route=user_delete username=$user->username}">delete</a>
					</td>
				</tr>
			{/foreach}
		</tbody>
	</table>
	
	{$pagination->render()}
	
{/if}
