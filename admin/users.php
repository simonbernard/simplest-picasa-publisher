<!-- 
	Manage your users.

	@package smoothgallery
	@version $Id$
	@author Simon Bernard <simon@bernard.cc>
	@copyright Simon Bernard 2013
-->


<!DOCTYPE HTML>
<html>

<?php
	$ini = parse_ini_file('config.ini', TRUE);
?>

<head>
	<title><?php echo $ini['display_text']['admin_title'] ?></title>
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
</head>

<body>

<div class="container">

	<div>
		<ul class="nav nav-pills">
			<li class="active"><a href="users.php"><?php echo $ini['display_text']['admin_users_header'] ?></a></li>
			<li><a href="galleries.php"><?php echo $ini['display_text']['admin_galleries_header'] ?></a></li>
			<li><a href="permissions.php"><?php echo $ini['display_text']['admin_permissions_header'] ?></a></li>
		</ul>
	</div>

	<div>
		<h2><?php echo $ini['display_text']['admin_users_header'] ?></h2>
		<table class="table">
		<tr><th><?php echo $ini['display_text']['admin_users_user_label'] ?></th><th><?php echo $ini['display_text']['admin_users_password_label'] ?></th><th>&nbsp;</th></tr>
		<?php
			// actions
			if(isset($_GET['action']) && $_GET['action']=='delete')
			{
				$ufile=file_get_contents($ini['directories']['protected_dir'] . '/users');
				$users=unserialize($ufile);
				unset($users[$_GET['name']]);
				$ufile=serialize($users);
				file_put_contents($ini['directories']['protected_dir'] . '/users', $ufile);
			}
			if(isset($_GET['action']) && $_GET['action']=='save')
			{
				$ufile=file_get_contents($ini['directories']['protected_dir'] . '/users');
				$users=unserialize($ufile);
				if(!is_array($users))
				{
					$users=array();
				}
				$user=array();
				$user['name']=$_GET['name'];
				$user['password']=$_GET['password'];
				if(!array_key_exists($user['name'], $users))
				{
					$users[$user['name']]=$user;
					$ufile=serialize($users);
					file_put_contents($ini['directories']['protected_dir'] . '/users', $ufile);
				}
			}
			
			// display
			$ufile=file_get_contents($ini['directories']['protected_dir'] . '/users');
			$users=unserialize($ufile);
			if(is_array($users))
			{
				foreach($users as $u)
				{
					print('<tr><td>'.$u['name'].'</td><td>'.$u['password'].'</td><td><a href="users.php?action=delete&name='.$u['name'].'">' . $ini['display_text']['admin_users_delete_label'] . '</a>');
				}
			}
		?>
		</table>
		
		<form action="users.php" method="get">
			<?php echo $ini['display_text']['admin_users_user_label'] ?><br />
			<input type="text" name="name"/><br />
			<?php echo $ini['display_text']['admin_users_password_label'] ?><br />
			<input type="text" name="password"/><br />
			<input type="hidden" name="action" value="save"/><br />
			<input type="submit" value="<?php echo $ini['display_text']['admin_submit_label'] ?>" class="btn btn-primary">
		</form>
	</div>

</div>

</body>
</html>
