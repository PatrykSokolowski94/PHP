<?php
session_start();
if(!isset($_SESSION['client_session'])){
	header("Location: logowanie_klient.php");
}
include_once("baza-danych/config.php");

$resultset = mysqli_query($link, "SELECT * FROM clients WHERE email='".$_SESSION['client_session']."'") or die("Błąd bazy danych:". mysqli_error($link));
$user_data = mysqli_fetch_assoc($resultset);

$order = mysqli_query($link,"SELECT * FROM `transakcje` WHERE `EMAIL` = '$user_data[email]' AND `ID` = $user_data[NROPERACJI]" );
$orders = mysqli_fetch_array($order);


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
	
	<link rel="stylesheet" href="css/custom.css">
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
            <a href="index.html" class="logo"><b>Witaj <?php echo $orders['Nick']; ?>!</b></a>
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
                      <a class="active" href="panel_klient">
                          <i class="fa fa-dashboard"></i>
                          <span>Strona główna panelu</span>
                      </a>
                  </li>

                  <li class="sub-menu">
                      <a href="javascript:;" >
                          <i class="fa fa-cogs"></i>
                          <span>Konto</span>
                      </a>
                      <ul class="sub">
						  <li><a  href="zmien-haslo-klient">Zmień hasło</a></li>
                      </ul>
                  </li>
              </ul>
          </div>
      </aside>

      <section id="main-content">
          <section class="wrapper">
		    <h3><i class="fa fa-angle-right"></i> Postęp boostingu <?php echo $orders['Nick']; ?></h3>
			
			  <?php
$region = $orders['Serwer'];
$summonerName = $orders['Nick'];

if(strpos($summonerName, " ") !== false){
$summonerName = str_replace(" ", "%20", $summonerName);
}
$apiKey = "RGAPI-7754ec86-523d-4e97-96d5-1ee28e81ee6f";

$summonerURL = "https://". $region . "1.api.riotgames.com/lol/summoner/v4/summoners/by-name/". $summonerName ."?api_key=". $apiKey;
$summonerResult = file_get_contents($summonerURL);

$summonerDecoded = json_decode($summonerResult, true);
$summonerId = $summonerDecoded['id'];
$rankInfoURL = "https://". $region . "1.api.riotgames.com/lol/league/v4/entries/by-summoner/". $summonerId ."?api_key=". $apiKey;
$rankInfoResult = file_get_contents($rankInfoURL);

$rankInfoDecoded = json_decode($rankInfoResult, true);

$ile = count($rankInfoDecoded);

for($i = 0; $i < $ile; $i++){
	if($rankInfoDecoded[$i]['queueType'] == "RANKED_SOLO_5x5"){
	$tier = $rankInfoDecoded[$i]['tier'];
	$rank = $rankInfoDecoded[$i]['rank'];
	$leaguePoints = $rankInfoDecoded[$i]['leaguePoints'];
	$wins = $rankInfoDecoded[$i]['wins'];
	$losses = $rankInfoDecoded[$i]['losses'];
	$leagueName = $rankInfoDecoded[$i]['leagueName'];
	}
}

if(strpos($summonerName, "%20") !== false){
$summonerName = str_replace("%20", " ", $summonerName);
}
$ranga = explode('-',$orders['Dywizja']);
$img_current = ucfirst(strtolower($tier)).".png";

$img_start =   explode(' ', $ranga[0]);
$img_start = $img_start[0].'.png';

$img_target =   explode(' ', $ranga[1]);
$img_target = $img_target[1].'.png';

$tier = ucfirst(strtolower($tier));
  ?>
  
