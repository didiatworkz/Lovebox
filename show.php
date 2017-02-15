<!DOCTYPE html>
<html lang="de">
	<head>
		<meta charset="utf-8">
<?php
			// System File
				include('_config.php');
			// Setting I
				$set=$db->query("SELECT * FROM settings WHERE userID='1'");
				$set = $set->fetchArray(SQLITE3_ASSOC);
				$set_refresh = $set['refresh'];
			// Refresh
				if (isset($_GET['start'])) {
					$start = (int) $_GET['start'];
				}
				else {
					$start = 0;
				}
				$wert = $start + 12;
				if($set_refresh == '1') {
					$result = $db->query("SELECT * FROM number");
					$inhalt = count($result);
						if($wert < $inhalt){
							$site = 'show.php?start='.$wert;
						}
						else {
							$site = 'show.php';
						}
					$time = $set['refresh_time'];
					$timescript = 1000*$set['refresh_time']-1000;
					redirect($site,$time);
				}
				else {
					echo '';
					//Emergency Refresh
						redirect("show.php",60);
				}
			// Set Style
				echo'
				<style>
					.love {
						padding-bottom: '.$set['style'].'px;
					}
				</style>';
?>
    <title>Lovebox</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="Expires" content="-1">
    <!-- Bootstrap -->
    <link href="css/bootstrap.css?v=<?php echo $set["css"]; ?>" rel="stylesheet">
		<link href="css/font-awesome.min.css" rel="stylesheet">
	</head>
	<body>
		<div class="container">
			<div class="row row-offcanvas row-offcanvas-right">
			<?php
				$ds=$db->query("SELECT * FROM number ORDER BY CAST(number AS SIGNED) LIMIT ".$start.",12");
				$anz=count($ds);
				if($anz) {
					while($ausgabe = $ds->fetchArray(SQLITE3_ASSOC)) {
						echo'<div class="fade-in col-md-3 love center-block">'.$ausgabe["number"].'</div>
							';
					}
				}
			?>
			</div>
		</div>
		<?php
		$text = $set['banner_text'];
		if($set['banner'] == 1) {
			echo'
			<footer class="footer_index">
				<div class="container">
					<h1>';
				if($set['blink'] == 1) {
				$text = '<span class="blink">'.$text.'</span>';
				}
					if($set['arrow'] == 1) {
					$text = '<i class="fa fa-arrow-left rot-left"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$text.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-arrow-left rot-left"></i>';
					}
					elseif($set['arrow'] == 2) {
					$text = '<i class="fa fa-arrow-down"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$text.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-arrow-down"></i>';
					}
					elseif($set['arrow'] == 3) {
					$text = '<i class="fa fa-arrow-right rot-right"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$text.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-arrow-right rot-right"></i>';
					}
				echo $text.'</h1>
				</div>
			</footer>
			';
		}
		?>
		<script src="js/jquery.js"></script>
		<script>
		var $message = $('.love');
		$message.addClass('fade-in');
		setTimeout(function(){
		   $message.removeClass('fade-in').addClass('fade-out');
		}, <?php echo $timescript; ?>);
		</script>
		<?php $db->close(); ?>
	</body>
</html>
