<?php
/**
 * Server-side rendering of the `core/block` block.
 */

/**
 * Renders the `core/block` block on server.
 *
 * @param  array  $attributes The block attributes.
 * @return string Rendered HTML of the referenced block.
 */
function render_block_core_block($attributes)
{
    static $seen_refs = [];

    if (empty($attributes['ref'])) {
        return '';
    }

    $reusable_block = get_post($attributes['ref']);
    if (!$reusable_block || $reusable_block->post_type !== 'wp_block') {
        return '';
    }

    if (isset($seen_refs[$attributes['ref']])) {
        // WP_DEBUG_DISPLAY must only be honored when WP_DEBUG. This precedent
        // is set in `wp_debug_mode()`.
        $is_debug = WP_DEBUG && WP_DEBUG_DISPLAY;

        return $is_debug ?
            // translators: Visible only in the front end, this warning takes the place of a faulty block.
            __('[block rendering halted]') :
            '';
    }

    if ($reusable_block->post_status !== 'publish' || !empty($reusable_block->post_password)) {
        return '';
    }

    $seen_refs[$attributes['ref']] = true;

    // Handle embeds for reusable blocks.
    global $wp_embed;
    $content = $wp_embed->run_shortcode($reusable_block->post_content);
    $content = $wp_embed->autoembed($content);

    $content = do_blocks($content);
    unset($seen_refs[$attributes['ref']]);

    return $content;
}

/**
 * Registers the `core/block` block.
 */
function register_block_core_block()
{
    register_block_type_from_metadata(
        __DIR__ . '/block',
        [
            'render_callback' => 'render_block_core_block',
        ]
    );
}
add_action('init', 'register_block_core_block');