<?php
			if(($orders['ID_rodzajzlecenia'] == 1) && ($orders['ID_boostera'] != 0)){
			echo '<div class="row">;
			 <div class="col-md-4 col-sm-4 col-xs-4 text-center"><b>START</b><br> 
			<img src="img/'.$img_start.'"><br>';	
			echo $ranga[0];
			echo '
			</div>		
			<div class="col-md-4 col-sm-4 col-xs-4 text-center"><b>CURRENT</b><Br> 	
			<img src="img/'.$img_current.'"><br>';		 
			echo $tier . ' ' . $rank . '<br>' . $leaguePoints . ' ' . "LP";
			echo '<br>' . $wins . ' ' . '<b style="color:green">WYGRANE</b>' .  ' ' . $losses . ' '  . '<b style="color:red">PRZEGRANE</b>';
			echo '
			</div>
			<div class="col-md-4 col-sm-4 col-xs-4 text-center "><b>TARGET</b><Br> 		
			<img src="img/'.$img_target.'"><br>'; 
			echo $ranga[1];
			echo '</div>
		 </div>


  </div>';
			}
			elseif(($orders['ID_rodzajzlecenia'] == 2) && ($orders['ID_boostera'] != 0)){
			echo '<div class="row">;';	
			echo '		
			<div class="col-md-12 col-sm-12 col-xs-12 text-center"><b>NA WYGRANE</b><Br> 	
			<img src="img/'.$img_current.'"><br>';		 
			echo $tier . ' ' . $rank . '<br>' . $leaguePoints . "LP";
			echo '<br>' . $wins . ' ' . '<b style="color:green">WYGRANE</b>' .  ' ' . $losses . ' '  . '<b style="color:red">PRZEGRANE</b>';
			echo '</div>
		 </div>


  </div>';}			
			elseif(($orders['ID_rodzajzlecenia'] == 3) && ($orders['ID_boostera'] != 0)){
			echo '<div class="row">';	
			echo '	
			<div class="col-md-12 col-sm-12 col-xs-12 text-center"><b>PLACEMENTY</b><Br> 	
			<img src="img/'.$img_current.'"><br>';		 
			echo $tier . ' ' . $rank . '<br>' . $leaguePoints . "LP";
			echo '<br>' . $wins . ' ' . '<b style="color:green">WYGRANE</b>' .  ' ' . $losses . ' '  . '<b style="color:red">PRZEGRANE</b>';
			echo '
			</div>
			</div>
		 </div>


  </div>
			<div>';}
			elseif($orders['ID_boostera'] == 0){
			echo '
			<div class="row">		
			<div class="col-md-12 col-sm-12 col-xs-12 text-center"><b>JESZCZE ŻADEN BOOSTER NIE PRZYJĄŁ TWOJEGO ZLECENIA !</b><Br>		  
			</div>
			</div>
  </div>
			<div>';}
			?>

                     
          </section>
		  <div class="container">
		  <div class="col-md-12">
<h1>Chat with booster</h1>

		<div class="chat2">	
			<div id="frame">		
				<div id="sidepanel">
					<div id="profile">
					<?php
					include ('php/Chat.php');
					$chat = new Chat();
					$loggedUser = $chat->getUserDetails($user_data['id']);
					echo '<div class="wrap">';
					foreach ($loggedUser as $user) {
						echo '<img id="profile-img" src="img/user3.jpg" class="online" alt="" />';
						echo  '<p>'.$user['email'].'</p>';
					}
					echo '</div>';
					?>
					</div>
					<div id="contacts">	
					<?php
					echo '<ul>';
					$chatUsers = $chat->chatUsers($orders['ID_boostera']);
					foreach ($chatUsers as $user) {
						echo '<li id="'.$user['ID'].'" class="contact active" data-touserid="'.$user['ID'].'" data-tousername="'.$user['login'].'">';
						echo '<div class="wrap">';
						echo '<img src="img/user5.jpg" alt="" />';
						echo '<div class="meta">';
						echo '<p class="name">BOOSTER: '.$user['login'].'</p>';
						echo '</div>';
						echo '</div>';
						echo '</li>'; 
					}
					echo '</ul>';
					?>
					</div>
				</div>			
				<div class="content" id="content"> 
			
					<div class="contact-profile" id="userSection">
						<span style="margin-left:20px;">Chat with booster</span>
					<?php
					$userDetails = $chat->getUserDetails($orders['ID_boostera']);

					foreach ($userDetails as $user) {										
						echo '<img src="userpics/'.$user['avatar'].'" alt="" />';
					}	
					?>						
					</div>
					<div class="messages" id="conversation">		
					<?php
					echo $chat->getUserChat($user_data['id'], $orders['ID_boostera']);						
					?>
					</div>
					<div class="message-input" id="replySection">				
						<div class="message-input" id="replyContainer">
							<div class="wrap">
								<input type="text" class="chatMessage" id="chatMessage<?php echo  $orders['ID_boostera']; ?>" placeholder="Please enter your message..." />
								<button class="submit chatButton" id="chatButton<?php echo  $orders['ID_boostera']; ?>"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>	
							</div>
						</div>					
					</div>
				</div>
			</div>
		</div>
</div>
</div>
		
		
		
		
		
		
	</section>
	 
  </section>

    <script src="panel_assets/assets/js/jquery.js"></script>
    <script src="panel_assets/assets/js/jquery-1.8.3.min.js"></script>
    <script src="panel_assets/assets/js/bootstrap.min.js"></script>
    <script class="include" type="text/javascript" src="panel_assets/assets/js/jquery.dcjqaccordion.2.7.js"></script>
    <script src="panel_assets/assets/js/jquery.scrollTo.min.js"></script>
    <script src="panel_assets/assets/js/jquery.nicescroll.js" type="text/javascript"></script>
    <script src="panel_assets/assets/js/jquery.sparkline.js"></script>
	<script src="panel_assets/assets/js/jquery.maskedinput.min.js"></script>
    <script src="panel_assets/assets/js/common-scripts.js"></script>
	<script src="js/chat.js"></script>
    
    <script type="text/javascript" src="panel_assets/assets/js/gritter/js/jquery.gritter.js"></script>
    <script type="text/javascript" src="panel_assets/assets/js/gritter-conf.js"></script>

  </body>
</html>
