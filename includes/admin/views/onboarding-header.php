<?php
/**
 * Header template for onboarding: Step - 1.
 *
 * @package tenup_podcasting
 */

?>
<div id="simple-podcasting__onboarding-header">
	<div id="simple-podcasting__branding">
		<div id="simple-podcasting__logo">
			<img src="https://ps.w.org/simple-podcasting/assets/icon-128x128.png" />
		</div>
		<div id="simple-podcasting__plugin-name">
			<?php esc_html_e( 'Simple Podcasting', 'simple-podcasting' ); ?>
		</div>
	</div>

	<div id="simple-podcasting__header-title">
		<?php esc_html_e( 'Get Started With Podcasting', 'simple-podcasting' ); ?>
	</div>

	<div id="simple-podcasting__header-controls">
		<a href="<?php echo esc_url( admin_url( 'plugins.php' ) ); ?>" class="simple-podcasting__btn simple-podcasting__btn--ghost" id="simple-podcasting-btn__skip-onboarding"><?php esc_html_e( 'Skip Setup', 'simple-podcasting' ); ?></a>
	</div>
</div>
