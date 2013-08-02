/**********************************
 * PHP Game Framework             *
 * Published under MIT License    *
 * Copyright (c) 2013 Léo Maradan *
 **********************************/
 
define(['js!lib/sha'], function() {
	
	$( ".form_class" ).on("submit", function() {
		$(".jscrypt").each(function() {
			var hash,
				input = $(this).data("source"),
				pass= $("#"+input).val(),
				salt = $(this).data("salt");

			hash = Sha1.hash(salt + pass);

			$("#"+input).val("");
			$(this).val(hash);

		});
	});
});
