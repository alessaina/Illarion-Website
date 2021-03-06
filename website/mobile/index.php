<!-- Mobile web page Illarion -->
<!-- version 1.02 2014-03-06   -->
<!-- Banduk and some more     -->
<?php //load news
 //changes
 //0.8: Date format Quests
 //0.9: Players list corrected
 //1.0: Quest German no text possible
 //1.01: Quest Line feed
 //1.02: favicon.ico, line in players list away
 
include $_SERVER['DOCUMENT_ROOT'].'/shared/shared.php';

//get language
if (ereg('de', $_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
	$IsGerman = true;
} else {
	$IsGerman = false;
}
//set FootlineText
$footlinetext = '<p>'.( $IsGerman == true ? 'Freies MMORPG mit echtem Rollenspiel' : 'Free MMORPG with real role play' ).'<br/>	&copy;2014 &bull; www.illarion.org</p>'
//'<p>'.echo( $IsGerman == true ? 'Freies MMORPG mit echtem Rollenspiel' : 'Free MMORPG with real role play' ).'<br/>	&copy;2014 &bull; www.illarion.org</p>'
?>
<!DOCTYPE html> 
<!-- Begin Web Page code -->
<html> 
<head> 
	<title>Illarion Mobile Web Page</title> 
	
	<meta charset="UTF-8">
	<meta name="description" content="Illarion, a MMORPG with focus to real role play" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="apple-mobile-web-app-capable" content="yes" />

	<link href=<?php Page::getURL()?>"/shared/css/jquery.mobile.css" rel="stylesheet" type="text/css"/>
<!--	"http://code.jquery.com/mobile/1.2.1/jquery.mobile-1.2.1.min.css"-->
	<link rel="stylesheet" type="text/css" href=<?php Page::getURL()?>"/shared/css/mobile.css" />
	<link rel="SHORTCUT ICON" href="favicon.ico"/>
	<script src=<?php Page::getURL()?>"/shared/js/jquery.js"></script>
<!-- "http://code.jquery.com/jquery-1.8.3.min.js"-->
	<script src=<?php Page::getURL()?>"/shared/js/jquery.mobile.js"></script>
<!-- "http://code.jquery.com/mobile/1.2.1/jquery.mobile-1.2.1.min.js"-->
	
<!-- Define swipe -->
<!-- left ==> reload page -->
<!-- right ==> Start page -->
	<script>
		$('body').live("swipeleft", function(){
				$.mobile.changePage("#ReloadPage", {transition: 'pop'});
		});
		$('body').live("swiperight", function(){
			var prevpage = $("#News").prev('div[data-role="page"]');
			$.mobile.changePage(prevpage, {transition: 'slide', reverse: true}, true);
		});
	</script>
</head> 

<body> 
<!-- Start of first page -->
<div data-role="page" id="Start" data-theme="a"> 
	<div data-role="header"  class="logo" data-position="fixed">
		<img src="<?php echo Page::getImageURL() ?>/illa_logo.png" alt="Illarion" width="200px" height="91px"/>

	</div>
	<div data-role="content" class="mainmenue">
		<p class="dateinfo"><?php echo IllaDateTime::IllaTimestampToTime( 'd. F Y' ); ?></p>
		<p><a href="#News" data-role="button" class="mainbutton" data-transition=slide>News</a></p>
		<?php Page::PlayerCount() ?>
		<?php if (Page::getServerStatus() == 1): ?>
			<p data-role="button" class="mainbutton">Server offline</p>
		<?php elseif (Page::getPlayerCount() == 0): ?>
			<p data-role="button" class="mainbutton">Nobody online</p>
		<?php else: ?>
			<p><a href="#Online" data-role="button" class="mainbutton" data-transition=slide><?php echo Page::getPlayerCount() ?> Chars online</a></p>
		<?php endif; ?>
		<?php
		//prepare quest list to count if quest available
		$pgSQL =& Database::getPostgreSQL();

		$query = 'SELECT q_title_de, q_title_us, q_status, q_id, q_type, q_content_de, q_content_us, q_starttime'
			.PHP_EOL.' FROM homepage.quests'
			.PHP_EOL.' WHERE q_status != 3'
			.PHP_EOL.' ORDER BY q_starttime ASC'
			;
			
			$pgSQL->setQuery( $query );
			$quests = $pgSQL->loadAssocList();
			if ( count($quests) == 0):
		?>
		<p data-role="button" class="mainbutton">No quests</p>
		<?php else: ?>
		<p><a href="#Quests" data-role="button" class="mainbutton" data-transition=slide><?php echo count($quests) ?> Quests</a></p>
		<?php endif; ?>
	</div> 
	<div data-role="footer" data-position="fixed">
		<?php echo $footlinetext ?>
	</div> 
</div> 

<!-- Start of news page -->
<div data-role="page" id="News" data-theme="a"> 
	<div data-role="header">
		 <a href="#" data-rel=back data-icon=home data-direction=reverse data-transition=slide>Home</a>
		 <p>News</p>
		<?php echo ( $IsGerman == true ? '<a href="#NewsHistory" data-icon=arrow-r data-transition=flip>Mehr</a>' : '<a href="#NewsHistory" data-icon=arrow-r data-transition=flip>More</a>' ) ?>	
		 
	</div> 
	<div data-role="content" class="newspage">
		<div data-role="content" class="newstext">
		<?php
			$newsRenderer = new \News\Renderer\MobileRenderer(false);
			$newsDb = new \News\NewsDatabase(false);
			if ($IsGerman == true):
				echo $newsRenderer->renderList($newsDb->getNewsList(1), 'de');
			else:
				echo $newsRenderer->renderList($newsDb->getNewsList(1), 'en');
			endif;
		?>
		</div>
	</div> 
	<div data-role="footer" data-position="fixed">
		<?php echo $footlinetext ?>
	</div> 
</div> 

<!-- Start of news history page -->
<div data-role="page" id="NewsHistory" data-theme="a"> 
	<div data-role="header">
		<a href="#" data-rel=back data-icon=arrow-l data-direction=reverse data-transition=flip>News</a>
		<?php echo ( $IsGerman == true ? '<p>Weitere News</p>' : '<p>News History</p>' ) ?>	
	</div> 
	<div data-role="content" class="newspage">
 		<div data-role="collapsible-set">
		<?php
			$newsRenderer = new \News\Renderer\MobileRenderer(true);
			$newsDb = new \News\NewsDatabase(false);
			if ($IsGerman == true):
				echo $newsRenderer->renderList($newsDb->getNewsList(5), 'de');
			else:
				echo $newsRenderer->renderList($newsDb->getNewsList(5), 'en');
			endif;
		?>
         </div>
	</div> 
	<div data-role="footer" data-position="fixed">
		<?php echo $footlinetext ?>
	</div> 
</div> 

<!-- Start of players page -->
<div data-role="page" id="Online" data-theme="a"> 
	<div data-role="header">
		<a href="#" data-rel=back data-icon=home data-direction=reverse data-transition=slide>Home</a>
		<?php echo ( $IsGerman == true ? '<p>Derzeit online</p>' : '<p>Currently online</p>' ) ?>		
	</div> 
	<div data-role="content" class="normpage">
		<ul data-role="listview" class="playerlist">
		<!-- Built player list -->
		<?php

			$pgSQL =& Database::getPostgreSQL();

			$query = 'SELECT "illarionserver"."chars"."chr_name" AS "chr_name",'
			.PHP_EOL.'       "illarionserver"."chars"."chr_playerid" AS "chr_playerid",'
			.PHP_EOL.'       (NOW() - interval \'1 months\') < "accounts"."account"."acc_registerdate" AS "newbie",'
			.PHP_EOL.'       ("illarionserver"."player"."ply_posz" >= 100 AND "illarionserver"."player"."ply_posz" <= 103) AS "newbieisland",'
			.PHP_EOL.'       ("illarionserver"."gms"."gm_rights_server" IS NOT NULL AND ("illarionserver"."gms"."gm_rights_server" & 131072) = 0) AS "gm",'
			.PHP_EOL.'       CASE WHEN "illarionserver"."questprogress"."qpg_progress" IS NULL OR "illarionserver"."questprogress"."qpg_progress" = 0 THEN \'4\' ELSE "illarionserver"."questprogress"."qpg_progress" END AS "town",'
			.PHP_EOL.'       ("homepage"."character_details"."settings" IS NOT NULL AND ("homepage"."character_details"."settings" & 1) > 0) AS "show_profile"'
			.PHP_EOL.' FROM "illarionserver"."onlineplayer"'
			.PHP_EOL.' INNER JOIN "illarionserver"."chars" ON "illarionserver"."onlineplayer"."on_playerid" = "illarionserver"."chars"."chr_playerid"'
			.PHP_EOL.' INNER JOIN "illarionserver"."player" ON "illarionserver"."onlineplayer"."on_playerid" = "illarionserver"."player"."ply_playerid"'
			.PHP_EOL.' INNER JOIN "accounts"."account" ON "illarionserver"."chars"."chr_accid" = "accounts"."account"."acc_id"'
			.PHP_EOL.' LEFT JOIN "illarionserver"."questprogress" ON "illarionserver"."onlineplayer"."on_playerid" = "illarionserver"."questprogress"."qpg_userid" AND "illarionserver"."questprogress"."qpg_questid" = 199'
			.PHP_EOL.' LEFT JOIN "illarionserver"."gms" ON "illarionserver"."onlineplayer"."on_playerid" = "illarionserver"."gms"."gm_charid"'
			.PHP_EOL.' LEFT JOIN "homepage"."character_details" ON "illarionserver"."onlineplayer"."on_playerid" = "homepage"."character_details"."char_id"'
			.PHP_EOL.' WHERE TRUE'
			.PHP_EOL.' ORDER BY "town", "chr_name"'
			;
			
			$pgSQL->setQuery( $query );
			$list = $pgSQL->loadAssocList();

			$hidden_chars = 0;
			$newbies = 0;
			
			if( count($list) > 0 )
			{
				$content = '';
				$current_town = -1;
			
				foreach($list as $key=>$char)
				{
					if (($char["gm"] =='t'))
					{
						++$hidden_chars;
						continue;
					}
					
					if (($char["newbie"] =='t'))
					{
						++$newbies;
						continue;
					}

					if ($char['town'] != $current_town)
					{

						$current_town = $char['town'];
						switch($current_town)
						{
							//<h3><img src="images/galmair.png" width="32" />  Galmair</h3>
							case 1: $content .= '<h3><img src="'.Page::getMediaURL().'/cadomyr.png" width="32" /> Cadomyr</h3>'; break;
							case 2: $content .= '<h3><img src="'.Page::getMediaURL().'/runewick.png" width="32" /> Runewick</h3>'; break;
							case 3: $content .= '<h3><img src="'.Page::getMediaURL().'/galmair.png" width="32" /> Galmair</h3>'; break;
							case 4: $IsGerman == true ? $content .= '<h3> Vogelfrei</h3>' : $content .= '<h3> Outlawed</h3>'; break;
							default: $content .= '<h3> Unknown</h3>';
						}
					}
					
					$content .= '<li>'.$char['chr_name'].'</li>';
				}
				if ($hidden_chars == 1)
				{
					$IsGerman == true ? $content .= '<h3>Ein nicht sichtbarer Spieler</h3>' : $content .= '<h3>One hidden char</h3>';
				}
				if ($hidden_chars > 1)
				{
					$IsGerman == true ? $content .= '<h3>'.$hidden_chars.' nicht sichtbare Spieler</h3>' : $content .= '<h3>'.$hidden_chars.' hidden chars</h3>';
				}
				if ($newbies == 1)
				{
					$IsGerman == true ? $content .= '<h3>Ein neuer Spieler</h3>' : $content .= '<h3>One new char</h3>';
				}
				if ($newbies > 1)
				{
					$IsGerman == true ? $content .= '<h3>'.$newbies.' neue Spieler</h3>' : $content .= '<h3>'.$newbies.' new chars</h3>';
				}
			}	
			echo $content;
		?>
		</ul>
	</div> 
	<div data-role="footer" data-position="fixed">
		<?php echo $footlinetext ?>
	</div> 
</div> 

<!-- Start of Quest page -->
<div data-role="page" id="Quests" data-theme="a"> 
	<div data-role="header">
		<a href="#" data-rel=back data-icon=home data-direction=reverse data-transition=slide>Home</a>
		<p>Quests</p>
	</div>
	<div data-role="content" class="normpage">
		<?php
			if ( count($quests) > 0):
		?>
		<div data-role="collapsible-set">
<!--		<div data-role="collapsible" class="questnow-collapsible" data-collapsed="false">
				<h4>12. Mai 20:00<br/>Quest 1</h4>
				<p>I'm the collapsible set content for Quest 1.</p>
			</div>
			<div data-role="collapsible" class="quest-collapsible">
				<h4>17. Mai 22:00<br/>Quest 3</h4>
				<p>I'm the collapsible set content for Quest 3.</p>
			</div> -->
			<?php 
			//top entry of the list not collapsed
			$topquest=true;
			
			foreach($quests as $key=>$quest):
				if ($quest['q_status'] != 3):
					if ($quest['q_type'] == 2)
					{
						$quest['q_status'] = 3;
					}
					if (!is_null( $quest['q_starttime'] ))
					{
						$quest['q_starttime'] = strtotime( $quest['q_starttime'] );
					}
					if ($topquest == true):
						$topquest = false;
						switch($quest['q_status'])
						{
							case 0:	echo '<div data-role="collapsible" class="quest-collapsible" data-collapsed="false">'; break;
							case 1:	echo '<div data-role="collapsible" class="questnow-collapsible" data-collapsed="false">'; break;
							case 2:	echo '<div data-role="collapsible" class="questnow-collapsible" data-collapsed="false">'; break;
							default: echo '<div data-role="collapsible" class="quest-collapsible" data-collapsed="false">'; 
						}
					else:
						switch($quest['q_status'])
						{
							case 0:	echo '<div data-role="collapsible" class="quest-collapsible">'; break;
							case 1:	echo '<div data-role="collapsible" class="questnow-collapsible">'; break;
							case 2:	echo '<div data-role="collapsible" class="questnow-collapsible">'; break;
							default: echo '<div data-role="collapsible" class="quest-collapsible">'; 
						}
					endif;
				?>
					<h4>
						<?php echo(is_null ($quest['q_starttime']) ? 'open' : strftime( '%d.%m.%Y  %H:%M:%S', IllaDateTime::TimestampWithOffset($quest['q_starttime']))); ?>
						<br/>
						<?php echo(( $IsGerman == true and empty($quest['q_title_de']) == false) ? $quest['q_title_de'] : $quest['q_title_us'] ); ?>
					</h4>
					<p>
						<?php
						if ($IsGerman == true):
							echo ( $quest['q_type'] == 1 ? 'Offizieller Quest' : 'Spielerquest');
						else:
							echo ( $quest['q_type'] == 1 ? 'Official quest' : 'Player quest');
						endif;
						?>
						<br/>
						<?php echo (preg_replace('/(\n\r)|(\r\n)|(\n|\r)/', '<br />',( $IsGerman == true and empty($quest['q_content_de']) == false) ? $quest['q_content_de'] : $quest['q_content_us'] )); ?>
					</p>
					</div>
				<?php
				endif;
			endforeach;
			?>
		</div> 
		<?php
			endif;
		?>
	</div> 
	<div data-role="footer" data-position="fixed">
		<?php echo $footlinetext ?>
	</div> 
</div> 


<!-- Reload page ? -->
<div data-role="dialog" id="ReloadPage" data-theme="a"> 
		<button onClick="window.location.reload()">Reload data</button>
		<a href="#Start"><button>Home</button></a>
</div> 

</body>
</html>
