<div class="page-header">
	<h1>Hallo <?php echo ( !empty( $user["realname"] ) ) ? $user["realname"] : $user["username"]; ?>, willkommen auf deinem Dashboard.</h1>
</div>
<div>
	<pre>
	<?php
	print_r( $user );
	?>
	</pre>
</div>