{IF(!{LAYOUTMODE})}
<ul class="references">
	{LOOP VAR(references)}
	<li class="reference">
		<figure class="reference__logo">
			<img src="/media/gallery/{VAR:gallery_image_internal_filename}" alt="{VAR:gallery_image_title}" />
			<figcaption class="reference__name">{VAR:gallery_image_title}</figcaption>
		</figure>
	</li>
	{ENDLOOP VAR}
</ul>
{ELSE}
<mark>Referenzen</mark>
{ENDIF}
