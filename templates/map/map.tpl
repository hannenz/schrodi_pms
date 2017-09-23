{IF("{VAR:is_layoutmode}" != "1" )}
<div id="js-map"
	data-lat="{VAR:lat}"
	data-lon="{VAR:lon}"
	{IF({ISSET:mapHeight:VAR})} data-height="{VAR:mapHeight}" {ENDIF}
	{IF({ISSET:popupHTML:VAR})} data-popup-html="{VAR:popupHTML}" {ENDIF}
	>
</div>
{ELSE}
	<pre>
	Kartendarstellung [OpenStreetMap]
	=================================

	Konfiguration
	-------------
	Tragen Sie unter "Optionale Variablen" ein:
	Variable 1: Breitengrad (Latitude)
	Variable 2: Längengrad (Longitude)
	Variable 3: Höhe der Karte als CSS-Längenangabe, z. Bsp. "380px" (optional)
	</pre>
{ENDIF}

