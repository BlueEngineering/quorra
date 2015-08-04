<div class="page-header">
	<h1>Hallo <?php echo ( !empty( $user["realname"] ) ) ? $user["realname"] : $user["username"]; ?></h1>
</div>
<div>
	<pre>
	<?php
	print_r( $user );
	print_r( $currMwUserinfos );
	?>
	</pre>
	<?= h( $user->id ); ?>
</div>