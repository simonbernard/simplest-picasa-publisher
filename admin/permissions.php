<!--
	Manage your gallery permissions. 

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
			<li><a href="users.php"><?php echo $ini['display_text']['admin_users_header'] ?></a></li>
			<li><a href="galleries.php"><?php echo $ini['display_text']['admin_galleries_header'] ?></a></li>
			<li class="active"><a href="permissions.php"><?php echo $ini['display_text']['admin_permissions_header'] ?></a></li>
		</ul>
	</div>

	<div>
		<h2><?php echo $ini['display_text']['admin_permissions_header'] ?></h2>
		<form action="permissions.php" method="post">
		<table class="table">
		<?php
			// actions
			if(isset($_POST['action']) && $_POST['action']=='save')
			{
				$fp = fopen($ini['directories']['protected_dir'] . '/permissions', "w");
				fclose($fp);
				$permissions=array();
				$galleries=array();
				foreach(array_keys($_POST['permission']) as $u)
				{
					foreach(array_keys($_POST['permission'][$u]) as $g)
					{
						// save permissions in array
						$permissions[$u][$g]=true;
						$galleries[$g][]=$u;
					}
				}
				file_put_contents($ini['directories']['protected_dir'] . '/permissions', serialize($permissions));
				
				// create .htpasswd file
				$ufile=file_get_contents($ini['directories']['protected_dir'] . '/users');
				$users=unserialize($ufile);
				if(!is_array($users)) $users=array();

				foreach(array_keys($galleries) as $g)
				{
					$fp = fopen('../' . $ini['directories']['gallery_dir'] . '/'.$g.'/.htpasswd', 'w');
					fclose($fp);
					foreach($galleries[$g] as $u)
					{
						$ret=shell_exec('/usr/bin/htpasswd -b '.dirname(__FILE__).'/../' . $ini['directories']['gallery_dir'] . '/'.$g.'/.htpasswd '.$u.' '.$users[$u]['password']);
						print_r($ret);
					}
				}
			}
			
			// display
			$pfile=file_get_contents($ini['directories']['protected_dir'] . '/permissions');
			$permissions=unserialize($pfile);
			if(!is_array($permissions)) $permissions=array();
			
			$ufile=file_get_contents($ini['directories']['protected_dir'] . '/users');
			$users=unserialize($ufile);
			if(!is_array($users)) $users=array();
			
			$directories=scandir('../' . $ini['directories']['gallery_dir']);
			$galleries=array();
			for($i=2; $i<count($directories); $i++)
			{
				if(is_file('../' . $ini['directories']['gallery_dir'] . '/'.$directories[$i].'/.htaccess'))
					$galleries[]=$directories[$i];
			}
			
			print('<tr><th>&nbsp;</th>');
			foreach($users as $u)
			{
				print('<th>'.$u['name'].'</th>');
			}	
			print('</tr>');
			foreach($galleries as $g)
			{
				print('<tr><td>'.$g.'</td>');
				foreach($users as $u)
				{
					$checked=@$permissions[$u['name']][$g]==1?'checked="checked"':'';
					print('<td><input type="checkbox" name="permission['.$u['name'].']['.$g.']" '.$checked.' /></td>');
				}
				print('</tr>');
			}
		?>
		</table>
		<input type="hidden" name="action" value="save">
		<input type="submit" value="<?php echo $ini['display_text']['admin_submit_label'] ?>" class="btn btn-primary">
		</form>
		
	</div>

</div>

</body>
</html>
