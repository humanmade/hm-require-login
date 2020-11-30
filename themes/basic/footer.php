<?php
// phpcs:ignorefile
?>
	<p id="backtoblog"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">
	<?php

	/* translators: %s: Site title. */
	printf( _x( '&larr; Back to %s', 'site' ), get_bloginfo( 'title', 'display' ) );

	?>
	</a></p>
	<?php

	the_privacy_policy_link( '<div class="privacy-policy-page-link">', '</div>' );

?>
</div><?php // End of <div id="login"> ?>

<?php

/**
 * Fires in the login page footer.
 *
 * @since 3.1.0
 */
do_action( 'login_footer' );

?>
<div class="clear"></div>
</body>
</html>

<?php
