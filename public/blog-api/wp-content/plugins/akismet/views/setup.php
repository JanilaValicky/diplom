<div class="akismet-setup-instructions">
	<p><?php esc_html_e('Set up your Akismet account to enable spam filtering on this site.', 'akismet'); ?></p>
	<?php Akismet::view('get', ['text' => __('Set up your Akismet account', 'akismet'), 'classes' => ['akismet-button', 'akismet-is-primary']]); ?>
</div>
