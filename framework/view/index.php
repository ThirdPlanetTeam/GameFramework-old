<?php

/**********************************
 * PHP Game Framework             *
 * Published under MIT License    *
 * Copyright (c) 2013 Léo Maradan *
 **********************************/

?>
		<header>
			<h1>Framework</h1>
		</header>
	
		<section id="main" role="main">
			<?php echo $content; ?>
				
		</section>

		<?php
			if($error != null) {
				echo '<div class="alert alert-danger">' . $error . '</div>';
			}
		?>

		<footer>

		</footer>