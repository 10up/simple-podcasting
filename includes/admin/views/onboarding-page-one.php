<?php
/**
 * Body template for onboarding: Step - 1.
 *
 * @package tenup_podcasting
 */

?>
<div class="simple-podcasting__onboarding-body simple-podcasting__onboarding-body--step-1">
	<div id="simple-podcasting__page-title">
		<?php esc_html_e( 'Create your first podcast show', 'simple-podcasting' ); ?>
	</div>

	<!-- Show name -->
	<div class="simple-podcasting__setting">
		<label class="simple-podcasting__setting-label"><?php esc_html_e( 'Show name', 'simple-podcasting' ); ?></label>
		<div class="simple-podcasting__setting-input"><input name="podcast-name" type="text" /></div>
		<div class="simple-podcasting__setting-description"><?php esc_html_e( 'What’s the title of your podcast show that listeners will see?', 'simple-podcasting' ); ?></div>
	</div>

	<!-- Show description -->
	<div class="simple-podcasting__setting">
		<label class="simple-podcasting__setting-label"><?php esc_html_e( 'Show description', 'simple-podcasting' ); ?></label>
		<div class="simple-podcasting__setting-input"><textarea name="podcast-description" rows="5"></textarea></div>
		<div class="simple-podcasting__setting-description"><?php esc_html_e( 'Briefly describe to your listeners what your podcast is about. (No HTML please)', 'simple-podcasting' ); ?></div>
	</div>

	<!-- Cover image -->
	<div class="simple-podcasting__setting">
		<label class="simple-podcasting__setting-label"><?php esc_html_e( 'Cover image', 'simple-podcasting' ); ?></label>
		<button class="simple-podcasting__btn simple-podcasting__btn--ghost" id="simple-podcasting__upload-cover-image"><?php esc_html_e( 'Select image', 'simple-podcasting' ); ?></button>
		<div class="simple-podcasting__setting-description"><?php esc_html_e( 'Minimum size: 1400px x 1400 px — maximum size: 2048px x 2048px. Make sure the image is square so it will properly display within podcatcher apps.', 'simple-podcasting' ); ?></div>
	</div>

	<!-- Podcast category -->
	<div class="simple-podcasting__setting">
		<label class="simple-podcasting__setting-label"><?php esc_html_e( 'Podcast category', 'simple-podcasting' ); ?></label>
		<select name="podcast-category">
			<option value=""><?php esc_html_e( 'None', 'simple-podcasting' ); ?></option>
		</select>
		<div class="simple-podcasting__setting-description"><?php esc_html_e( 'What’s the category the listeners will use to discover your podcast under when browsing  podcatchers?', 'simple-podcasting' ); ?></div>
	</div>

	<!-- Create button -->
	<button class="simple-podcasting__btn simple-podcasting__btn--black" id="simple-podcasting__create-podcast-button"><?php esc_html_e( 'Create', 'simple-podcasting' ); ?></button>
</div>
