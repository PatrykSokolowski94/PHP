<?php
session_start();
if(!isset($_SESSION['user_session'])){
	header("Location: logowanie");
}
include_once("baza-danych/config.php");
include ('php/Chat.php');
include("php/php-mailer/PHPMailerAutoload.php");
$sql = "SELECT login, ID_przywileje FROM boosterzy WHERE login='".$_SESSION['user_session']."'";
$resultset = mysqli_query($link, $sql) or die("Błąd bazy danych:". mysqli_error($link));
$row = mysqli_fetch_assoc($resultset);
?>
<!DOCTYPE html>
<html lang="pl">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="EzBoosting">

    <title>EzBoosting.net - Panel</title>

    <link href="panel_assets/assets/css/bootstrap.css" rel="stylesheet">
    <link href="panel_assets/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="panel_assets/assets/css/zabuto_calendar.css">
    <link rel="stylesheet" type="text/css" href="panel_assets/assets/js/gritter/css/jquery.gritter.css" />
    <link rel="stylesheet" type="text/css" href="panel_assets/assets/lineicons/style.css">    
    
    <link href="panel_assets/assets/css/style.css" rel="stylesheet">
    <link href="panel_assets/assets/css/style-responsive.css" rel="stylesheet">
	<link rel="stylesheet" href="panel_assets/assets/css/to-do.css">
	<link rel="stylesheet" href="css/chat_style.css">
    <script src="panel_assets/assets/js/chart-master/Chart.js"></script>
    
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

  <section id="container" >
      <header class="header black-bg">
              <div class="sidebar-toggle-box">
                  <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Przełącznik nawigacji"></div>
              </div>
            <a href="index.html" class="logo"><b>Witaj <?php echo $row['login']; ?>!</b></a>
            <div class="nav notify-row" id="top_menu">
                <ul class="nav top-menu">
                </ul>
            </div>
            <div class="top-menu">
            	<ul class="nav pull-right top-menu">
                    <li><a class="logout" href="logout">Wyloguj</a></li>
            	</ul>
            </div>
        </header>
		
      <aside>
          <div id="sidebar"  class="nav-collapse ">
              <ul class="sidebar-menu" id="nav-accordion">
              	  	
                  <li class="mt">
                      <a href="panel">
                          <i class="fa fa-dashboard"></i>
                          <span>Strona główna panelu</span>
                      </a>
                  </li>
				  
                  <li class="sub">
                      <a href="regulamin boostera">
                          <i class="fa fa-desktop"></i>
                          <span>Regulamin Boostera</span>
                      </a>
                  </li>

                  <li class="sub-menu">
                      <a class="active" href="javascript:;" >
                          <i class="fa fa-desktop"></i>
                          <span>Zlecenia</span>
                      </a>
                      <ul class="sub">
                          <li><a  href="zlecenia">Dostępne zlecenia</a></li>
						  <li class="active"><a  href="moje-zlecenia">Moje aktualne zlecenia</a></li>
                          <li><a  href="historia-zlecen">Historia moich zleceń</a></li>
                      </ul>
                  </li>

                  <li class="sub-menu">
                      <a href="javascript:;" >
                          <i class="fa fa-cogs"></i>
                          <span>Konto</span>
                      </a>
                      <ul class="sub">
                          <li><a  href="moje-dane">Moje dane</a></li>
						  <li><a  href="zmien-haslo">Zmień hasło</a></li>
                          <li><a  href="historia-wyplat">Historia wypłat</a></li>
                      </ul>
                  </li>

				  <?php
				  if($row['ID_przywileje'] == 1){
				  echo'
                  <li class="sub-menu">
                      <a href="javascript:;" >
                          <i class="fa fa-cogs"></i>
                          <span>Panel Administratora</span>
                      </a>
                      <ul class="sub">
                          <li><a  href="panel_wiadomosc">Dodaj/Usuń wiadomość</a></li>
                          <li><a  href="panel_wyplaty">Wypłaty</a></li>
						  <li><a  href="panel_historia_wyplat">Historia wypłat</a></li>
						  <li><a  href="panel_boosterzy">Dodaj/Usuń Boostera</a></li>
						  <li><a  href="panel_dodaj_zlecenie">Dodaj zlecenie z allegro</a></li>
						  <li><a  href="panel_aktywne_zlecenia">Aktywne zlecenia</a></li>
						  <li><a  href="panel_historia_zlecen">Historia zleceń</a></li>
						  <li><a  href="panel_potwierdz">Oczekujące potwierdzenia</a></li>
						  <li><a  href="panel_kontrola">Chaty boosterów</a></li>
                      </ul>
                  </li>';
				  }
					?>

              </ul>
          </div>
      </aside>

      <section id="main-content">
          <section class="wrapper">
		    <h3><i class="fa fa-angle-right"></i> Moje zlecenia</h3>
			
			<?php
			$id_boostera = "SELECT ID, stawkaprocent FROM boosterzy WHERE login='".$_SESSION['user_session']."'";
			$id_boostera2 = mysqli_query($link, $id_boostera) or die("Błąd bazy danych:". mysqli_error($link));
			$id_boostera3 = mysqli_fetch_assoc($id_boostera2);
			
			$zlecen_ukonczonych = "SELECT Ilosc_wykonanych_zlecen FROM boosterzy WHERE login='".$_SESSION['user_session']."'";
			$zlecen_ukonczonych2 = mysqli_query($link, $zlecen_ukonczonych) or die("Błąd bazy danych:". mysqli_error($link));
			$zlecen_ukonczonych3 = mysqli_fetch_assoc($zlecen_ukonczonych2);
			
			$max_zlecen = "SELECT przywileje.max_zlecen FROM przywileje, boosterzy WHERE boosterzy.login='".$_SESSION['user_session']."' AND boosterzy.ID_przywileje=przywileje.ID";
			$max_zlecen2 = mysqli_query($link, $max_zlecen) or die("Błąd bazy danych:". mysqli_error($link));
			$max_zlecen3 = mysqli_fetch_assoc($max_zlecen2);
						
			$ilosc_przydz_zlecen = "SELECT transakcje.ID FROM transakcje, boosterzy WHERE boosterzy.login='".$_SESSION['user_session']."' AND transakcje.ID_boostera = boosterzy.ID";
			$ilosc_przydz_zlecen2 = mysqli_query($link, $ilosc_przydz_zlecen) or die("Błąd bazy danych:". mysqli_error($link));
			$ilosc_przydz_zlecen3 = mysqli_num_rows($ilosc_przydz_zlecen2);
			
			  if(isset($_POST['ID'])) {
				$spr = "SELECT * FROM transakcje WHERE ID='".$_POST['ID']."'";
				$spr2 = mysqli_query($link, $spr) or die("Błąd bazy danych:". mysqli_error($link));
				$spr3 = mysqli_fetch_assoc($spr2);
				
				$q = "SELECT * FROM `clients` WHERE `email` = '".$spr3['EMAIL']."' ";
				
				if($spr3['WALUTA'] == EUR)
				{
				$check_client = mysqli_query($link, "SELECT * FROM `clients` WHERE `email` = '".$spr3['EMAIL']."' ")  or die("Błąd bazy danych:". mysqli_error($link));
				if(mysqli_num_rows($check_client) > 0) {
					$update_client = mysqli_query($link, "UPDATE `clients` SET `NROPERACJI` = '".$_POST['ID']."', IDBOOSTERA = '".$id_boostera3['ID']."' WHERE `email`  = '".$spr3['EMAIL']."' ");
					$from = 'contact@ezboosting.net';
					$subject = 'Your order has been taken EzBoosting.eu';
					$name = 'Support';
					$to = $spr3['EMAIL'];
					$message = '<p>Your boost order purchased on the website <strong>EzBoosting.eu<br /><br /></strong></p>
									<p>Your order has been transferred for execution. To track its progress or contact booster log in to the customer panel using the data received during the first purchase in the first email!<br />Panel url adress:&nbsp;<a href="https://ezboosting.eu/login">https://ezboosting.eu/login </a></p>
									<p style="color:red"><br />Thank you for using our website and we look forward to further cooperation!!</p>';


					$mail = new PHPMailer;


					$mail->SMTPDebug = 0;

					$mail->From = $from;
					$mail->AddAddress($to);
					$mail->AddReplyTo($from, $name);

					$mail->IsHTML(true);
					$mail->addBCC("ezboosting.net+e432fd1afc@invite.trustpilot.com");
					$mail->CharSet = 'UTF-8';

					$mail->Subject = $subject;
					$mail->Body    = $message;

					if($mail->Send()) {
						$arrResult = array('response'=> 'success');
					} else {
						$arrResult = array('response'=> 'error', 'error'=> $mail->ErrorInfo);
					}
					
				} else {
					$randomPassword = bin2hex(random_bytes(5)); 
					$pass = md5($randomPassword);
					$query = mysqli_query($link, "INSERT INTO `clients` VALUES (0, '".$spr3['EMAIL']."', '".$pass."', ".$_POST['ID'].", ".$id_boostera3['ID'].")");
					
					$from = 'contact@ezboosting.net';
					$subject = 'Customer panel from EzBoosting.eu';
					$name = 'Support';
					$to = $spr3['EMAIL'];
					$message = '<p>Registration on the website <strong>EzBoosting.eu<br /><br /></strong></p>
									<p>Your order has been transferred for execution. To track its progress or contact booster log in to the customer panel using the data below.<br />Panel url adress:&nbsp;<a href="https://ezboosting.eu/login">https://ezboosting.eu/login </a></p>
									<p><br />Login: <strong>'.$spr3['EMAIL'].'<br /></strong>Password: <strong>'.$randomPassword.'</strong><br><br>Save your login details, you can use them for future purchases !</p>';


					$mail = new PHPMailer;


					$mail->SMTPDebug = 0;

					$mail->From = $from;
					$mail->AddAddress($to);
					$mail->AddReplyTo($from, $name);

					$mail->IsHTML(true);

					$mail->CharSet = 'UTF-8';

					$mail->Subject = $subject;
					$mail->Body    = $message;

					if($mail->Send()) {
						$arrResult = array('response'=> 'success');
					} else {
						$arrResult = array('response'=> 'error', 'error'=> $mail->ErrorInfo);
					}


				}
				}
				else {
					$check_client = mysqli_query($link, "SELECT * FROM `clients` WHERE `email` = '".$spr3['EMAIL']."' ")  or die("Błąd bazy danych:". mysqli_error($link));
				if(mysqli_num_rows($check_client) > 0) {
					
					$update_client = mysqli_query($link, "UPDATE `clients` SET `NROPERACJI` = '".$_POST['ID']."', IDBOOSTERA = '".$id_boostera3['ID']."' WHERE `email`  = '".$spr3['EMAIL']."' ");
					
					$from = 'contact@ezboosting.net';
					$subject = 'Zlecenie zostało przyjęte EzBoosting.net';
					$name = 'Support';
					$to = $spr3['EMAIL'];
					$message = '<p>Twoje zlecenie boostingu zakupione na stronie <strong>EzBoosting.net<br /><br /></strong></p>
									<p>Twoje zlecenie zostało przyjęte przez naszego boostera. Aby skontaktować się z boosterem oraz śledzić postęp boostingu skorzystaj z panelu otrzymanego w pierwszym emailu od Nas po dokonaniu pierwszego zakupu.<br />URL do zalogowania:&nbsp;<a href="https://ezboosting.net/login">https://ezboosting.net/login </a></p>
									<p><br /><p style="color:red">Dziękujemy za skorzystanie z naszego serwisu i liczymy na dalszą współpracę !</p><br><br><p>Po zakończonym zleceniu zostaw nam opinie(oby jak najlepsza:) ) Ciebie to nic nie kosztuje, a nam pomoże w rozwoju<br /><a href="https://pl.trustpilot.com/review/ezboosting.net" target="_blank">Trustpilot</a><br>WEŹ UDZIAŁ W KONKURSIE ! Co miesiąc spośród pozytywnych komenatrzy w ciągu tego czasu na naszym <a href="https://pl.trustpilot.com/review/ezboosting.net" target="_blank">Trustpilocie</a> losujemy boosting o wartości 200zł oraz dodatkowe 5h coachingu !<br>Serdecznie dziękujemy!</p>';


					$mail = new PHPMailer;


					$mail->SMTPDebug = 0;

					$mail->From = $from;
					$mail->AddAddress($to);
					$mail->AddReplyTo($from, $name);

					$mail->IsHTML(true);

					$mail->CharSet = 'UTF-8';

					$mail->Subject = $subject;
					$mail->Body    = $message;

					if($mail->Send()) {
						$arrResult = array('response'=> 'success');
					} else {
						$arrResult = array('response'=> 'error', 'error'=> $mail->ErrorInfo);
					}
					
					
				} else {
					$randomPassword = bin2hex(random_bytes(5)); 
					$pass = md5($randomPassword);
					$query = mysqli_query($link, "INSERT INTO `clients` VALUES (0, '".$spr3['EMAIL']."', '".$pass."', ".$_POST['ID'].", ".$id_boostera3['ID'].")");
					
					$from = 'contact@ezboosting.net';
					$subject = 'Konto w serwisie EzBoosting.net';
					$name = 'Support';
					$to = $spr3['EMAIL'];
					$message = '<p>Rejestracja na stronie <strong>EzBoosting.net<br /><br /></strong></p>
									<p>Twoje zlecenie zostało przyjęte przez naszego boostera. Aby skontaktować się z boosterem oraz śledzić postęp boostingu skorzystaj z panelu otrzymanego poniżej.<br />URL do zalogowania:&nbsp;<a href="https://ezboosting.net/login">https://ezboosting.net/login </a></p>
									<p><br />Login: <strong>'.$spr3['EMAIL'].'<br /></strong>Hasło: <strong>'.$randomPassword.'</p></strong><br><br><p style="color:red">Zachowaj te dane do logowania, dostajesz je jeden raz i będą one Twoimi danymi do logowania w panelu przy następnych zakupach.</p><br><br><p>Po zakończonym zleceniu zostaw nam opinie(oby jak najlepsza:) ) Ciebie to nic nie kosztuje, a nam pomoże w rozwoju<br /><a href="https://pl.trustpilot.com/review/ezboosting.net" target="_blank">Trustpilot</a><br>WEŹ UDZIAŁ W KONKURSIE ! Co miesiąc spośród pozytywnych komenatrzy w ciągu tego czasu na naszym <a href="https://pl.trustpilot.com/review/ezboosting.net" target="_blank">Trustpilocie</a> losujemy boosting o wartości 200zł oraz dodatkowe 5h coachingu !<br>Serdecznie dziękujemy!</p>';


					$mail = new PHPMailer;


					$mail->SMTPDebug = 0;

					$mail->From = $from;
					$mail->AddAddress($to);
					$mail->AddReplyTo($from, $name);

					$mail->IsHTML(true);

					$mail->CharSet = 'UTF-8';

					$mail->Subject = $subject;
					$mail->Body    = $message;

					if($mail->Send()) {
						$arrResult = array('response'=> 'success');
					} else {
						$arrResult = array('response'=> 'error', 'error'=> $mail->ErrorInfo);
					}


				}
				}
				
				
				
				
				if(($spr3['ID_boostera'] == 0) && ($ilosc_przydz_zlecen3 <= $max_zlecen3['max_zlecen'])){
				$submit_query = "UPDATE transakcje SET ID_boostera = ".$id_boostera3['ID']." WHERE ID = '".$_POST['ID']."'";
				$submit = mysqli_query($link, $submit_query) or die("Błąd bazy danych:". mysqli_error($link));
				echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><b>Sukces!</b> Dodano nowe zlecenie do Twojego konta.</div>';
				
				}
				else{
				echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><b>Niestety!</b> Ktoś Cię przed sekundką wyprzedził i pierwszy dodał to zlecenie do swojego konta, lub też przekroczyłeś limit max zleceń.</div>';
				}
			  }

			  if(isset($_POST['ID_boost'])) {
				$spr = "SELECT ID_boostera FROM transakcje WHERE ID='".$_POST['ID_boost']."'";
				$spr2 = mysqli_query($link, $spr) or die("Błąd bazy danych:". mysqli_error($link));
				$spr3 = mysqli_fetch_assoc($spr2);
				
				if($spr3['ID_boostera'] == $id_boostera3['ID']){
				$zlecen_ukonczonych3['Ilosc_wykonanych_zlecen'] = $zlecen_ukonczonych3['Ilosc_wykonanych_zlecen'] + 1;
				$submit_boost = "INSERT INTO zakonczone_transakcje (ID,NROPERACJI,KWOTA,WALUTA,Dywizja,Nick,Login,Haslo,EMAIL,ID_boostera,ID_rodzajzlecenia,Liczba_gier,Liczba_godzin,DUO,Discord,Serwer,Kolejkalol,Ilelp,LPperW,Linie,Czary,Offik,Express,Jedenwin,Streamek,Champek,ChampMasterka,Masterkastart,Masterkaend,Data_zakupu) SELECT ID,NROPERACJI,KWOTA,WALUTA,Dywizja,Nick,Login,Haslo,EMAIL,ID_boostera,ID_rodzajzlecenia,Liczba_gier,Liczba_godzin,DUO,Discord,Serwer,Kolejkalol,Ilelp,LPperW,Linie,Czary,Offik,Express,Jedenwin,Streamek,Champek,ChampMasterka,Masterkastart,Masterkaend,Data_zakupu FROM transakcje WHERE ID = '".$_POST['ID_boost']."'";
				$delete_boost = "DELETE FROM transakcje WHERE ID = '".$_POST['ID_boost']."'";
				$add_ukonczone_zlecenia = "UPDATE boosterzy SET Ilosc_wykonanych_zlecen = ".$zlecen_ukonczonych3['Ilosc_wykonanych_zlecen']." WHERE login = '".$_SESSION['user_session']."'";
				
				$submit = mysqli_query($link, $submit_boost) or die("Błąd bazy danych:". mysqli_error($link));
				$delete = mysqli_query($link, $delete_boost) or die("Błąd bazy danych:". mysqli_error($link));
				$add_ukon_zlecenia = mysqli_query($link, $add_ukonczone_zlecenia) or die("Błąd bazy danych:". mysqli_error($link));

				echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><b>Sukces!</b> Zlecenie oznaczono jako zrealizowane.<br>Pamiętaj, iż wartość Twojej wypłaty powiększy się o twoją stawkę za to zlecenie dopiero gdy Administrator zatwierdzi jego wykonanie.</div>';
				}
				else{
				echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><b>Błąd!</b> Zlecenie które próbujesz zatwierdzić wcale nie należy do Ciebie!</div>';
				}
			  }

			  if(isset($_POST['ID_boostperwin'])) {
				$spr = "SELECT ID_boostera FROM transakcje WHERE ID='".$_POST['ID_boostperwin']."'";
				$spr2 = mysqli_query($link, $spr) or die("Błąd bazy danych:". mysqli_error($link));
				$spr3 = mysqli_fetch_assoc($spr2);
				
				if($spr3['ID_boostera'] == $id_boostera3['ID']){
				$zlecen_ukonczonych3['Ilosc_wykonanych_zlecen'] = $zlecen_ukonczonych3['Ilosc_wykonanych_zlecen'] + 1;
				$submit_boost = "INSERT INTO zakonczone_transakcje (ID,NROPERACJI,KWOTA,WALUTA,Dywizja,Nick,Login,Haslo,EMAIL,ID_boostera,ID_rodzajzlecenia,Liczba_gier,Liczba_godzin,DUO,Discord,Serwer,Kolejkalol,Ilelp,LPperW,Linie,Czary,Offik,Express,Jedenwin,Streamek,Champek,ChampMasterka,Masterkastart,Masterkaend,Data_zakupu) SELECT ID,NROPERACJI,KWOTA,WALUTA,Dywizja,Nick,Login,Haslo,EMAIL,ID_boostera,ID_rodzajzlecenia,Liczba_gier,Liczba_godzin,DUO,Discord,Serwer,Kolejkalol,Ilelp,LPperW,Linie,Czary,Offik,Express,Jedenwin,Streamek,Champek,ChampMasterka,Masterkastart,Masterkaend,Data_zakupu FROM transakcje WHERE ID = '".$_POST['ID_boostperwin']."'";
				$delete_boost = "DELETE FROM transakcje WHERE ID = '".$_POST['ID_boostperwin']."'";
				$add_ukonczone_zlecenia = "UPDATE boosterzy SET Ilosc_wykonanych_zlecen = ".$zlecen_ukonczonych3['Ilosc_wykonanych_zlecen']." WHERE login = '".$_SESSION['user_session']."'";
				
				$submit = mysqli_query($link, $submit_boost) or die("Błąd bazy danych:". mysqli_error($link));
				$delete = mysqli_query($link, $delete_boost) or die("Błąd bazy danych:". mysqli_error($link));
				$add_ukon_zlecenia = mysqli_query($link, $add_ukonczone_zlecenia) or die("Błąd bazy danych:". mysqli_error($link));

				echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><b>Sukces!</b> Zlecenie oznaczono jako zrealizowane.<br>Pamiętaj, iż wartość Twojej wypłaty powiększy się o twoją stawkę za to zlecenie dopiero gdy Administrator zatwierdzi jego wykonanie.</div>';
				}
				else{
				echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><b>Błąd!</b> Zlecenie które próbujesz zatwierdzić wcale nie należy do Ciebie!</div>';
				}
			  }
			  
			  if(isset($_POST['ID_placements'])) {
				$spr = "SELECT ID_boostera FROM transakcje WHERE ID='".$_POST['ID_placements']."'";
				$spr2 = mysqli_query($link, $spr) or die("Błąd bazy danych:". mysqli_error($link));
				$spr3 = mysqli_fetch_assoc($spr2);
				
				if($spr3['ID_boostera'] == $id_boostera3['ID']){
				$zlecen_ukonczonych3['Ilosc_wykonanych_zlecen'] = $zlecen_ukonczonych3['Ilosc_wykonanych_zlecen'] + 1;
				$submit_boost = "INSERT INTO zakonczone_transakcje (ID,NROPERACJI,KWOTA,WALUTA,Dywizja,Nick,Login,Haslo,EMAIL,ID_boostera,ID_rodzajzlecenia,Liczba_gier,Liczba_godzin,DUO,Discord,Serwer,Kolejkalol,Ilelp,LPperW,Linie,Czary,Offik,Express,Jedenwin,Streamek,Champek,ChampMasterka,Masterkastart,Masterkaend,Data_zakupu) SELECT ID,NROPERACJI,KWOTA,WALUTA,Dywizja,Nick,Login,Haslo,EMAIL,ID_boostera,ID_rodzajzlecenia,Liczba_gier,Liczba_godzin,DUO,Discord,Serwer,Kolejkalol,Ilelp,LPperW,Linie,Czary,Offik,Express,Jedenwin,Streamek,Champek,ChampMasterka,Masterkastart,Masterkaend,Data_zakupu FROM transakcje WHERE ID = '".$_POST['ID_placements']."'";
				$delete_boost = "DELETE FROM transakcje WHERE ID = '".$_POST['ID_placements']."'";
				$add_ukonczone_zlecenia = "UPDATE boosterzy SET Ilosc_wykonanych_zlecen = ".$zlecen_ukonczonych3['Ilosc_wykonanych_zlecen']." WHERE login = '".$_SESSION['user_session']."'";
				
				$submit = mysqli_query($link, $submit_boost) or die("Błąd bazy danych:". mysqli_error($link));
				$delete = mysqli_query($link, $delete_boost) or die("Błąd bazy danych:". mysqli_error($link));
				$add_ukon_zlecenia = mysqli_query($link, $add_ukonczone_zlecenia) or die("Błąd bazy danych:". mysqli_error($link));

				echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><b>Sukces!</b> Zlecenie oznaczono jako zrealizowane.<br>Pamiętaj, iż wartość Twojej wypłaty powiększy się o twoją stawkę za to zlecenie dopiero gdy Administrator zatwierdzi jego wykonanie.</div>';
				}
				else{
				echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><b>Błąd!</b> Zlecenie które próbujesz zatwierdzić wcale nie należy do Ciebie!</div>';
				}
			  }	

			  if(isset($_POST['ID_coaching'])) {
				$spr = "SELECT ID_boostera FROM transakcje WHERE ID='".$_POST['ID_coaching']."'";
				$spr2 = mysqli_query($link, $spr) or die("Błąd bazy danych:". mysqli_error($link));
				$spr3 = mysqli_fetch_assoc($spr2);
				
				if($spr3['ID_boostera'] == $id_boostera3['ID']){
				$zlecen_ukonczonych3['Ilosc_wykonanych_zlecen'] = $zlecen_ukonczonych3['Ilosc_wykonanych_zlecen'] + 1;
				$submit_boost = "INSERT INTO zakonczone_transakcje (ID,NROPERACJI,KWOTA,WALUTA,Dywizja,Nick,Login,Haslo,EMAIL,ID_boostera,ID_rodzajzlecenia,Liczba_gier,Liczba_godzin,DUO,Discord,Serwer,Kolejkalol,Ilelp,LPperW,Linie,Czary,Offik,Express,Jedenwin,Streamek,Champek,ChampMasterka,Masterkastart,Masterkaend,Data_zakupu) SELECT ID,NROPERACJI,KWOTA,WALUTA,Dywizja,Nick,Login,Haslo,EMAIL,ID_boostera,ID_rodzajzlecenia,Liczba_gier,Liczba_godzin,DUO,Discord,Serwer,Kolejkalol,Ilelp,LPperW,Linie,Czary,Offik,Express,Jedenwin,Streamek,Champek,ChampMasterka,Masterkastart,Masterkaend,Data_zakupu FROM transakcje WHERE ID = '".$_POST['ID_coaching']."'";
				$delete_boost = "DELETE FROM transakcje WHERE ID = '".$_POST['ID_coaching']."'";
				$add_ukonczone_zlecenia = "UPDATE boosterzy SET Ilosc_wykonanych_zlecen = ".$zlecen_ukonczonych3['Ilosc_wykonanych_zlecen']." WHERE login = '".$_SESSION['user_session']."'";
				
				$submit = mysqli_query($link, $submit_boost) or die("Błąd bazy danych:". mysqli_error($link));
				$delete = mysqli_query($link, $delete_boost) or die("Błąd bazy danych:". mysqli_error($link));
				$add_ukon_zlecenia = mysqli_query($link, $add_ukonczone_zlecenia) or die("Błąd bazy danych:". mysqli_error($link));

				echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><b>Sukces!</b> Zlecenie oznaczono jako zrealizowane.<br>Pamiętaj, iż wartość Twojej wypłaty powiększy się o twoją stawkę za to zlecenie dopiero gdy Administrator zatwierdzi jego wykonanie.</div>';
				}
				else{
				echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><b>Błąd!</b> Zlecenie które próbuje zatwierdzić wcale nie należy do Ciebie!</div>';
				}
			  }				  

			  if(isset($_POST['ID_boosttft'])) {
				$spr = "SELECT ID_boostera FROM transakcje WHERE ID='".$_POST['ID_boosttft']."'";
				$spr2 = mysqli_query($link, $spr) or die("Błąd bazy danych:". mysqli_error($link));
				$spr3 = mysqli_fetch_assoc($spr2);
				
				if($spr3['ID_boostera'] == $id_boostera3['ID']){
				$zlecen_ukonczonych3['Ilosc_wykonanych_zlecen'] = $zlecen_ukonczonych3['Ilosc_wykonanych_zlecen'] + 1;
				$submit_boost = "INSERT INTO zakonczone_transakcje (ID,NROPERACJI,KWOTA,WALUTA,Dywizja,Nick,Login,Haslo,EMAIL,ID_boostera,ID_rodzajzlecenia,Liczba_gier,Liczba_godzin,DUO,Discord,Serwer,Kolejkalol,Ilelp,LPperW,Linie,Czary,Offik,Express,Jedenwin,Streamek,Champek,ChampMasterka,Masterkastart,Masterkaend,Data_zakupu) SELECT ID,NROPERACJI,KWOTA,WALUTA,Dywizja,Nick,Login,Haslo,EMAIL,ID_boostera,ID_rodzajzlecenia,Liczba_gier,Liczba_godzin,DUO,Discord,Serwer,Kolejkalol,Ilelp,LPperW,Linie,Czary,Offik,Express,Jedenwin,Streamek,Champek,ChampMasterka,Masterkastart,Masterkaend,Data_zakupu FROM transakcje WHERE ID = '".$_POST['ID_boosttft']."'";
				$delete_boost = "DELETE FROM transakcje WHERE ID = '".$_POST['ID_boosttft']."'";
				$add_ukonczone_zlecenia = "UPDATE boosterzy SET Ilosc_wykonanych_zlecen = ".$zlecen_ukonczonych3['Ilosc_wykonanych_zlecen']." WHERE login = '".$_SESSION['user_session']."'";
				
				$submit = mysqli_query($link, $submit_boost) or die("Błąd bazy danych:". mysqli_error($link));
				$delete = mysqli_query($link, $delete_boost) or die("Błąd bazy danych:". mysqli_error($link));
				$add_ukon_zlecenia = mysqli_query($link, $add_ukonczone_zlecenia) or die("Błąd bazy danych:". mysqli_error($link));

				echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><b>Sukces!</b> Zlecenie oznaczono jako zrealizowane.<br>Pamiętaj, iż wartość Twojej wypłaty powiększy się o twoją stawkę za to zlecenie dopiero gdy Administrator zatwierdzi jego wykonanie.</div>';
				}
				else{
				echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><b>Błąd!</b> Zlecenie które próbuje zatwierdzić wcale nie należy do Ciebie!</div>';
				}
			  }	
			
			if(isset($_POST['ID_boostperwintft'])) {
				$spr = "SELECT ID_boostera FROM transakcje WHERE ID='".$_POST['ID_boostperwintft']."'";
				$spr2 = mysqli_query($link, $spr) or die("Błąd bazy danych:". mysqli_error($link));
				$spr3 = mysqli_fetch_assoc($spr2);
				
				if($spr3['ID_boostera'] == $id_boostera3['ID']){
				$zlecen_ukonczonych3['Ilosc_wykonanych_zlecen'] = $zlecen_ukonczonych3['Ilosc_wykonanych_zlecen'] + 1;
				$submit_boost = "INSERT INTO zakonczone_transakcje (ID,NROPERACJI,KWOTA,WALUTA,Dywizja,Nick,Login,Haslo,EMAIL,ID_boostera,ID_rodzajzlecenia,Liczba_gier,Liczba_godzin,DUO,Discord,Serwer,Kolejkalol,Ilelp,LPperW,Linie,Czary,Offik,Express,Jedenwin,Streamek,Champek,ChampMasterka,Masterkastart,Masterkaend,Data_zakupu) SELECT ID,NROPERACJI,KWOTA,WALUTA,Dywizja,Nick,Login,Haslo,EMAIL,ID_boostera,ID_rodzajzlecenia,Liczba_gier,Liczba_godzin,DUO,Discord,Serwer,Kolejkalol,Ilelp,LPperW,Linie,Czary,Offik,Express,Jedenwin,Streamek,Champek,ChampMasterka,Masterkastart,Masterkaend,Data_zakupu FROM transakcje WHERE ID = '".$_POST['ID_boostperwintft']."'";
				$delete_boost = "DELETE FROM transakcje WHERE ID = '".$_POST['ID_boostperwintft']."'";
				$add_ukonczone_zlecenia = "UPDATE boosterzy SET Ilosc_wykonanych_zlecen = ".$zlecen_ukonczonych3['Ilosc_wykonanych_zlecen']." WHERE login = '".$_SESSION['user_session']."'";
				
				$submit = mysqli_query($link, $submit_boost) or die("Błąd bazy danych:". mysqli_error($link));
				$delete = mysqli_query($link, $delete_boost) or die("Błąd bazy danych:". mysqli_error($link));
				$add_ukon_zlecenia = mysqli_query($link, $add_ukonczone_zlecenia) or die("Błąd bazy danych:". mysqli_error($link));

				echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><b>Sukces!</b> Zlecenie oznaczono jako zrealizowane.<br>Pamiętaj, iż wartość Twojej wypłaty powiększy się o twoją stawkę za to zlecenie dopiero gdy Administrator zatwierdzi jego wykonanie.</div>';
				}
				else{
				echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><b>Błąd!</b> Zlecenie które próbuje zatwierdzić wcale nie należy do Ciebie!</div>';
				}
			  }	  
				
				if(isset($_POST['ID_placementstft'])) {
				$spr = "SELECT ID_boostera FROM transakcje WHERE ID='".$_POST['ID_placementstft']."'";
				$spr2 = mysqli_query($link, $spr) or die("Błąd bazy danych:". mysqli_error($link));
				$spr3 = mysqli_fetch_assoc($spr2);
				
				if($spr3['ID_boostera'] == $id_boostera3['ID']){
				$zlecen_ukonczonych3['Ilosc_wykonanych_zlecen'] = $zlecen_ukonczonych3['Ilosc_wykonanych_zlecen'] + 1;
				$submit_boost = "INSERT INTO zakonczone_transakcje (ID,NROPERACJI,KWOTA,WALUTA,Dywizja,Nick,Login,Haslo,EMAIL,ID_boostera,ID_rodzajzlecenia,Liczba_gier,Liczba_godzin,DUO,Discord,Serwer,Kolejkalol,Ilelp,LPperW,Linie,Czary,Offik,Express,Jedenwin,Streamek,Champek,ChampMasterka,Masterkastart,Masterkaend,Data_zakupu) SELECT ID,NROPERACJI,KWOTA,WALUTA,Dywizja,Nick,Login,Haslo,EMAIL,ID_boostera,ID_rodzajzlecenia,Liczba_gier,Liczba_godzin,DUO,Discord,Serwer,Kolejkalol,Ilelp,LPperW,Linie,Czary,Offik,Express,Jedenwin,Streamek,Champek,ChampMasterka,Masterkastart,Masterkaend,Data_zakupu FROM transakcje WHERE ID = '".$_POST['ID_placementstft']."'";
				$delete_boost = "DELETE FROM transakcje WHERE ID = '".$_POST['ID_placementstft']."'";
				$add_ukonczone_zlecenia = "UPDATE boosterzy SET Ilosc_wykonanych_zlecen = ".$zlecen_ukonczonych3['Ilosc_wykonanych_zlecen']." WHERE login = '".$_SESSION['user_session']."'";
				
				$submit = mysqli_query($link, $submit_boost) or die("Błąd bazy danych:". mysqli_error($link));
				$delete = mysqli_query($link, $delete_boost) or die("Błąd bazy danych:". mysqli_error($link));
				$add_ukon_zlecenia = mysqli_query($link, $add_ukonczone_zlecenia) or die("Błąd bazy danych:". mysqli_error($link));

				echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><b>Sukces!</b> Zlecenie oznaczono jako zrealizowane.<br>Pamiętaj, iż wartość Twojej wypłaty powiększy się o twoją stawkę za to zlecenie dopiero gdy Administrator zatwierdzi jego wykonanie.</div>';
				}
				else{
				echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><b>Błąd!</b> Zlecenie które próbuje zatwierdzić wcale nie należy do Ciebie!</div>';
				}
			  }	
			  	
				if(isset($_POST['ID_mastery'])) {
				$spr = "SELECT ID_boostera FROM transakcje WHERE ID='".$_POST['ID_mastery']."'";
				$spr2 = mysqli_query($link, $spr) or die("Błąd bazy danych:". mysqli_error($link));
				$spr3 = mysqli_fetch_assoc($spr2);
				
				if($spr3['ID_boostera'] == $id_boostera3['ID']){
				$zlecen_ukonczonych3['Ilosc_wykonanych_zlecen'] = $zlecen_ukonczonych3['Ilosc_wykonanych_zlecen'] + 1;
				$submit_boost = "INSERT INTO zakonczone_transakcje (ID,NROPERACJI,KWOTA,WALUTA,Dywizja,Nick,Login,Haslo,EMAIL,ID_boostera,ID_rodzajzlecenia,Liczba_gier,Liczba_godzin,DUO,Discord,Serwer,Kolejkalol,Ilelp,LPperW,Linie,Czary,Offik,Express,Jedenwin,Streamek,Champek,ChampMasterka,Masterkastart,Masterkaend,Data_zakupu) SELECT ID,NROPERACJI,KWOTA,WALUTA,Dywizja,Nick,Login,Haslo,EMAIL,ID_boostera,ID_rodzajzlecenia,Liczba_gier,Liczba_godzin,DUO,Discord,Serwer,Kolejkalol,Ilelp,LPperW,Linie,Czary,Offik,Express,Jedenwin,Streamek,Champek,ChampMasterka,Masterkastart,Masterkaend,Data_zakupu FROM transakcje WHERE ID = '".$_POST['ID_mastery']."'";
				$delete_boost = "DELETE FROM transakcje WHERE ID = '".$_POST['ID_mastery']."'";
				$add_ukonczone_zlecenia = "UPDATE boosterzy SET Ilosc_wykonanych_zlecen = ".$zlecen_ukonczonych3['Ilosc_wykonanych_zlecen']." WHERE login = '".$_SESSION['user_session']."'";
				
				$submit = mysqli_query($link, $submit_boost) or die("Błąd bazy danych:". mysqli_error($link));
				$delete = mysqli_query($link, $delete_boost) or die("Błąd bazy danych:". mysqli_error($link));
				$add_ukon_zlecenia = mysqli_query($link, $add_ukonczone_zlecenia) or die("Błąd bazy danych:". mysqli_error($link));

				echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><b>Sukces!</b> Zlecenie oznaczono jako zrealizowane.<br>Pamiętaj, iż wartość Twojej wypłaty powiększy się o twoją stawkę za to zlecenie dopiero gdy Administrator zatwierdzi jego wykonanie.</div>';
				}
				else{
				echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><b>Błąd!</b> Zlecenie które próbuje zatwierdzić wcale nie należy do Ciebie!</div>';
				}
			  }	
			?>
			<?php
			$max_zlecen = "SELECT przywileje.max_zlecen FROM przywileje, boosterzy WHERE boosterzy.login='".$_SESSION['user_session']."' AND boosterzy.ID_przywileje=przywileje.ID";
			$max_zlecen2 = mysqli_query($link, $max_zlecen) or die("Błąd bazy danych:". mysqli_error($link));
			$max_zlecen3 = mysqli_fetch_assoc($max_zlecen2);
						
			$ilosc_przydz_zlecen = "SELECT transakcje.ID FROM transakcje, boosterzy WHERE boosterzy.login='".$_SESSION['user_session']."' AND transakcje.ID_boostera = boosterzy.ID";
			$ilosc_przydz_zlecen2 = mysqli_query($link, $ilosc_przydz_zlecen) or die("Błąd bazy danych:". mysqli_error($link));
			$ilosc_przydz_zlecen3 = mysqli_num_rows($ilosc_przydz_zlecen2);
			
			echo '
			<br>
			<div class="progress progress-striped">
			<div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: ';echo ($ilosc_przydz_zlecen3/$max_zlecen3['max_zlecen'])*100; echo'%">
			<span class="sr-only" style="position:relative">Ilość zleceń: ';echo $ilosc_przydz_zlecen3; echo '/'; echo $max_zlecen3['max_zlecen']; echo'</span>
			</div>
			</div>';
			
			

			$moje_zlecenia = "SELECT ID, Kwota, Waluta, Dywizja, Nick, Login, Haslo, ID_rodzajzlecenia, Liczba_gier, Liczba_godzin, DUO, Discord, Serwer, Kolejkalol, Ilelp, LPperW, Notatka, Linie, Czary, Offik, Express, Jedenwin, Streamek, Champek, ChampMasterka, Masterkastart, Masterkaend, Data_zakupu, NROPERACJI,EMAIL  FROM transakcje WHERE ID_boostera=".$id_boostera3['ID']." ORDER BY ID DESC";
			$moje_zlecenia2 = mysqli_query($link, $moje_zlecenia) or die("Błąd bazy danych:". mysqli_error($link));
			
			$ilosc_moich_zlecen = mysqli_query($link, $moje_zlecenia) or die("Błąd bazy danych:". mysqli_error($link));
			$ilosc_moich_zlecen2 = mysqli_num_rows($ilosc_moich_zlecen);
			
			if($ilosc_moich_zlecen2 == 0){
			echo 'Nie posiadasz jeszcze żadnych zleceń. Gdy tylko się jakieś pojawią zadeklaruj swoją chęć wykonania (zakładka Dostępne zlecenia)';
			}
			elseif($row['ID_przywileje'] == 4){		
			echo 'Twoje konto zostało tymczasowo zablokowane do czasu wyjaśnienia sprawy ! Nie łam regulaminu !';
			}
			else{
				while($wynik = mysqli_fetch_array($moje_zlecenia2)) {
					
					$userid = mysqli_query($link,  "SELECT * FROM `clients` WHERE `email` = '$wynik[EMAIL]' LIMIT 1" );
					$userid = mysqli_fetch_assoc($userid);
					$userid = $userid['id'];
					echo'
						<div class="row mt">
							<div class="col-lg-6 col-xs-12">
							  <div class="form-panel">';
							  if($wynik['ID_rodzajzlecenia'] == 1){
								  echo '
								  <h4 class="mb"> Zlecenie od gracza: '.$wynik['Nick'].'</h4>
								  <form class="form-horizontal style-form">
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Rodzaj boostingu</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">
												<span class="label label-success label-mini" style="font-size:13px">Zwykły Boosting</span>
											  </p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">SOLO/DUO</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">';
											  if($wynik['DUO'] == 1){
												echo " <span class='label label-danger label-mini' style='font-size:13px'>DUO</span> ";
												}
												else{
												echo " <span class='label label-info label-mini' style='font-size:13px'>SOLO</span> ";
												}
												echo'</p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Serwer</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">';
											  echo $wynik['Serwer'];
											  echo '</p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Kolejka</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">';
											  echo $wynik['Kolejkalol'];
											  echo '</p>
										  </div>
									  </div>									  
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Dywizje (OD - DO)</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">';
											  echo $wynik['Dywizja'];
											  echo '</p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Kwota za zlecenie</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">';
											  echo $wynik['Kwota']* ($id_boostera3['stawkaprocent']/100);echo $wynik['Waluta'];
											  echo ' </p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Ekspresowy?</label>
										  <div class="col-lg-10">
											  <p class="form-control-static"><b>';
											  echo $wynik['Express'];
											  echo '</b></p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">LP w dywizji</label>
										  <div class="col-lg-10">
											  <p class="form-control-static"><b>';
											  echo $wynik['Ilelp'];
											  echo '</b></p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">LP za win</label>
										  <div class="col-lg-10">
											  <p class="form-control-static"><b>';
											  echo $wynik['LPperW'];
											  echo '</b></p>
										  </div>
									  </div>
									  	<div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Dodatkowy win</label>
										  <div class="col-lg-10">
											  <p class="form-control-static"><b>';
											  echo $wynik['Jedenwin'];
											  echo '</b></p>
										  </div>
									  </div>';
									  if($wynik['DUO'] == 0){
									echo '
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Login do konta</label>
										  <div class="col-lg-10">
											  <p class="form-control-static"><b>';
											  echo $wynik['Login'];
											  echo '</b></p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Hasło do konta</label>
										  <div class="col-lg-10">
											  <p class="form-control-static"><b>';
											  echo $wynik['Haslo'];
											  echo '</b></p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Linie</label>
										  <div class="col-lg-10">
											  <p class="form-control-static"><b>';
											  echo $wynik['Linie'];
											  echo '</b></p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Czary</label>
										  <div class="col-lg-10">
											  <p class="form-control-static"><b>';
											  echo $wynik['Czary'];
											  echo '</b></p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Offline</label>
										  <div class="col-lg-10">
											  <p class="form-control-static"><b>';
											  echo $wynik['Offik'];
											  echo '</b></p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Streaming</label>
										  <div class="col-lg-10">
											  <p class="form-control-static"><b>';
											  echo $wynik['Streamek'];
											  echo '</b></p>
										  </div>
									  </div>
									  	<div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Champion</label>
										  <div class="col-lg-10">
											  <p class="form-control-static"><b>';
											  echo $wynik['Champek'];
											  echo '</b></p>
										  </div>
									  </div>'; }
									  if($wynik['Notatka'] != NULL){
									echo '
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Dodatkowe informacje</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">';
											  echo $wynik['Notatka'];
											  echo '</p>
										  </div>
									  </div>';
									  }
									  echo'
									  <div class="form-group">
										  <div class="col-lg-10">
											  <p class="form-control-static">
											  <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#'.$wynik['ID'].'" style="margin-left:20px; font-size:15px">Oznacz jako wykonane</button>
											</p>
										  </div>
									  </div>';
								  echo '</form>
								  		<div class="modal fade" id="'.$wynik['ID'].'" tabindex="-1" role="dialog" aria-hidden="true">
										  <div class="modal-dialog">
											<div class="modal-content">
											  <div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												<h4 class="modal-title">Czy aby napewno ?</h4>
											  </div>
											  <div class="modal-body">
												Czy aby napewno potwierdzasz wykonanie tego zlecenia ?
											  </div>
											  <div class="modal-footer">
											  <form name="form_boost" method="post">
												<input type="hidden" name="ID_boost" value="'.$wynik['ID'].'" />
												<button type="button" class="btn btn-default" data-dismiss="modal">Nie, pomyłka</button>
												<button type="submit" class="btn btn-primary" name="submit_boost">Tak, zlecenie ukończone</button>
												</form>
											  </div>
											</div>
										  </div>
										</div>';
								  
							  }
							  
							  if($wynik['ID_rodzajzlecenia'] == 2){
								  echo '
								  <h4 class="mb"> Zlecenie od gracza: '.$wynik['Nick'].'</h4>
								  <form class="form-horizontal style-form">
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Rodzaj boostingu</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">
											  <span class="label label-primary label-mini" style="font-size:13px">Boosting per win</span>
											  </p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">SOLO/DUO</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">';
											  if($wynik['DUO'] == 1){
												echo " <span class='label label-danger label-mini' style='font-size:13px'>DUO</span> ";
												}
												else{
												echo " <span class='label label-info label-mini' style='font-size:13px'>SOLO</span> ";
												}
												echo'</p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Serwer</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">';
											  echo $wynik['Serwer'];
											  echo '</p>
										  </div>
									  </div>
										<div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Kolejka</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">';
											  echo $wynik['Kolejkalol'];
											  echo '</p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Aktualna Dywizja</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">';
											  echo $wynik['Dywizja'];
											  echo '</p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Liczba zwycięstw</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">';
											  echo $wynik['Liczba_gier'];
											  echo '</p>
										  </div>
									  </div>					
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Kwota za zlecenie</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">';
											  echo $wynik['Kwota']* ($id_boostera3['stawkaprocent']/100);echo $wynik['Waluta'];
											  echo ' </p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Dodatkowy win</label>
										  <div class="col-lg-10">
											  <p class="form-control-static"><b>';
											  echo $wynik['Jedenwin'];
											  echo '</b></p>
										  </div>
									  </div>									  
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Ekspresowy?</label>
										  <div class="col-lg-10">
											  <p class="form-control-static"><b>';
											  echo $wynik['Express'];
											  echo '</b></p>
										  </div>
									  </div>';
									  if($wynik['DUO'] == 0){
									echo '
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Login do konta</label>
										  <div class="col-lg-10">
											  <p class="form-control-static"><b>';
											  echo $wynik['Login'];
											  echo '</b></p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Hasło do konta</label>
										  <div class="col-lg-10">
											  <p class="form-control-static"><b>';
											  echo $wynik['Haslo'];
											  echo '</b></p>
										  </div>
									  </div>									
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Linie</label>
										  <div class="col-lg-10">
											  <p class="form-control-static"><b>';
											  echo $wynik['Linie'];
											  echo '</b></p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Czary</label>
										  <div class="col-lg-10">
											  <p class="form-control-static"><b>';
											  echo $wynik['Czary'];
											  echo '</b></p>
										  </div>
									  </div>									  
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Offline</label>
										  <div class="col-lg-10">
											  <p class="form-control-static"><b>';
											  echo $wynik['Offik'];
											  echo '</b></p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Streaming</label>
										  <div class="col-lg-10">
											  <p class="form-control-static"><b>';
											  echo $wynik['Streamek'];
											  echo '</b></p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Champion</label>
										  <div class="col-lg-10">
											  <p class="form-control-static"><b>';
											  echo $wynik['Champek'];
											  echo '</b></p>
										  </div>
									  </div>'; }
									  if($wynik['Notatka'] != NULL){
									echo '
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Dodatkowe informacje</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">';
											  echo $wynik['Notatka'];
											  echo '</p>
										  </div>
									  </div>';
									  }
									  echo'
									  <div class="form-group">
										  <div class="col-lg-10">
											  <p class="form-control-static">
											  <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#'.$wynik['ID'].'" style="margin-left:20px; font-size:15px">Oznacz jako wykonane</button>
											</p>
										  </div>
									  </div>';
								  echo '</form>
								  		<div class="modal fade" id="'.$wynik['ID'].'" tabindex="-1" role="dialog" aria-hidden="true">
										  <div class="modal-dialog">
											<div class="modal-content">
											  <div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												<h4 class="modal-title">Czy aby napewno ?</h4>
											  </div>
											  <div class="modal-body">
												Czy aby napewno potwierdzasz wykonanie tego zlecenia ?
											  </div>
											  <div class="modal-footer">
											  <form name="form_boostperwin" method="post">
												<input type="hidden" name="ID_boostperwin" value="'.$wynik['ID'].'" />
												<button type="button" class="btn btn-default" data-dismiss="modal">Nie, pomyłka</button>
												<button type="submit" class="btn btn-primary" name="submit_boostperwin">Tak, zlecenie ukończone</button>
												</form>
											  </div>
											</div>
										  </div>
										</div>';
							  }
							  
							  if($wynik['ID_rodzajzlecenia'] == 3){
								  echo '
								  <h4 class="mb"> Zlecenie od gracza: '.$wynik['Nick'].'</h4>
								  <form class="form-horizontal style-form">
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Rodzaj boostingu</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">
											  <span class="label label-warning label-mini" style="font-size:13px">Placement Games</span>
											  </p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">SOLO/DUO</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">';
											  if($wynik['DUO'] == 1){
												echo " <span class='label label-danger label-mini' style='font-size:13px'>DUO</span> ";
												}
												else{
												echo " <span class='label label-info label-mini' style='font-size:13px'>SOLO</span> ";
												}
												echo'</p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Serwer</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">';
											  echo $wynik['Serwer'];
											  echo '</p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Kolejka</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">';
											  echo $wynik['Kolejkalol'];
											  echo '</p>
										  </div>
									  </div>									  
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Poprzedna Dywizja</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">';
											  echo $wynik['Dywizja'];
											  echo '</p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Liczba gier do zagrania</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">';
											  echo $wynik['Liczba_gier'];
											  echo '</p>
										  </div>
									  </div>
									  	  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Dodatkowy win</label>
										  <div class="col-lg-10">
											  <p class="form-control-static"><b>';
											  echo $wynik['Jedenwin'];
											  echo '</b></p>
										  </div>
									  </div>									  
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Kwota za zlecenie</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">';
											  echo $wynik['Kwota']* ($id_boostera3['stawkaprocent']/100);echo $wynik['Waluta'];
											  echo ' </p>
										  </div>
									  </div>									  
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Ekspresowy?</label>
										  <div class="col-lg-10">
											  <p class="form-control-static"><b>';
											  echo $wynik['Express'];
											  echo '</b></p>
										  </div>
									  </div>';
									  if($wynik['DUO'] == 0){
									echo '
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Login do konta</label>
										  <div class="col-lg-10">
											  <p class="form-control-static"><b>';
											  echo $wynik['Login'];
											  echo '</b></p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Hasło do konta</label>
										  <div class="col-lg-10">
											  <p class="form-control-static"><b>';
											  echo $wynik['Haslo'];
											  echo '</b></p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Linie</label>
										  <div class="col-lg-10">
											  <p class="form-control-static"><b>';
											  echo $wynik['Linie'];
											  echo '</b></p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Czary</label>
										  <div class="col-lg-10">
											  <p class="form-control-static"><b>';
											  echo $wynik['Czary'];
											  echo '</b></p>
										  </div>
									  </div>									  
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Offline</label>
										  <div class="col-lg-10">
											  <p class="form-control-static"><b>';
											  echo $wynik['Offik'];
											  echo '</b></p>
										  </div>
									  </div>									  
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Streaming</label>
										  <div class="col-lg-10">
											  <p class="form-control-static"><b>';
											  echo $wynik['Streamek'];
											  echo '</b></p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Champion</label>
										  <div class="col-lg-10">
											  <p class="form-control-static"><b>';
											  echo $wynik['Champek'];
											  echo '</b></p>
										  </div>
									  </div>'; }
									  if($wynik['Notatka'] != NULL){
									echo '
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Dodatkowe informacje</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">';
											  echo $wynik['Notatka'];
											  echo '</p>
										  </div>
									  </div>';
									  }
									  echo'
									  <div class="form-group">
										  <div class="col-lg-10">
											  <p class="form-control-static">
											  <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#'.$wynik['ID'].'" style="margin-left:20px; font-size:15px">Oznacz jako wykonane</button>
											</p>
										  </div>
									  </div>';
								  echo '</form>
								  		<div class="modal fade" id="'.$wynik['ID'].'" tabindex="-1" role="dialog" aria-hidden="true">
										  <div class="modal-dialog">
											<div class="modal-content">
											  <div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												<h4 class="modal-title">Czy aby napewno ?</h4>
											  </div>
											  <div class="modal-body">
												Czy aby napewno potwierdzasz wykonanie tego zlecenia ?
											  </div>
											  <div class="modal-footer">
											  <form name="form_placements" method="post">
												<input type="hidden" name="ID_placements" value="'.$wynik['ID'].'" />
												<button type="button" class="btn btn-default" data-dismiss="modal">Nie, pomyłka</button>
												<button type="submit" class="btn btn-primary" name="submit_placements">Tak, zlecenie ukończone</button>
												</form>
											  </div>
											</div>
										  </div>
										</div>';
							  }
							  
							  if($wynik['ID_rodzajzlecenia'] == 4){
								  echo '
								  <h4 class="mb"> Zlecenie od gracza: '.$wynik['Nick'].'</h4>
								  <form class="form-horizontal style-form">
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Rodzaj boostingu</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">
												<span class="label label-danger label-mini" style="font-size:13px">Coaching</span>
											</p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Serwer</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">';
											  echo $wynik['Serwer'];
											  echo '</p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Kolejka</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">';
											  echo $wynik['Kolejkalol'];
											  echo '</p>
										  </div>
									  </div>									  
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Liczba godzin</label>
										  <div class="col-lg-10">
											  <p class="form-control-static"><b>';
											  echo $wynik['Liczba_godzin'];
											  echo '</b></p>
										  </div>
									  </div>					
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Kwota za zlecenie</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">';
											  echo $wynik['Kwota']* ($id_boostera3['stawkaprocent']/100);echo $wynik['Waluta'];
											  echo ' </p>
										  </div>
									  </div>';
									  if($wynik['Notatka'] != NULL){
									echo '
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Dodatkowe informacje</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">';
											  echo $wynik['Notatka'];
											  echo '</p>
										  </div>
									  </div>';
									  }
									  echo'
									  <div class="form-group">
										  <div class="col-lg-10">
											  <p class="form-control-static">
											  <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#'.$wynik['ID'].'" style="margin-left:20px; font-size:15px">Oznacz jako wykonane</button>
											</p>
										  </div>
									  </div>';
								  echo '</form>
								  		<div class="modal fade" id="'.$wynik['ID'].'" tabindex="-1" role="dialog" aria-hidden="true">
										  <div class="modal-dialog">
											<div class="modal-content">
											  <div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												<h4 class="modal-title">Czy aby napewno ?</h4>
											  </div>
											  <div class="modal-body">
												Czy aby napewno potwierdzasz wykonanie tego zlecenia ?
											  </div>
											  <div class="modal-footer">
											  <form name="form_coaching" method="post">
												<input type="hidden" name="ID_coaching" value="'.$wynik['ID'].'" />
												<button type="button" class="btn btn-default" data-dismiss="modal">Nie, pomyłka</button>
												<button type="submit" class="btn btn-primary" name="submit_coaching">Tak, zlecenie ukończone</button>
												</form>
											  </div>
											</div>
										  </div>
										</div>';
							  }
							  
							  if($wynik['ID_rodzajzlecenia'] == 5){
								  echo '
								  <h4 class="mb"> Zlecenie od gracza: '.$wynik['Nick'].'</h4>
								  <form class="form-horizontal style-form">
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Rodzaj boostingu</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">
												<span class="label label-success label-mini" style="font-size:13px">Zwykły TFT Boosting</span>
											  </p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">SOLO/DUO</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">';
											  if($wynik['DUO'] == 1){
												echo " <span class='label label-danger label-mini' style='font-size:13px'>DUO</span> ";
												}
												else{
												echo " <span class='label label-info label-mini' style='font-size:13px'>SOLO</span> ";
												}
												echo'</p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Serwer</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">';
											  echo $wynik['Serwer'];
											  echo '</p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Kolejka</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">';
											  echo $wynik['Kolejkalol'];
											  echo '</p>
										  </div>
									  </div>									  
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Dywizje (OD - DO)</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">';
											  echo $wynik['Dywizja'];
											  echo '</p>
										  </div>
									  </div>
									  	  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Dodatkowy win</label>
										  <div class="col-lg-10">
											  <p class="form-control-static"><b>';
											  echo $wynik['Jedenwin'];
											  echo '</b></p>
										  </div>
									  </div>									
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Kwota za zlecenie</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">';
											  echo $wynik['Kwota']* ($id_boostera3['stawkaprocent']/100);echo $wynik['Waluta'];
											  echo ' </p>
										  </div>
									  </div>';
									  if($wynik['DUO'] == 0){
									echo '
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Login do konta</label>
										  <div class="col-lg-10">
											  <p class="form-control-static"><b>';
											  echo $wynik['Login'];
											  echo '</b></p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Hasło do konta</label>
										  <div class="col-lg-10">
											  <p class="form-control-static"><b>';
											  echo $wynik['Haslo'];
											  echo '</b></p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Ekspresowy?</label>
										  <div class="col-lg-10">
											  <p class="form-control-static"><b>';
											  echo $wynik['Express'];
											  echo '</b></p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Dodatkowy win</label>
										  <div class="col-lg-10">
											  <p class="form-control-static"><b>';
											  echo $wynik['Jedenwin'];
											  echo '</b></p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Streaming</label>
										  <div class="col-lg-10">
											  <p class="form-control-static"><b>';
											  echo $wynik['Streamek'];
											  echo '</b></p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Champion</label>
										  <div class="col-lg-10">
											  <p class="form-control-static"><b>';
											  echo $wynik['Champek'];
											  echo '</b></p>
										  </div>
									  </div>'; }
									  if($wynik['Notatka'] != NULL){
									echo '
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Dodatkowe informacje</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">';
											  echo $wynik['Notatka'];
											  echo '</p>
										  </div>
									  </div>';
									  }
									  echo'
									  <div class="form-group">
										  <div class="col-lg-10">
											  <p class="form-control-static">
											  <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#'.$wynik['ID'].'" style="margin-left:20px; font-size:15px">Oznacz jako wykonane</button>
											</p>
										  </div>
									  </div>';
								  echo '</form>
								  		<div class="modal fade" id="'.$wynik['ID'].'" tabindex="-1" role="dialog" aria-hidden="true">
										  <div class="modal-dialog">
											<div class="modal-content">
											  <div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												<h4 class="modal-title">Czy aby napewno ?</h4>
											  </div>
											  <div class="modal-body">
												Czy aby napewno potwierdzasz wykonanie tego zlecenia ?
											  </div>
											  <div class="modal-footer">
											  <form name="form_boost" method="post">
												<input type="hidden" name="ID_boost" value="'.$wynik['ID'].'" />
												<button type="button" class="btn btn-default" data-dismiss="modal">Nie, pomyłka</button>
												<button type="submit" class="btn btn-primary" name="submit_boost">Tak, zlecenie ukończone</button>
												</form>
											  </div>
											</div>
										  </div>
										</div>';
								  
							  }
							  
							  if($wynik['ID_rodzajzlecenia'] == 6){
								  echo '
								  <h4 class="mb"> Zlecenie od gracza: '.$wynik['Nick'].'</h4>
								  <form class="form-horizontal style-form">
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Rodzaj boostingu</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">
											  <span class="label label-primary label-mini" style="font-size:13px">Boosting TFT per win</span>
											  </p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">SOLO/DUO</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">';
											  if($wynik['DUO'] == 1){
												echo " <span class='label label-danger label-mini' style='font-size:13px'>DUO</span> ";
												}
												else{
												echo " <span class='label label-info label-mini' style='font-size:13px'>SOLO</span> ";
												}
												echo'</p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Serwer</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">';
											  echo $wynik['Serwer'];
											  echo '</p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Kolejka</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">';
											  echo $wynik['Kolejkalol'];
											  echo '</p>
										  </div>
									  </div>									 
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Aktualna Dywizja</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">';
											  echo $wynik['Dywizja'];
											  echo '</p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Liczba zwycięstw</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">';
											  echo $wynik['Liczba_gier'];
											  echo '</p>
										  </div>
									  </div>
									  	  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Dodatkowy win</label>
										  <div class="col-lg-10">
											  <p class="form-control-static"><b>';
											  echo $wynik['Jedenwin'];
											  echo '</b></p>
										  </div>
									  </div>									
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Kwota za zlecenie</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">';
											  echo $wynik['Kwota']* ($id_boostera3['stawkaprocent']/100);echo $wynik['Waluta'];
											  echo ' </p>
										  </div>
									  </div>';
									  if($wynik['DUO'] == 0){
									echo '
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Login do konta</label>
										  <div class="col-lg-10">
											  <p class="form-control-static"><b>';
											  echo $wynik['Login'];
											  echo '</b></p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Hasło do konta</label>
										  <div class="col-lg-10">
											  <p class="form-control-static"><b>';
											  echo $wynik['Haslo'];
											  echo '</b></p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Ekspresowy?</label>
										  <div class="col-lg-10">
											  <p class="form-control-static"><b>';
											  echo $wynik['Express'];
											  echo '</b></p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Dodatkowy win</label>
										  <div class="col-lg-10">
											  <p class="form-control-static"><b>';
											  echo $wynik['Jedenwin'];
											  echo '</b></p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Streaming</label>
										  <div class="col-lg-10">
											  <p class="form-control-static"><b>';
											  echo $wynik['Streamek'];
											  echo '</b></p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Champion</label>
										  <div class="col-lg-10">
											  <p class="form-control-static"><b>';
											  echo $wynik['Champek'];
											  echo '</b></p>
										  </div>
									  </div>'; }
									  if($wynik['Notatka'] != NULL){
									echo '
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Dodatkowe informacje</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">';
											  echo $wynik['Notatka'];
											  echo '</p>
										  </div>
									  </div>';
									  }
									  echo'
									  <div class="form-group">
										  <div class="col-lg-10">
											  <p class="form-control-static">
											  <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#'.$wynik['ID'].'" style="margin-left:20px; font-size:15px">Oznacz jako wykonane</button>
											</p>
										  </div>
									  </div>';
								  echo '</form>
								  		<div class="modal fade" id="'.$wynik['ID'].'" tabindex="-1" role="dialog" aria-hidden="true">
										  <div class="modal-dialog">
											<div class="modal-content">
											  <div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												<h4 class="modal-title">Czy aby napewno ?</h4>
											  </div>
											  <div class="modal-body">
												Czy aby napewno potwierdzasz wykonanie tego zlecenia ?
											  </div>
											  <div class="modal-footer">
											  <form name="form_boostperwin" method="post">
												<input type="hidden" name="ID_boostperwin" value="'.$wynik['ID'].'" />
												<button type="button" class="btn btn-default" data-dismiss="modal">Nie, pomyłka</button>
												<button type="submit" class="btn btn-primary" name="submit_boostperwin">Tak, zlecenie ukończone</button>
												</form>
											  </div>
											</div>
										  </div>
										</div>';
							  }
							  
							  if($wynik['ID_rodzajzlecenia'] == 7){
								  echo '
								  <h4 class="mb"> Zlecenie od gracza: '.$wynik['Nick'].'</h4>
								  <form class="form-horizontal style-form">
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Rodzaj boostingu</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">
											  <span class="label label-warning label-mini" style="font-size:13px">Placement Games TFT</span>
											  </p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">SOLO/DUO</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">';
											  if($wynik['DUO'] == 1){
												echo " <span class='label label-danger label-mini' style='font-size:13px'>DUO</span> ";
												}
												else{
												echo " <span class='label label-info label-mini' style='font-size:13px'>SOLO</span> ";
												}
												echo'</p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Serwer</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">';
											  echo $wynik['Serwer'];
											  echo '</p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Kolejka</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">';
											  echo $wynik['Kolejkalol'];
											  echo '</p>
										  </div>
									  </div>									  
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Poprzedna Dywizja</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">';
											  echo $wynik['Dywizja'];
											  echo '</p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Liczba gier do zagrania</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">';
											  echo $wynik['Liczba_gier'];
											  echo '</p>
										  </div>
									  </div>
									  	  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Dodatkowy win</label>
										  <div class="col-lg-10">
											  <p class="form-control-static"><b>';
											  echo $wynik['Jedenwin'];
											  echo '</b></p>
										  </div>
									  </div>						
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Kwota za zlecenie</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">';
											  echo $wynik['Kwota']* ($id_boostera3['stawkaprocent']/100);echo $wynik['Waluta'];
											  echo ' </p>
										  </div>
									  </div>';
									  if($wynik['DUO'] == 0){
									echo '
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Login do konta</label>
										  <div class="col-lg-10">
											  <p class="form-control-static"><b>';
											  echo $wynik['Login'];
											  echo '</b></p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Hasło do konta</label>
										  <div class="col-lg-10">
											  <p class="form-control-static"><b>';
											  echo $wynik['Haslo'];
											  echo '</b></p>
										  </div>
									  </div>		  
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Ekspresowy?</label>
										  <div class="col-lg-10">
											  <p class="form-control-static"><b>';
											  echo $wynik['Express'];
											  echo '</b></p>
										  </div>
									  </div>
									  	  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Dodatkowy win</label>
										  <div class="col-lg-10">
											  <p class="form-control-static"><b>';
											  echo $wynik['Jedenwin'];
											  echo '</b></p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Streaming</label>
										  <div class="col-lg-10">
											  <p class="form-control-static"><b>';
											  echo $wynik['Streamek'];
											  echo '</b></p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Champion</label>
										  <div class="col-lg-10">
											  <p class="form-control-static"><b>';
											  echo $wynik['Champek'];
											  echo '</b></p>
										  </div>
									  </div>'; }
									  if($wynik['Notatka'] != NULL){
									echo '
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Dodatkowe informacje</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">';
											  echo $wynik['Notatka'];
											  echo '</p>
										  </div>
									  </div>';
									  }
									  echo'
									  <div class="form-group">
										  <div class="col-lg-10">
											  <p class="form-control-static">
											  <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#'.$wynik['ID'].'" style="margin-left:20px; font-size:15px">Oznacz jako wykonane</button>
											</p>
										  </div>
									  </div>';
								  echo '</form>
								  		<div class="modal fade" id="'.$wynik['ID'].'" tabindex="-1" role="dialog" aria-hidden="true">
										  <div class="modal-dialog">
											<div class="modal-content">
											  <div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												<h4 class="modal-title">Czy aby napewno ?</h4>
											  </div>
											  <div class="modal-body">
												Czy aby napewno potwierdzasz wykonanie tego zlecenia ?
											  </div>
											  <div class="modal-footer">
											  <form name="form_placementstft" method="post">
												<input type="hidden" name="ID_placementstft" value="'.$wynik['ID'].'" />
												<button type="button" class="btn btn-default" data-dismiss="modal">Nie, pomyłka</button>
												<button type="submit" class="btn btn-primary" name="submit_placementstft">Tak, zlecenie ukończone</button>
												</form>
											  </div>
											</div>
										  </div>
										</div>';
							  }
							  							

								if($wynik['ID_rodzajzlecenia'] == 8){
								  echo '
								  <h4 class="mb"> Zlecenie od gracza: '.$wynik['Nick'].'</h4>
								  <form class="form-horizontal style-form">
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Rodzaj boostingu</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">
												<span class="label label-danger label-mini" style="font-size:13px">Mastery Champion</span>
											</p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Serwer</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">';
											  echo $wynik['Serwer'];
											  echo '</p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Champion</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">';
											  echo $wynik['ChampMasterka'];
											  echo '</p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Masteria początkowa:</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">';
											  echo $wynik['Masterkastart'];
											  echo '</p>
										  </div>
									  </div>
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Masteria końcowa:</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">';
											  echo $wynik['Masterkaend'];
											  echo '</p>
										  </div>
									  </div>									  						
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Kwota za zlecenie</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">';
											  echo $wynik['Kwota']* ($id_boostera3['stawkaprocent']/100);echo $wynik['Waluta'];
											  echo ' </p>
										  </div>
									  </div>';
									  if($wynik['Notatka'] != NULL){
									echo '
									  <div class="form-group">
										  <label class="col-lg-2 col-sm-2 control-label">Dodatkowe informacje</label>
										  <div class="col-lg-10">
											  <p class="form-control-static">';
											  echo $wynik['Notatka'];
											  echo '</p>
										  </div>
									  </div>';
									  }
									  echo'
									  <div class="form-group">
										  <div class="col-lg-10">
											  <p class="form-control-static">
											  <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#'.$wynik['ID'].'" style="margin-left:20px; font-size:15px">Oznacz jako wykonane</button>
											</p>
										  </div>
									  </div>';
								  echo '</form>
								  		<div class="modal fade" id="'.$wynik['ID'].'" tabindex="-1" role="dialog" aria-hidden="true">
										  <div class="modal-dialog">
											<div class="modal-content">
											  <div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												<h4 class="modal-title">Czy aby napewno ?</h4>
											  </div>
											  <div class="modal-body">
												Czy aby napewno potwierdzasz wykonanie tego zlecenia ?
											  </div>
											  <div class="modal-footer">
											  <form name="form_mastery" method="post">
												<input type="hidden" name="ID_mastery" value="'.$wynik['ID'].'" />
												<button type="button" class="btn btn-default" data-dismiss="modal">Nie, pomyłka</button>
												<button type="submit" class="btn btn-primary" name="submit_mastery">Tak, zlecenie ukończone</button>
												</form>
											  </div>
											</div>
										  </div>
										</div>';
							  }
							?>
							  </div>
							</div>  
							<?php if($chat_shown == false) { ?>
							<div class="col-lg-6 col-xs-12">
								<H1>CZAT</h1>
										<div class="chat2">	
								<div id="frame">		
									<div id="sidepanel">
										<div id="profile">
										<?php
										$chat_shown = true;
										$chat = new Chat();
										$loggedUser = $chat->getUserDetails($user_data['id']);
										echo '<div class="wrap">';
											echo '<img id="profile-img" src="img/user3.jpg" class="online" alt="" />';
											echo  '<p>'.$row['login'].'</p>';
										echo '</div>';
										?>
										</div>
										<div id="contacts">	
										<?php
										echo '<ul>';
										$chatUsers = $chat->boosterClients($id_boostera3['ID']);
										//print_r($chatUsers);
										foreach ($chatUsers as $user) {
											$client_info = mysqli_query($link, "SELECT * FROM `transakcje` WHERE `ID` = ".$user['NROPERACJI']."");
											$client_info = mysqli_fetch_assoc($client_info);
											$activeUser = "active";
											if(isset($client_info['NROPERACJI'])){
											echo '<li id="'.$user['id'].'" class="contact" data-touserid="'.$user['id'].'" data-tousername="'.$client_info['Nick'].'">';
											echo '<div class="wrap">';
											echo '<img src="img/user5.jpg" alt="" />';
											echo '<div class="meta">';
											echo '<p class="name">Klient: '.$client_info['Nick'].'</p>';
											echo '</div>';
											echo '</div>';
											echo '</li>'; 
										}
										}
										echo '</ul>';
										?>
										</div>
									</div>			
									<div class="content" id="content"> 
										<div class="contact-profile" id="userSection">
										<span style="margin-left:20px;">Klient (nick): <b><?=$client_info['Nick'];?></b></span>
					
										</div>
										<div class="messages" id="conversation" style="width:100%">		
										<?php
										echo $chat->getUserChat($id_boostera3['ID'], $user['id']);						
										?>
										</div>
										<div class="message-input" id="replySection">				
											<div class="message-input" id="replyContainer">
												<div class="wrap">
													<input type="text" class="chatMessage" id="chatMessage<?php echo  $user['id']; ?>" placeholder="Wpisz swoją wiadomość..." />
													<button class="submit chatButton" id="chatButton<?php echo  $user['id']; ?>"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>	
												</div>
											</div>					
										</div>
									</div>
								</div>
							</div>
							</div>	
							<?php } ?>
						</div><?php
						
				}
			}
			?>
                     
          </section>
      </section>
  </section>

    <script src="panel_assets/assets/js/jquery.js"></script>
    <script src="panel_assets/assets/js/jquery-1.8.3.min.js"></script>
    <script src="panel_assets/assets/js/bootstrap.min.js"></script>
    <script class="include" type="text/javascript" src="panel_assets/assets/js/jquery.dcjqaccordion.2.7.js"></script>
    <script src="panel_assets/assets/js/jquery.scrollTo.min.js"></script>
    <script src="panel_assets/assets/js/jquery.nicescroll.js" type="text/javascript"></script>
    <script src="panel_assets/assets/js/jquery.sparkline.js"></script>
	<script src="js/chat.js"></script>

    <script src="panel_assets/assets/js/common-scripts.js"></script>
    
    <script type="text/javascript" src="panel_assets/assets/js/gritter/js/jquery.gritter.js"></script>
    <script type="text/javascript" src="panel_assets/assets/js/gritter-conf.js"></script>

  </body>
</html>
