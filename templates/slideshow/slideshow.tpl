{IF("{VAR:is_layoutmode}" != "1" )}
	<ul class="slideshow" data-flickity='{ "autoPlay": 8000, "cellAlign": "center", "contain": true }'>
		{LOOP VAR(slideshow_images)}
			<li class="slideshow__slide">
				<img class="slideshow__image" src="/media/gallery/{VAR:gallery_image_internal_filename}" alt="" title="" />
			</li>
		{ENDLOOP VAR}
	</ul>
{ELSE}
	<pre>
		Bilderwechsler
		==============

		Die Bilder können im Modul "Gallerie" verwaltet werden
	</pre>
{ENDIF}
