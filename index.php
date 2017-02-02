<?php
session_set_cookie_params(36000, '/' );
session_start();
require_once("_config.php");
?>
<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->
	<title>&#10084; Lovebox Settings</title>
	<script src="js/jquery.js"></script>
	<script src="js/bootstrap.js"></script>
	<script src="js/jquery.toaster.js"></script>
  </head>
  <body>
	
  <div class="warp">
	<div class="container">
 <?php
		if(isset($_POST['Login']) && md5($_POST['passwort']) == $Zugangspasswort && $_POST['user'] == $Benutzername) {
		$_SESSION['user'] = $_POST['user'];
		$_SESSION['passwort'] = $Zugangspasswort;
		}
		if(isset($_GET['action']) && $_GET['action']=="logout"){
		if(session_destroy()){
		$logedout=TRUE;
		$_SESSION['passwort']=""; //Falsch setzen des Session Passworts
		}else{
		//Der Logout hat nocht geklappt
		$logedout=FALSE;
		}
		}
		if($_SESSION['passwort'] == $Zugangspasswort && $_SESSION['user']==$Benutzername){
		
			//$result = $db->query("SELECT * FROM number");
			
			$error_time = $set['error'];
			$stat = $db->query("SELECT * FROM settings");
			$stat = $stat->fetchArray(SQLITE3_ASSOC);

?>
      <div class="header">
        <ul class="nav nav-pills pull-right">
        <?php if($stat['static']==1){ ?>
		<li><a href="#" data-toggle="modal" data-target="#Stat"><i class="fa fa-tasks"></i> Statistik</a></li><?php } ?>
		  <li><a href="#" data-toggle="modal" data-target="#myModal"><i class="fa fa-cogs"></i> Einstellungen</a></li>
		  <li><a href="index.php" title="refresh"><i class="fa fa-refresh"></i></a></li>
		  <li class="active"><a href="#" onclick="javascript:window.open('show.php', 'Lovebox_Window','directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=yes,width=1280,height=600')"><i class="fa fa-desktop" aria-hidden="true"></i> Anzeigefenster</a></li>
        </ul>
       <h3 class="text-muted"><a href="index.php">Lovebox Settings</a></h3>
      </div>
	<br />
<?php
if($_POST["addsave"]) {
	$setnumber=$_POST["number"];
	if(!preg_match('/[^a-zA-Z0-9]/', $setnumber)) {
	$datum = date("d.m.Y");
	$zeit = date("H:i:s");
	$check=$db->query("SELECT * FROM number WHERE number='".$setnumber."'");
	$check = $check->fetchArray(SQLITE3_ASSOC);
	$log=$db->query("SELECT * FROM log WHERE number='".$setnumber."'");
	$log = $log->fetchArray(SQLITE3_ASSOC);
	$count = $log['count']+1;
	

	
	if($setnumber != '') {
	if($setnumber!==$check['number']) {
		
			$db->exec("INSERT INTO number (number) values('".$setnumber."')");
			if($setnumber!==$log['number']) {
			$db->exec("INSERT INTO log (number, date, time, count) values('".$setnumber."', '".$datum."', '".$zeit."', '1')"); }
			else {
			$db->exec("UPDATE log SET count='".$count."', date='".$datum."', time='".$zeit."'  WHERE number='".$setnumber."'");
			}
			sysinfo('success', 'Nummer gespeichert');
	} else {
	$db->exec("UPDATE log SET count='".$count."', date='".$datum."', time='".$zeit."' WHERE number='".$setnumber."'");
	sysinfo('danger', 'Diese Nummer existiert schon im System!'); }
	} else {
	sysinfo('danger', 'Eingabe &uuml;berpr&uuml;fen!!'); }
	} else {
	sysinfo('danger', 'Eingabe ung&uuml;ltig!!'); }
	
}

if($_GET[add]) {
	echo'<br /><br /><br /><br />
	<form method="post" action="index.php" enctype="multipart/form-data">
		  <div class="alert alert-info">&nbsp;&nbsp;&nbsp;&nbsp;Nummer eingeben:<br /><div class="col-xs-3"><input type="text" name="number" tabindex="1" autofocus class="form-control"></div><input class="btn btn-default" type="submit" name="addsave" value="speichern" /><br />&nbsp;</div> <br /><br />
		</form><br /><br /><br /><br /><br /><br /><br /><br /><br />';
}
if($_GET["delete"]) {
	$db->query("DELETE FROM number WHERE number='".$_GET["number"]."'");
	sysinfo('warning', 'Nummer gel&ouml;scht!');
}
if($_POST["remove"] AND $_POST['enter'] == $set['clean']) {
	$db->query("DELETE FROM number");
	$db->query("DELETE FROM log");
	echo'<div class="alert alert-warning"><i class="fa fa-chain-broken"></i> Alle Nummern aus dem System entfernt!</div>';
	redirect("index.php","",$error_time);
}
if($_GET["testmode"]) {
	$db->exec("UPDATE settings SET test='1' WHERE userID='1'");
	$db->query("DELETE FROM number");
	$db->exec("INSERT INTO number (number) values('01')");
	$db->exec("INSERT INTO number (number) values('02')");
	$db->exec("INSERT INTO number (number) values('03')");
	$db->exec("INSERT INTO number (number) values('04')");
	$db->exec("INSERT INTO number (number) values('05')");
	$db->exec("INSERT INTO number (number) values('06')");
	$db->exec("INSERT INTO number (number) values('07')");
	$db->exec("INSERT INTO number (number) values('08')");
	$db->exec("INSERT INTO number (number) values('09')");
	$db->exec("INSERT INTO number (number) values('10')");
	$db->exec("INSERT INTO number (number) values('11')");
	$db->exec("INSERT INTO number (number) values('12')");
	echo'<div class="alert alert-warning"><i class="fa fa-arrows"></i> </div>';
	sysinfo('warning', 'Kalibrierung aktiviert!','1');
}
if($_GET["css"]) {
	$zeit = date("H:i:s");
	$db->exec("UPDATE settings SET css='".$zeit."' WHERE userID='1'");
	sysinfo('info', 'CSS Reset erfolgreich');
}
if($_GET["testmode_off"]) {
	$db->exec("UPDATE settings SET test='0' WHERE userID='1'");
	$db->query("DELETE FROM number");
	sysinfo('warning', 'Kalibrierung deaktiviert!','1');
}
if($_POST["saveset"]) {
	$refresh=$_POST["refresh"];
	$static=$_POST["static"];	
	$time=$_POST["refresh_time"];	
	$clean=$_POST["clean"];
	$e_time=$_POST["e_time"];
	$style=$_POST["style"];
	$user=$_POST["user"];
	$banner=$_POST["banner"];
	$banner_text=$_POST["banner_text"];
	$blink=$_POST["blink"];
	$arrow=$_POST["arrow"];
	if($_POST["pass"] !== '') {
	$pass=md5($_POST["pass"]); } else {
	$pass=$set['password']; }
	if($time AND $clean) {					
		$db->exec("UPDATE settings SET username='".$user."', password='".$pass."', refresh='".$refresh."', error='".$e_time."', style='".$style."', static='".$static."', refresh_time='".$time."', clean='".$clean."', banner='".$banner."', banner_text='".$banner_text."', blink='".$blink."', arrow='".$arrow."' WHERE userID='1'");	
		sysinfo('success', 'Einstellungen gespeichert','1');
	} 
else { sysinfo('danger', 'Fehler! - Bitte die Eingaben &uuml;berpr&uuml;fen!'); }

}
	
	
	
	$ds=$db->query("SELECT * FROM number ORDER BY CAST(number AS SIGNED)");
	if ($set['test'] == '1') {
	 echo'<div class="alert alert-warning text-center"><h2><i class="fa fa-th"></i> Kalibrierung ist aktiv!</h2><br /><a class="btn btn-danger" href="index.php?testmode_off=1"><i class="fa fa-times"></i> Kalibrierung deaktivieren</a></div>'; }
	 else {
	$anz=count($ds);
	if($anz) {
	echo'
		<form method="post" action="index.php" enctype="multipart/form-data">
		  <div class="alert alert-info">
		  <div class="input-group">
			<input name="number" tabindex="1" autofocus type="text" class="form-control" placeholder="Nummer eingeben..">
			  <span class="input-group-btn">
				<button class="btn btn-success" type="submit" name="addsave" value="speichern">speichern</button>
			  </span>
			</div></div>
		</form>
		<h4>'.$eintrag.'</h4>
		<table class="table table-striped">
        <thead>
          <tr>
			<th></th>
            <th class="text-center">Nummer</th>
            <th class="text-center">Option</th>
			<th></th>
          </tr>
        </thead>
		<tbody>';
		while($ausgabe = $ds->fetchArray(SQLITE3_ASSOC)) {
			echo'<tr>
            <td width="25%"></td>
            <td class="text-center">'.$ausgabe[number].'</td>
            <td class="text-center"><a class="btn btn-danger" href="index.php?delete=1&number='.$ausgabe[number].'"><i class="fa fa-trash-o"></i> l&ouml;schen</a></td>
			<td width="25%"></td>
		  </tr>
		  ';
		} echo'</tbody>
      </table>';
	} else { echo'<form method="post" action="index.php" enctype="multipart/form-data">
		  <div class="alert alert-info">
		  <div class="input-group">
			<input name="number" tabindex="1" autofocus type="text" class="form-control" placeholder="Nummer eingeben..">
			  <span class="input-group-btn">
				<button class="btn btn-success" type="submit" name="addsave" value="speichern">speichern</button>
			  </span>
			</div></div>
		</form>
		<div class="alert alert-warning"><h2>Keine Eintr&auml;ge</h2></div>'; }
	echo '<!--<div class="pull-right well">
		<form method="post" action="index.php" enctype="multipart/form-data">
		Um die Datenbank zu leeren bitte "'.$set['clean'].'" eingeben<br />
		  <input type="text" name="enter" class="form-control"><input class="btn btn-warning pull-right" type="submit" name="remove" value="ausf&uuml;hren" /> <br /><br />
		</form></div>-->
		
		<div class="row">
  <div class="col-md-4 col-md-offset-8">
  <form method="post" action="index.php" enctype="multipart/form-data">
    <div class="input-group">
      <input placeholder="Bitte '.$set['clean'].' eingeben" type="text" name="enter" class="form-control">
      <span class="input-group-btn">
        <button class="btn btn-warning" name="remove" value="ausf&uuml;hren" type="submit"><i class="fa fa-trash-o"></i>
 Datenbank leeren!</button>
      </span>
    </div>
	</form>
  </div><!-- /.col-lg-6 -->
</div><!-- /.row -->
';
		}

?>

	
	  <!-- Settingsmen&uuml; -->
		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  <div class="modal-dialog">
			<div class="modal-content">
			  <div class="modal-body">
				<?php
				if ($set['test'] == '1'){ $li_sty =''; $li_set ='active'; 
						} else { $li_sty =''; $li_set ='active'; 
						}
				echo'
				<ul class="nav nav-tabs">
						<li class="'.$li_set.'"><a href="#settings" data-toggle="tab">Wall</a></li>
						<li class="'.$li_sty.'"><a href="#style" data-toggle="tab">Backend</a></li>
						</ul>
				<form method="post" action="index.php" enctype="multipart/form-data">
				<div class="tab-content">
				<div class="tab-pane '.$li_set.'" id="settings">
				<table class="table table-striped">
					<thead>
						<td colspan="2"><h4>Lovebox <span class="text-muted">Einstellungen</span></h4></td>
					  </thead>
					<tbody>
					  <tr>
						<td>Auto Refresh</td>
						<td><div class="btn-group" data-toggle="buttons"><label class="btn btn-info '; if ($set['refresh'] == '1') echo 'active'; echo'">
						<input type="radio" name="refresh" id="optionsRadios1" value="1" '; if ($set['refresh'] == '1') echo 'checked="checked"'; echo'>&nbsp;&nbsp;&nbsp;&nbsp;Ja&nbsp;&nbsp;&nbsp;&nbsp;</label><label class="btn btn-info '; if ($set['refresh'] == '0') echo 'active'; echo'"><input type="radio" name="refresh" id="optionsRadios2" value="0" '; if ($set['refresh'] == '0') echo 'checked="checked"'; echo'>&nbsp;&nbsp;&nbsp;&nbsp;Nein&nbsp;&nbsp;&nbsp;&nbsp;</label></div></td>
					  </tr>
					  <tr>
						<td>Auto Refresh Zeit <span class="text-muted">(Standard: 10sek)</span></td>
						<td><input type="text" name="refresh_time" class="form-control" value="'.$set['refresh_time'].'"></td>
					  </tr>
					  </tbody>
					  
					  
					  <thead>
						<td colspan="2"><br /><br /><h4>Lovebox <span class="text-muted">Style</span></h4></td>
					  </thead>
					<tbody>
					<tr>
						<td>Herz Abstand <span class="text-muted">(Standard: 15px)</span></td>
						<td><input type="text" name="style" class="form-control" value="'.$set['style'].'"></td>
					</tr>
					<tr>
						<td>Banner einblenden</td>
						<td><div class="btn-group" data-toggle="buttons">
							<label class="btn btn-info '; if ($set['banner'] == '1') echo 'active'; echo'">
							<input type="radio" name="banner" id="optionsRadios1" value="1" '; if ($set['banner'] == '1') echo 'checked="checked"'; echo'>
							&nbsp;&nbsp;&nbsp;&nbsp;Ja&nbsp;&nbsp;&nbsp;&nbsp;
							</label>
							<label class="btn btn-info '; if ($set['banner'] == '0') echo 'active'; echo'">
							<input type="radio" name="banner" id="optionsRadios2" value="0" '; if ($set['banner'] == '0') echo 'checked="checked"'; echo'>
							&nbsp;&nbsp;&nbsp;&nbsp;Nein&nbsp;&nbsp;&nbsp;&nbsp;
							</label>
							</div>
						</td>
					</tr>
					<tr>
						<td>Banner Text</td>
						<td><input type="text" name="banner_text" class="form-control" value="'.$set['banner_text'].'"></td>
					</tr>
					<tr>
						<td>Text blinken</td>
						<td><div class="btn-group" data-toggle="buttons">
							<label class="btn btn-info '; if ($set['blink'] == '1') echo 'active'; echo'">
							<input type="radio" name="blink" id="optionsRadios1" value="1" '; if ($set['blink'] == '1') echo 'checked="checked"'; echo'>
							&nbsp;&nbsp;&nbsp;&nbsp;Ja&nbsp;&nbsp;&nbsp;&nbsp;
							</label>
							<label class="btn btn-info '; if ($set['blink'] == '0') echo 'active'; echo'">
							<input type="radio" name="blink" id="optionsRadios2" value="0" '; if ($set['blink'] == '0') echo 'checked="checked"'; echo'>
							&nbsp;&nbsp;&nbsp;&nbsp;Nein&nbsp;&nbsp;&nbsp;&nbsp;
							</label>
							</div>
						</td>
					</tr>
					<tr>
						<td>Pfeilrichtung</td>
						<td><div class="btn-group" data-toggle="buttons">
							<label class="btn btn-info '; if ($set['arrow'] == '0') echo 'active'; echo'">
							<input type="radio" name="arrow" id="optionsRadios2" value="0" '; if ($set['arrow'] == '0') echo 'checked="checked"'; echo'>
							Aus</label>
							<label class="btn btn-info '; if ($set['arrow'] == '1') echo 'active'; echo'">
							<input type="radio" name="arrow" id="optionsRadios3" value="1" '; if ($set['arrow'] == '1') echo 'checked="checked"'; echo'>
							<i class="fa fa-arrow-left rot-left"></i></label>
							<label class="btn btn-info '; if ($set['arrow'] == '2') echo 'active'; echo'">
							<input type="radio" name="arrow" id="optionsRadios1" value="2" '; if ($set['arrow'] == '2') echo 'checked="checked"'; echo'>
							<i class="fa fa-arrow-down"></i></label>
							<label class="btn btn-info '; if ($set['arrow'] == '3') echo 'active'; echo'">
							<input type="radio" name="arrow" id="optionsRadios3" value="3" '; if ($set['arrow'] == '3') echo 'checked="checked"'; echo'>
							<i class="fa fa-arrow-right rot-right"></i></label>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="2" class="text-center">'; if ($set['test'] == '1'){ echo '<a class="btn btn-danger" href="index.php?testmode_off=1"><i class="fa fa-th"></i> Kalibrierung deaktivieren</a>'; 
						} else { echo'<a class="btn btn-primary" href="index.php?testmode=1"><i class="fa fa-th"></i> Kalibrierung aktivieren</a>'; 
						} echo'</td>
					  </tr>
					  </tbody>
				  </table>
				  </div>
				<div class="tab-pane '.$li_sty.'" id="style">
				<table class="table table-striped">
					<thead>
						<td colspan="2"><h4>Backend <span class="text-muted">Sicherheit</span></h4></td>
					  </thead>
					  <tbody>
					  <tr>
						<td>Sicherheitswort</td>
						<td><input type="text" name="clean" class="form-control" value="'.$set['clean'].'"></td>
					  </tr>
					  <tr>
						<td>Login-Daten</td>
						<td><input type="text" name="user" class="form-control" value="'.$set['username'].'"><br /><input type="password" onfocus="this.select()" name="pass" class="form-control" placeholder="Password"></td>
					  </tr>
					</tbody>
					

					<thead>
						<td colspan="2"><br /><br /><h4>Backend <span class="text-muted">Einstellungen</span></h4></td>
					  </thead>
					<tbody>
						<tr>
						<td>Statistik anzeigen</td>
						<td><div class="btn-group" data-toggle="buttons"><label class="btn btn-info '; if ($set['static'] == '1') echo 'active'; echo'">
						<input type="radio" name="static" id="optionsRadios1" value="1" '; if ($set['static'] == '1') echo 'checked="checked"'; echo'>&nbsp;&nbsp;&nbsp;&nbsp;Ja&nbsp;&nbsp;&nbsp;&nbsp;</label><label class="btn btn-info '; if ($set['static'] == '0') echo 'active'; echo'"><input type="radio" name="static" id="optionsRadios2" value="0" '; if ($set['static'] == '0') echo 'checked="checked"'; echo'>&nbsp;&nbsp;&nbsp;&nbsp;Nein&nbsp;&nbsp;&nbsp;&nbsp;</label></div></td>
					  </tr>
					  <tr>
						<td>CSS Reset</td>
						<td><a class="btn btn-danger" href="index.php?css=1">CSS Reset</a></td>
					  </tr>
					  </tbody>
				  </table>
				  </div>
				  </div>';
				?>
			  </div>
			  <div class="modal-footer">
				<a href="index.php?action=logout" class="btn btn-danger pull-left">Logout</a>
				<input class="btn btn-success" type="submit" name="saveset" value="speichern" /></form>
				<button type="button" class="btn btn-default" data-dismiss="modal">schlie&szlig;en</button>
			  </div>
			</div><!-- /.modal-content -->
		  </div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
		
		<!-- Statistik -->
		<div class="modal fade" id="Stat" tabindex="-1" role="dialog" aria-labelledby="StatLabel" aria-hidden="true">
		  <div class="modal-dialog">
			<div class="modal-content">
			  <div class="modal-body">
				<?php
				echo'
				<ul class="nav nav-tabs">
						<li class="active"><a href="#overview" data-toggle="tab"><i class="fa fa-heart-o"></i> &Uuml;bersicht</a></li>
						<li><a href="#log" data-toggle="tab"><i class="fa fa-list"></i> Log</a></li>
						</ul>
				<div class="tab-content">
				<div class="tab-pane active" id="overview">';
				$to=$db->query("SELECT * FROM log ORDER BY count DESC LIMIT 3");
						$topn = 1;
						while($top=$to->fetchArray(SQLITE3_ASSOC)) {
						if($topn == 1) {
							$toplatz1 = $top[number];
						}
						elseif($topn == 2) {
							$toplatz2 = $top[number];
						}
						elseif($topn == 3) {
							$toplatz3 = $top[number];
						}
						$topn++;
						}
						echo'
				  <table border="0" style="width:100%; text-align:center">
					<tr>
						<td></td>
						<td style="border-bottom:1px solid #000;"><h1>'.$toplatz1.'</h1></td>
						<td></td>
					</tr>
					<tr>
						<td style="border-bottom:1px solid #000;"><h1>'.$toplatz2.'</h1></td>
						<td style="border-left:1px solid #000; border-right:1px solid #000;"><h3>Platz 1</h3></td>
						<td></td>
					</tr>
					<tr>
						<td style="border-left:1px solid #000;"><h3>Platz 2</h3></td>
						<td style="border-left:1px solid #000; border-right:1px solid #000;"></td>
						<td style="border-bottom:1px solid #000;"><h1>'.$toplatz3.'</h1></td>
					</tr>
					<tr>
						<td style="border-left:1px solid #000;"></td>
						<td style="border-left:1px solid #000; border-right:1px solid #000;"></td>
						<td style="border-right:1px solid #000;"><h3>Platz 3</h3></td>
					</tr>
					<tr>
						<td style="border-left:1px solid #000;"></td>
						<td style="border-left:1px solid #000; border-right:1px solid #000;"></td>
						<td style="border-right:1px solid #000;"></td>
					</tr>
					<tr>
						<td style="border-bottom:1px solid #000; border-left:1px solid #000;"></td>
						<td style="border-bottom:1px solid #000; border-left:1px solid #000; border-right:1px solid #000;">&nbsp;</td>
						<td style="border-bottom:1px solid #000; border-right:1px solid #000;"></td>
					</tr>
					<tr>
						<td></td>
						<td>&nbsp;</td>
						<td></td>
					</tr>
					<tr>
					<td></td>
					<td class="text-center">';
						$rows=$db->query("SELECT SUM(count) summe FROM log");
						$heatmax=$rows->fetchArray();
						$heatmax=$heatmax[0];
						echo 'Gesamte Nachrichten: '.$heatmax.' <i class="fa fa-heart-o"></i></td>
					<td></td>
					</tr>
				</table>

				  </div>
				<div class="tab-pane" id="log">';
					$dslog=$db->query("SELECT * FROM log ORDER by date, time DESC");
					$anz=count($dslog);
					$cmax=$db->query("SELECT count FROM log ORDER BY count DESC LIMIT 1");
					$cmax=$cmax->fetchArray(SQLITE3_ASSOC);
					$cmax = $cmax[count];

					if($anz) {
					echo'<table class="table table-bordered text-center">
						<thead>
						  <tr>
						    <th class="text-center">Counter</th>
							<th class="text-center">Zeit</th>
							<th class="text-center">Nummer</th>
						  </tr>
						</thead>
						<tbody>';
						while($alog = $dslog->fetchArray(SQLITE3_ASSOC)) {
						$counter = $alog['count'];
						$prozent = $counter/$cmax*100;
						$prozent = round($prozent);
							echo'<tr>
							<td>
							<div class="progress">
							  <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="'.$prozent.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$prozent.'%; text-shadow:0px 0px 8px #000;">
							    '.$prozent.'%
							  </div>
							</div>
							</td>
							<td>'.$alog[date].' <b>'.$alog[time].'</b></td>
							<td>'.$alog[number].'</td>
						  </tr>
						  ';
						} echo'</tbody>
					  </table>';
					} else { echo'<div class="alert alert-info"><h2>Keine Eintr&auml;ge</h2></div>'; } 
				?>
				</div>
				  </div>
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">schlie&szlig;en</button>
			  </div>
			</div><!-- /.modal-content -->
		  </div><!-- /.modal-dialog -->
		</div>
		 	<div class="footer">
	<div class="container text-center">
	<p>&copy; <?php echo date(Y); ?> by <a href="http://www.atworkz.de" target="_blank">@workz.de</a> | Lovebox <?php echo $system_version; ?> </p>
      </div>
	</div>  
		
	
	<?php
	}else{ //Ende Sesson

if ($logedout){
//Falls man ausgeloggt wurde, wird nun hier eine Erfolgsmeldung angezeigt
	sysinfo('success', '<i class="fa fa-check"></i> Sie wurden erfolgreich ausgeloggt.');
}
echo ' </div>
		</div>';
if(isset($_POST['Login'])){
sysinfo('danger', 'Die eingegebenen Login-Daten sind nicht korrekt!'); }
?>

<div class="container">
    <div class="login-container">
            <div id="output"></div>
			<h2 class="loginhead">LOVEBOX</h2>
			<div class="loginsub">virtuelle Postwand</div>
            <div class="form-box">
                <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
                    <input name="user" type="text" placeholder="username">
                    <input name="passwort" type="password" placeholder="password">
                    <button class="btn btn-info btn-block login" name="Login" type="submit">Login</button>
                </form>
            </div>
        </div>
        
</div>
 	<div class="footer">
	<div class="container text-center text-white">
	<span class="label label-info">Lovebox <?php echo $system_version; ?></span><br />
	<span class="label label-default">&copy; <?php echo date(Y); ?> by <a href="http://www.atworkz.de" target="_blank">@workz.de</a></span>
      </div>
	</div>  
<?php
}
$db = close();
?>
   


	
  </body>
</html>