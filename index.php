<!--
	Show your galleries. 

	@package smoothgallery
	@version $Id$
	@author Simon Bernard <simon@bernard.cc>
	@copyright Simon Bernard 2013
-->

<!DOCTYPE html>
<html>

<?php
	$ini = parse_ini_file('admin/config.ini', TRUE);
?>

<head>
	<title><?php echo $ini['display_text']['title'] ?></title>
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
</head>

<body>

	<div class="container">

		<?php
			$directories=scandir('static');
			$galleries=array();
			for($i=2; $i<count($directories); $i++)
			{
				$priv_or_pub='public';
				
				if(is_file('static/'.$directories[$i].'/.htaccess'))
					$priv_or_pub='private';
					
				$galleries[$priv_or_pub][$i-2]['name']=htmlentities(str_replace('_', ' ', $directories[$i]));
				$galleries[$priv_or_pub][$i-2]['link']='static/'.$directories[$i];
			}
		?>
	
		<h2><?php echo $ini['display_text']['public_gallery_header'] ?></h2>
		<?php
			if(count(@$galleries['public'])==0)
			{
				echo '<p>' . $ini['display_text']['no_public_gallery_available'] . '</p>';
			}
			else
			{
				foreach($galleries['public'] as $g)
				{
					print('<p><a href="'.$g['link'].'/index.html" target="_blank">'.$g['name'].'</a></p>');
				}
			}
		?>
		
		<h2><?php echo $ini['display_text']['public_gallery_header'] ?></h2>
		<?php
			if(count(@$galleries['private'])==0)
			{
				echo '<p>' . $ini['display_text']['no_private_gallery_available'] . '</p>';
			}
			else
			{
				foreach($galleries['private'] as $g)
				{
					print('<p><a href="'.$g['link'].'/index.html" target="_blank">'.$g['name'].'</a>');
					if(is_file($g['link'].'/'.basename($g['link']).'.zip'))
						echo ' (<a href="' . $g['link'] . '/' . basename($g['link']) . '.zip" target="_blank">' . $ini['display_text']['download_all_pictures_link'] . '</a>)';
					print('</p>');
				}
			}
		?>
	</div>

</div>

</body>
</html>

