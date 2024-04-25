<!doctype html>
<html lang="{PAGELANG}"> 
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title>{PAGETITLE}</title>
		<meta name="description" content="{PAGEVAR:cmt_meta_description:recursive}">
		<meta name="keywords" content="{PAGEVAR:cmt_meta_keywords:recursive">
	 	<meta name="viewport" content="width=device-width, initial-scale=1">

		<link rel="stylesheet" href="/css/main.css">
		<!-- <link rel="stylesheet" href="/css/vendor/leaflet.css"> -->
		<link rel="stylesheet" href="/css/vendor/mapbox-gl.css">

		{LAYOUTMODE_STARTSCRIPT}
		{IF (!{LAYOUTMODE})}
			<script src="/js/vendor/jquery.min.js"></script>
			<script src="/js/vendor/flickity.pkgd.min.js"></script>
			<!-- <script src="/js/vendor/leaflet.js"></script> -->
			<script src="/js/vendor/mapbox-gl.js"></script>
		{ENDIF}
	</head>
	<body id="top">
		<header class="main-header">
			<div class="inner-bound">
				<a href="{PAGEURL:2}#top"><img class="logo" src="/img/logo.svg" alt="" /></a>
				<nav class="main-nav">
					<ul>
						<li><a href="{PAGEURL:2}#portfolio">Leistungen</a></li>
						<li><a href="{PAGEURL:2}#foerderprogramme">Förderprogramme</a></li>
						<!-- <li><a href="{PAGEURL:2}#referenzen">Referenzen</a></li> -->
						<li><a href="{PAGEURL:2}#contact">Kontakt</a></li>
					</ul>
				</nav>
				<nav class="main-nav--mobile">
					<select id="js-mobile-nav">
						<option value="">Menü</option>
						<option value="welcome">Start</option>
						<option value="portfolio">Leistungen</option>
						<option value="foerderprogramme">Förderprogramme</option>
						<!-- <option value="referenzen">Referenzen</option> -->
						<option value="contact">Kontakt</option>
						<option value="map">Anfahrt</option>
					</select>
				</nav>
			</div>
		</header>
			{INCLUDE:PATHTOWEBROOT."phpincludes/slideshow/SlideshowController.php"}
		
		<section id="welcome" class="section">
			<div class="main-content">
				{IF({LAYOUTMODE})}<pre>Einstieg</pre>{ENDIF}
				{LOOP CONTENT(1)}{ENDLOOP CONTENT}
			</div>
		</section>
		<section id="portfolio" class="section">
			<div class="main-content">
				{IF({LAYOUTMODE})}<pre>Portfolio</pre>{ENDIF}
				{LOOP CONTENT(2)}{ENDLOOP CONTENT}
			</div>
		</section>
		<section id="foerderprogramme" class="section">
			<div class="main-content">
				{IF({LAYOUTMODE})}<pre>Förderprogramme</pre>{ENDIF}
				{LOOP CONTENT(8)}{ENDLOOP CONTENT}
			</div>
		</section>
		<section id="contact" class="section">
			<div class="main-content">
				{IF({LAYOUTMODE})}<pre>Kontaktformular</pre>{ENDIF}
				{LOOP CONTENT(3)}{ENDLOOP CONTENT}
			</div>
		</section>
		<section id="map" class="section">
			{IF({LAYOUTMODE})}<div class="main-content"><pre>Kartenansicht</pre></div>{ENDIF}
			{LOOP CONTENT(4)}{ENDLOOP CONTENT}
		</section>

		<footer class="main-footer">
			<div class="inner">
				<div class="main-footer__column">
					{LOOP CONTENT(5)}{ENDLOOP CONTENT}
				</div>
				<div class="main-footer__column">
					{LOOP CONTENT(6)}{ENDLOOP CONTENT}
				</div>
				<div class="main-footer__column">
					{LOOP CONTENT(7)}{ENDLOOP CONTENT}
				</div>
			</div>
		</footer>

		<!-- Include GA -->

		{IF(!{LAYOUTMODE})}
			<script src="/js/main.min.js"></script>
		{ENDIF}
		{LAYOUTMODE_ENDSCRIPT}
	</body>
</html>
