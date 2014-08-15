<!-- 
	Manage your gallery access.

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
				<li><a href="users.php">Users</a></li>
				<li class="active"><a href="galleries.php">Galleries</a></li>
				<li><a href="permissions.php">Permissions</a></li>
			</ul>
		</div>

		<div>
			<h2><?php echo $ini['display_text']['admin_galleries_header']; ?></h2> 
			<form action="galleries.php" method="post">
				<table class="table">
					<?php
					// actions
					if (isset($_POST['action']) && $_POST['action'] == 'save') {
						foreach (array_keys($_POST['gallery']) as $g) {
							if ($_POST['gallery'][$g] == 'public') {
								if (is_file('../' . $ini['directories']['gallery_dir'] . '/' . $g . '/.htaccess'))
									unlink('../' . $ini['directories']['gallery_dir'] . '/' . $g . '/.htaccess');
								if (is_file('../' . $ini['directories']['gallery_dir'] . '/' . $g . '/.htpasswd'))
									unlink('../' . $ini['directories']['gallery_dir'] . '/' . $g . '/.htpasswd');
							}
							else {
								if (is_file('../' . $ini['directories']['gallery_dir'] . '/' . $g . '/.htpasswd'))
									unlink('../' . $ini['directories']['gallery_dir'] . '/' . $g . '/.htpasswd');
								$htaccess = 'AuthUserFile ' . dirname(__FILE__) . '/../' . $ini['directories']['gallery_dir'] . '/' . $g . '/.htpasswd
AuthGroupFile /dev/null
AuthName "Password Protected Area"
AuthType Basic
<limit GET POST>
require valid-user
</limit>
';
								$fp = fopen('../' . $ini['directories']['gallery_dir'] . '/' . $g . '/.htaccess', 'w');
								fclose($fp);
								file_put_contents('../' . $ini['directories']['gallery_dir'] . '/' . $g . '/.htaccess', $htaccess);
							}
						}
					}

					// display
					$directories = scandir('../' . $ini['directories']['gallery_dir']);
					$galleries = array();
					for ($i = 2; $i < count($directories); $i++) {
						$galleries[$directories[$i]] = 'public';
						if (is_file('../' . $ini['directories']['gallery_dir'] . '/' . $directories[$i] . '/.htaccess'))
							$galleries[$directories[$i]] = 'private';
					}

					print('<tr><th>&nbsp;</th><th>' . $ini['display_text']['admin_galleries_private_label'] . '</th><th>' . $ini['display_text']['admin_galleries_public_label'] . '</th></tr>');
					foreach (array_keys($galleries) as $g) {
						$private = @$galleries[$g] == 'private' ? 'checked="checked"' : '';
						$public = @$galleries[$g] == 'public' ? 'checked="checked"' : '';
						print('<tr><td>' . $g . '</td>');
						print('<td><input type="radio" name="gallery[' . $g . ']" value="private" ' . $private . ' /></td>');
						print('<td><input type="radio" name="gallery[' . $g . ']" value="public" ' . $public . ' /></td>');
						print('</tr>');
					}
					?>
				</table>
				<input type="hidden" name="action" value="save">
				<input type="submit" class="btn btn-primary" value="<?php echo $ini['display_text']['admin_submit_label']; ?>">
			</form> 

		</div>

	</div>
</div>
</body>
</html>
