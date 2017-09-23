<div class="container contact">
	<div class="main-column">

		{IF({ISSET:success})}
			<p class="message message--positive success-message">
				<strong>Vielen Dank für Ihre Nachricht</strong>
				<br>
				Wir melden uns in Kürze bei Ihnen.
			</p>
		{ELSE}
			<form id="contactform" class="contact-form two-columns two-columns-6-6" action="?" method="post" >
				<div class="form-field form-field--radio">
					<input {IF("{VAR:salutation}" != "Frau")}checked{ENDIF} name="salutation" value="Herr" class="form-field__radio" type="radio" id="salutation-mr">
					<label class="form-field__label" for="salutation-mr">Herr</label>
					<strong> / </strong>
					<input {IF("{VAR:salutation}" == "Frau")}checked{ENDIF} name="salutation" value="Frau" class="form-field__radio" type="radio" id="salutation-ms">
					<label class="form-field__label" for="salutation-ms">Frau</label>
				</div>
				<div class="form-field form-field--text">
					<label class="form-field__label">Firma</label>
					<input name="company" class="form-field__input form-field--text" type="text"  value="{VAR:company}" />
					{IF({ISSET:validation-error-company})}
						<span class="message message--negative validation-error">Bitte füllen Sie dieses Feld aus</span>
					{ENDIF}
				</div>
				<div class="row">
					<div class="form-field form-field--text">
						<label class="form-field__label">Vorname</label>
						<input name="firstname" class="form-field__input form-field--text" type="text"  value="{VAR:firstname}" />
						{IF({ISSET:validation-error-firstname})}
							<span class="message message--negative validation-error">Bitte füllen Sie dieses Feld aus</span>
						{ENDIF}
					</div>
					<div class="form-field form-field--text">
						<label class="form-field__label">Nachname (*)</label>
						<input name="lastname" class="form-field__input form-field--text" type="text"  value="{VAR:lastname}" />
						{IF({ISSET:validation-error-lastname})}
							<span class="message message--negative validation-error">Bitte füllen Sie dieses Feld aus</span>
						{ENDIF}
					</div>
				</div>
				<div class="row">
					<div class="form-field form-field--email">
						<label class="form-field__label">E-Mail-Adresse (*)</label>
						<input name="email" class="form-field__input form-field--email" type="text"  value="{VAR:email}" />
						{IF({ISSET:validation-error-email})}
							<span class="message message--negative validation-error">Bitte geben Sie eine gültige E-Mail-Adresse ein</span>
						{ENDIF}
					</div>
					<div class="form-field form-field--phone">
						<label class="form-field__label">Telefon</label>
						<input name="phone" class="form-field__input form-field--text" type="text"  value="{VAR:phone}" />
						{IF({ISSET:validation-error-phone})}
							<span class="message message--negative validation-error">Bitte füllen Sie dieses Feld aus</span>
						{ENDIF}
					</div>
				</div>
				<div class="form-field form-field--textarea">
					<label class="form-field__label">Hinterlassen Sie eine Nachricht (*)</label>
					<textarea name="message" class="form-field__textarea">{VAR:message}</textarea>
					{IF({ISSET:validation-error-message})}
						<span class="message message--negative validation-error">Bitte geben Sie eine Nachricht ein </span>
					{ENDIF}
				</div>
				<div class="form-field form-field--submit">
					<button type="submit">Nachricht senden</button>
				</div>
			</form>
		{ENDIF}
	</div>
	<aside class="sidebar">
		<img src="/img/noun_1033609_cc.svg" alt="" />
		<!-- <h3 class="sidebar__headline"></h3> -->
		<p><em>Einfach das Kontaktformular mit ihrer Anfrage ausfüllen und wir melden uns umgehend zurück &mdash; per E-Mail oder Telefon</em></p>
	</aside>
</div>
