<?php
/**
 * Functions related to registering and parsing blocks.
 *
 * @since 5.0.0
 */

/**
 * Removes the block asset's path prefix if provided.
 *
 * @since 5.5.0
 *
 * @param  string  $asset_handle_or_path Asset handle or prefixed path.
 * @return string Path without the prefix or the original value.
 */
function remove_block_asset_path_prefix($asset_handle_or_path)
{
    $path_prefix = 'file:';
    if (!str_starts_with($asset_handle_or_path, $path_prefix)) {
        return $asset_handle_or_path;
    }
    $path = substr(
        $asset_handle_or_path,
        strlen($path_prefix)
    );
    if (str_starts_with($path, './')) {
        $path = substr($path, 2);
    }

    return $path;
}

/**
 * Generates the name for an asset based on the name of the block
 * and the field name provided.
 *
 * @since 5.5.0
 * @since 6.1.0 Added `$index` parameter.
 *
 * @param  string  $block_name Name of the block.
 * @param  string  $field_name Name of the metadata field.
 * @param  int  $index      Optional. Index of the asset when multiple items passed.
 *                           Default 0.
 * @return string Generated asset name for the block's field.
 */
function generate_block_asset_handle($block_name, $field_name, $index = 0)
{
    if (str_starts_with($block_name, 'core/')) {
        $asset_handle = str_replace('core/', 'wp-block-', $block_name);
        if (str_starts_with($field_name, 'editor')) {
            $asset_handle .= '-editor';
        }
        if (str_starts_with($field_name, 'view')) {
            $asset_handle .= '-view';
        }
        if ($index > 0) {
            $asset_handle .= '-' . ($index + 1);
        }

        return $asset_handle;
    }

    $field_mappings = [
        'editorScript' => 'editor-script',
        'script' => 'script',
        'viewScript' => 'view-script',
        'editorStyle' => 'editor-style',
        'style' => 'style',
    ];
    $asset_handle = str_replace('/', '-', $block_name) .
        '-' . $field_mappings[$field_name];
    if ($index > 0) {
        $asset_handle .= '-' . ($index + 1);
    }

    return $asset_handle;
}

/**
 * Gets the URL to a block asset.
 *
 * @since 6.4.0
 *
 * @param  string  $path A normalized path to a block asset.
 * @return string|false The URL to the block asset or false on failure.
 */
function get_block_asset_url($path)
{
    if (empty($path)) {
        return false;
    }

    // Path needs to be normalized to work in Windows env.
    static $wpinc_path_norm = '';
    if (!$wpinc_path_norm) {
        $wpinc_path_norm = wp_normalize_path(realpath(ABSPATH . WPINC));
    }

    if (str_starts_with($path, $wpinc_path_norm)) {
        return includes_url(str_replace($wpinc_path_norm, '', $path));
    }

    static $template_paths_norm = [];

    $template = get_template();
    if (!isset($template_paths_norm[$template])) {
        $template_paths_norm[$template] = wp_normalize_path(get_template_directory());
    }

    if (str_starts_with($path, trailingslashit($template_paths_norm[$template]))) {
        return get_theme_file_uri(str_replace($template_paths_norm[$template], '', $path));
    }

    if (is_child_theme()) {
        $stylesheet = get_stylesheet();
        if (!isset($template_paths_norm[$stylesheet])) {
            $template_paths_norm[$stylesheet] = wp_normalize_path(get_stylesheet_directory());
        }

        if (str_starts_with($path, trailingslashit($template_paths_norm[$stylesheet]))) {
            return get_theme_file_uri(str_replace($template_paths_norm[$stylesheet], '', $path));
        }
    }

    return plugins_url(basename($path), $path);
}

/**
 * Finds a script handle for the selected block metadata field. It detects
 * when a path to file was provided and finds a corresponding asset file
 * with details necessary to register the script under automatically
 * generated handle name. It returns unprocessed script handle otherwise.
 *
 * @since 5.5.0
 * @since 6.1.0 Added `$index` parameter.
 *
 * @param  array  $metadata   Block metadata.
 * @param  string  $field_name Field name to pick from metadata.
 * @param  int  $index      Optional. Index of the script to register when multiple items passed.
 *                           Default 0.
 * @return string|false Script handle provided directly or created through
 *                      script's registration, or false on failure.
 */
function register_block_script_handle($metadata, $field_name, $index = 0)
{
    if (empty($metadata[$field_name])) {
        return false;
    }

    $script_handle = $metadata[$field_name];
    if (is_array($script_handle)) {
        if (empty($script_handle[$index])) {
            return false;
        }
        $script_handle = $script_handle[$index];
    }

    $script_path = remove_block_asset_path_prefix($script_handle);
    if ($script_handle === $script_path) {
        return $script_handle;
    }

    $path = dirname($metadata['file']);
    $script_asset_raw_path = $path . '/' . substr_replace($script_path, '.asset.php', -strlen('.js'));
    $script_handle = generate_block_asset_handle($metadata['name'], $field_name, $index);
    $script_asset_path = wp_normalize_path(
        realpath($script_asset_raw_path)
    );

    if (empty($script_asset_path)) {
        _doing_it_wrong(
            __FUNCTION__,
            sprintf(
                /* translators: 1: Asset file location, 2: Field name, 3: Block name.  */
                __('The asset file (%1$s) for the "%2$s" defined in "%3$s" block definition is missing.'),
                $script_asset_raw_path,
                $field_name,
                $metadata['name']
            ),
            '5.5.0'
        );

        return false;
    }

    $script_path_norm = wp_normalize_path(realpath($path . '/' . $script_path));
    $script_uri = get_block_asset_url($script_path_norm);

    $script_args = [];
    if ($field_name === 'viewScript' && $script_uri) {
        $script_args['strategy'] = 'defer';
    }

    $script_asset = require $script_asset_path;
    $script_dependencies = isset($script_asset['dependencies']) ? $script_asset['dependencies'] : [];
    $result = wp_register_script(
        $script_handle,
        $script_uri,
        $script_dependencies,
        isset($script_asset['version']) ? $script_asset['version'] : false,
        $script_args
    );
    if (!$result) {
        return false;
    }

    if (!empty($metadata['textdomain']) && in_array('wp-i18n', $script_dependencies, true)) {
        wp_set_script_translations($script_handle, $metadata['textdomain']);
    }

    return $script_handle;
}

/**
 * Finds a style handle for the block metadata field. It detects when a path
 * to file was provided and registers the style under automatically
 * generated handle name. It returns unprocessed style handle otherwise.
 *
 * @since 5.5.0
 * @since 6.1.0 Added `$index` parameter.
 *
 * @param  array  $metadata   Block metadata.
 * @param  string  $field_name Field name to pick from metadata.
 * @param  int  $index      Optional. Index of the style to register when multiple items passed.
 *                           Default 0.
 * @return string|false Style handle provided directly or created through
 *                      style's registration, or false on failure.
 */
function register_block_style_handle($metadata, $field_name, $index = 0)
{
    if (empty($metadata[$field_name])) {
        return false;
    }

    $style_handle = $metadata[$field_name];
    if (is_array($style_handle)) {
        if (empty($style_handle[$index])) {
            return false;
        }
        $style_handle = $style_handle[$index];
    }

    $style_handle_name = generate_block_asset_handle($metadata['name'], $field_name, $index);
    // If the style handle is already registered, skip re-registering.
    if (wp_style_is($style_handle_name, 'registered')) {
        return $style_handle_name;
    }

    static $wpinc_path_norm = '';
    if (!$wpinc_path_norm) {
        $wpinc_path_norm = wp_normalize_path(realpath(ABSPATH . WPINC));
    }

    $is_core_block = isset($metadata['file']) && str_starts_with($metadata['file'], $wpinc_path_norm);
    // Skip registering individual styles for each core block when a bundled version provided.
    if ($is_core_block && !wp_should_load_separate_core_block_assets()) {
        return false;
    }

    $style_path = remove_block_asset_path_prefix($style_handle);
    $is_style_handle = $style_handle === $style_path;
    // Allow only passing style handles for core blocks.
    if ($is_core_block && !$is_style_handle) {
        return false;
    }
    // Return the style handle unless it's the first item for every core block that requires special treatment.
    if ($is_style_handle && !($is_core_block && $index === 0)) {
        return $style_handle;
    }

    // Check whether styles should have a ".min" suffix or not.
    $suffix = SCRIPT_DEBUG ? '' : '.min';
    if ($is_core_block) {
        $style_path = ($field_name === 'editorStyle') ? "editor{$suffix}.css" : "style{$suffix}.css";
    }

    $style_path_norm = wp_normalize_path(realpath(dirname($metadata['file']) . '/' . $style_path));
    $style_uri = get_block_asset_url($style_path_norm);

    $version = !$is_core_block && isset($metadata['version']) ? $metadata['version'] : false;
    $result = wp_register_style(
        $style_handle_name,
        $style_uri,
        [],
        $version
    );
    if (!$result) {
        return false;
    }

    if ($style_uri) {
        wp_style_add_data($style_handle_name, 'path', $style_path_norm);

        if ($is_core_block) {
            $rtl_file = str_replace("{$suffix}.css", "-rtl{$suffix}.css", $style_path_norm);
        } else {
            $rtl_file = str_replace('.css', '-rtl.css', $style_path_norm);
        }

        if (is_rtl() && file_exists($rtl_file)) {
            wp_style_add_data($style_handle_name, 'rtl', 'replace');
            wp_style_add_data($style_handle_name, 'suffix', $suffix);
            wp_style_add_data($style_handle_name, 'path', $rtl_file);
        }
    }

    return $style_handle_name;
}

/**
 * Gets i18n schema for block's metadata read from `block.json` file.
 *
 * @since 5.9.0
 *
 * @return object The schema for block's metadata.
 */
function get_block_metadata_i18n_schema()
{
    static $i18n_block_schema;

    if (!isset($i18n_block_schema)) {
        $i18n_block_schema = wp_json_file_decode(__DIR__ . '/block-i18n.json');
    }

    return $i18n_block_schema;
}

/**
 * Registers a block type from the metadata stored in the `block.json` file.
 *
 * @since 5.5.0
 * @since 5.7.0 Added support for `textdomain` field and i18n handling for all translatable fields.
 * @since 5.9.0 Added support for `variations` and `viewScript` fields.
 * @since 6.1.0 Added support for `render` field.
 * @since 6.3.0 Added `selectors` field.
 * @since 6.4.0 Added support for `blockHooks` field.
 *
 * @param  string  $file_or_folder Path to the JSON file with metadata definition for
 *                               the block or path to the folder where the `block.json` file is located.
 *                               If providing the path to a JSON file, the filename must end with `block.json`.
 * @param  array  $args           Optional. Array of block type arguments. Accepts any public property
 *                               of `WP_Block_Type`. See WP_Block_Type::__construct() for information
 *                               on accepted arguments. Default empty array.
 * @return WP_Block_Type|false The registered block type on success, or false on failure.
 */
function register_block_type_from_metadata($file_or_folder, $args = [])
{
    /*
     * Get an array of metadata from a PHP file.
     * This improves performance for core blocks as it's only necessary to read a single PHP file
     * instead of reading a JSON file per-block, and then decoding from JSON to PHP.
     * Using a static variable ensures that the metadata is only read once per request.
     */
    static $core_blocks_meta;
    if (!$core_blocks_meta) {
        $core_blocks_meta = require ABSPATH . WPINC . '/blocks/blocks-json.php';
    }

    $metadata_file = (!str_ends_with($file_or_folder, 'block.json')) ?
        trailingslashit($file_or_folder) . 'block.json' :
        $file_or_folder;

    $is_core_block = str_starts_with($file_or_folder, ABSPATH . WPINC);

    if (!$is_core_block && !file_exists($metadata_file)) {
        return false;
    }

    // Try to get metadata from the static cache for core blocks.
    $metadata = false;
    if ($is_core_block) {
        $core_block_name = str_replace(ABSPATH . WPINC . '/blocks/', '', $file_or_folder);
        if (!empty($core_blocks_meta[$core_block_name])) {
            $metadata = $core_blocks_meta[$core_block_name];
        }
    }

    // If metadata is not found in the static cache, read it from the file.
    if (!$metadata) {
        $metadata = wp_json_file_decode($metadata_file, ['associative' => true]);
    }

    if (!is_array($metadata) || empty($metadata['name'])) {
        return false;
    }
    $metadata['file'] = wp_normalize_path(realpath($metadata_file));

    /**
     * Filters the metadata provided for registering a block type.
     *
     * @since 5.7.0
     *
     * @param  array  $metadata Metadata for registering a block type.
     */
    $metadata = apply_filters('block_type_metadata', $metadata);

    // Add `style` and `editor_style` for core blocks if missing.
    if (!empty($metadata['name']) && str_starts_with($metadata['name'], 'core/')) {
        $block_name = str_replace('core/', '', $metadata['name']);

        if (!isset($metadata['style'])) {
            $metadata['style'] = "wp-block-$block_name";
        }
        if (current_theme_supports('wp-block-styles') && wp_should_load_separate_core_block_assets()) {
            $metadata['style'] = (array) $metadata['style'];
            $metadata['style'][] = "wp-block-{$block_name}-theme";
        }
        if (!isset($metadata['editorStyle'])) {
            $metadata['editorStyle'] = "wp-block-{$block_name}-editor";
        }
    }

    $settings = [];
    $property_mappings = [
        'apiVersion' => 'api_version',
        'title' => 'title',
        'category' => 'category',
        'parent' => 'parent',
        'ancestor' => 'ancestor',
        'icon' => 'icon',
        'description' => 'description',
        'keywords' => 'keywords',
        'attributes' => 'attributes',
        'providesContext' => 'provides_context',
        'usesContext' => 'uses_context',
        'selectors' => 'selectors',
        'supports' => 'supports',
        'styles' => 'styles',
        'variations' => 'variations',
        'example' => 'example',
    ];
    $textdomain = !empty($metadata['textdomain']) ? $metadata['textdomain'] : null;
    $i18n_schema = get_block_metadata_i18n_schema();

    foreach ($property_mappings as $key => $mapped_key) {
        if (isset($metadata[$key])) {
            $settings[$mapped_key] = $metadata[$key];
            if ($textdomain && isset($i18n_schema->$key)) {
                $settings[$mapped_key] = translate_settings_using_i18n_schema($i18n_schema->$key, $settings[$key], $textdomain);
            }
        }
    }

    $script_fields = [
        'editorScript' => 'editor_script_handles',
        'script' => 'script_handles',
        'viewScript' => 'view_script_handles',
    ];
    foreach ($script_fields as $metadata_field_name => $settings_field_name) {
        if (!empty($metadata[$metadata_field_name])) {
            $scripts = $metadata[$metadata_field_name];
            $processed_scripts = [];
            if (is_array($scripts)) {
                for ($index = 0; $index < count($scripts); $index++) {
                    $result = register_block_script_handle(
                        $metadata,
                        $metadata_field_name,
                        $index
                    );
                    if ($result) {
                        $processed_scripts[] = $result;
                    }
                }
            } else {
                $result = register_block_script_handle(
                    $metadata,
                    $metadata_field_name
                );
                if ($result) {
                    $processed_scripts[] = $result;
                }
            }
            $settings[$settings_field_name] = $processed_scripts;
        }
    }

    $style_fields = [
        'editorStyle' => 'editor_style_handles',
        'style' => 'style_handles',
    ];
    foreach ($style_fields as $metadata_field_name => $settings_field_name) {
        if (!empty($metadata[$metadata_field_name])) {
            $styles = $metadata[$metadata_field_name];
            $processed_styles = [];
            if (is_array($styles)) {
                for ($index = 0; $index < count($styles); $index++) {
                    $result = register_block_style_handle(
                        $metadata,
                        $metadata_field_name,
                        $index
                    );
                    if ($result) {
                        $processed_styles[] = $result;
                    }
                }
            } else {
                $result = register_block_style_handle(
                    $metadata,
                    $metadata_field_name
                );
                if ($result) {
                    $processed_styles[] = $result;
                }
            }
            $settings[$settings_field_name] = $processed_styles;
        }
    }

    if (!empty($metadata['blockHooks'])) {
        /**
         * Map camelCased position string (from block.json) to snake_cased block type position.
         *
         * @var array
         */
        $position_mappings = [
            'before' => 'before',
            'after' => 'after',
            'firstChild' => 'first_child',
            'lastChild' => 'last_child',
        ];

        $settings['block_hooks'] = [];
        foreach ($metadata['blockHooks'] as $anchor_block_name => $position) {
            // Avoid infinite recursion (hooking to itself).
            if ($metadata['name'] === $anchor_block_name) {
                _doing_it_wrong(
                    __METHOD__,
                    __('Cannot hook block to itself.'),
                    '6.4.0'
                );

                continue;
            }

            if (!isset($position_mappings[$position])) {
                continue;
            }

            $settings['block_hooks'][$anchor_block_name] = $position_mappings[$position];
        }
    }

    if (!empty($metadata['render'])) {
        $template_path = wp_normalize_path(
            realpath(
                dirname($metadata['file']) . '/' .
                remove_block_asset_path_prefix($metadata['render'])
            )
        );
        if ($template_path) {
            /**
             * Renders the block on the server.
             *
             * @since 6.1.0
             *
             * @param  array  $attributes Block attributes.
             * @param  string  $content    Block default content.
             * @param  WP_Block  $block      Block instance.
             * @return string Returns the block content.
             */
            $settings['render_callback'] = static function ($attributes, $content, $block) use ($template_path) {
                ob_start();
                require $template_path;

                return ob_get_clean();
            };
        }
    }

    /**
     * Filters the settings determined from the block type metadata.
     *
     * @since 5.7.0
     *
     * @param  array  $settings Array of determined settings for registering a block type.
     * @param  array  $metadata Metadata provided for registering a block type.
     */
    $settings = apply_filters(
        'block_type_metadata_settings',
        array_merge(
            $settings,
            $args
        ),
        $metadata
    );

    return WP_Block_Type_Registry::get_instance()->register(
        $metadata['name'],
        $settings
    );
}

/**
 * Registers a block type. The recommended way is to register a block type using
 * the metadata stored in the `block.json` file.
 *
 * @since 5.0.0
 * @since 5.8.0 First parameter now accepts a path to the `block.json` file.
 *
 * @param  string|WP_Block_Type  $block_type Block type name including namespace, or alternatively
 *                                         a path to the JSON file with metadata definition for the block,
 *                                         or a path to the folder where the `block.json` file is located,
 *                                         or a complete WP_Block_Type instance.
 *                                         In case a WP_Block_Type is provided, the $args parameter will be ignored.
 * @param  array  $args       Optional. Array of block type arguments. Accepts any public property
 *                                         of `WP_Block_Type`. See WP_Block_Type::__construct() for information
 *                                         on accepted arguments. Default empty array.
 * @return WP_Block_Type|false The registered block type on success, or false on failure.
 */
function register_block_type($block_type, $args = [])
{
    if (is_string($block_type) && file_exists($block_type)) {
        return register_block_type_from_metadata($block_type, $args);
    }

    return WP_Block_Type_Registry::get_instance()->register($block_type, $args);
}

/**
 * Unregisters a block type.
 *
 * @since 5.0.0
 *
 * @param  string|WP_Block_Type  $name Block type name including namespace, or alternatively
 *                                   a complete WP_Block_Type instance.
 * @return WP_Block_Type|false The unregistered block type on success, or false on failure.
 */
function unregister_block_type($name)
{
    return WP_Block_Type_Registry::get_instance()->unregister($name);
}

/**
 * Determines whether a post or content string has blocks.
 *
 * This test optimizes for performance rather than strict accuracy, detecting
 * the pattern of a block but not validating its structure. For strict accuracy,
 * you should use the block parser on post content.
 *
 * @since 5.0.0
 * @see parse_blocks()
 *
 * @param  int|string|WP_Post|null  $post Optional. Post content, post ID, or post object.
 *                                      Defaults to global $post.
 * @return bool Whether the post has blocks.
 */
function has_blocks($post = null)
{
    if (!is_string($post)) {
        $wp_post = get_post($post);

        if (!$wp_post instanceof WP_Post) {
            return false;
        }

        $post = $wp_post->post_content;
    }

    return str_contains((string) $post, '<!-- wp:');
}

/**
 * Determines whether a $post or a string contains a specific block type.
 *
 * This test optimizes for performance rather than strict accuracy, detecting
 * whether the block type exists but not validating its structure and not checking
 * synced patterns (formerly called reusable blocks). For strict accuracy,
 * you should use the block parser on post content.
 *
 * @since 5.0.0
 * @see parse_blocks()
 *
 * @param  string  $block_name Full block type to look for.
 * @param  int|string|WP_Post|null  $post       Optional. Post content, post ID, or post object.
 *                                            Defaults to global $post.
 * @return bool Whether the post content contains the specified block.
 */
function has_block($block_name, $post = null)
{
    if (!has_blocks($post)) {
        return false;
    }

    if (!is_string($post)) {
        $wp_post = get_post($post);
        if ($wp_post instanceof WP_Post) {
            $post = $wp_post->post_content;
        }
    }

    /*
     * Normalize block name to include namespace, if provided as non-namespaced.
     * This matches behavior for WordPress 5.0.0 - 5.3.0 in matching blocks by
     * their serialized names.
     */
    if (!str_contains($block_name, '/')) {
        $block_name = 'core/' . $block_name;
    }

    // Test for existence of block by its fully qualified name.
    $has_block = str_contains($post, '<!-- wp:' . $block_name . ' ');

    if (!$has_block) {
        /*
         * If the given block name would serialize to a different name, test for
         * existence by the serialized form.
         */
        $serialized_block_name = strip_core_block_namespace($block_name);
        if ($serialized_block_name !== $block_name) {
            $has_block = str_contains($post, '<!-- wp:' . $serialized_block_name . ' ');
        }
    }

    return $has_block;
}

/**
 * Returns an array of the names of all registered dynamic block types.
 *
 * @since 5.0.0
 *
 * @return string[] Array of dynamic block names.
 */
function get_dynamic_block_names()
{
    $dynamic_block_names = [];

    $block_types = WP_Block_Type_Registry::get_instance()->get_all_registered();
    foreach ($block_types as $block_type) {
        if ($block_type->is_dynamic()) {
            $dynamic_block_names[] = $block_type->name;
        }
    }

    return $dynamic_block_names;
}

/**
 * Retrieves block types hooked into the given block, grouped by anchor block type and the relative position.
 *
 * @since 6.4.0
 *
 * @return array[] Array of block types grouped by anchor block type and the relative position.
 */
function get_hooked_blocks()
{
    $block_types = WP_Block_Type_Registry::get_instance()->get_all_registered();
    $hooked_blocks = [];
    foreach ($block_types as $block_type) {
        if (!($block_type instanceof WP_Block_Type) || !is_array($block_type->block_hooks)) {
            continue;
        }
        foreach ($block_type->block_hooks as $anchor_block_type => $relative_position) {
            if (!isset($hooked_blocks[$anchor_block_type])) {
                $hooked_blocks[$anchor_block_type] = [];
            }
            if (!isset($hooked_blocks[$anchor_block_type][$relative_position])) {
                $hooked_blocks[$anchor_block_type][$relative_position] = [];
            }
            $hooked_blocks[$anchor_block_type][$relative_position][] = $block_type->name;
        }
    }

    return $hooked_blocks;
}

/**
 * Returns a function that injects the theme attribute into, and hooked blocks before, a given block.
 *
 * The returned function can be used as `$pre_callback` argument to `traverse_and_serialize_block(s)`,
 * where it will inject the `theme` attribute into all Template Part blocks, and prepend the markup for
 * any blocks hooked `before` the given block and as its parent's `first_child`, respectively.
 *
 * This function is meant for internal use only.
 *
 * @since 6.4.0
 *
 * @param  array  $hooked_blocks An array of blocks hooked to another given block.
 * @param  WP_Block_Template|array  $context       A block template, template part, or pattern that the blocks belong to.
 * @return callable A function that returns the serialized markup for the given block,
 *                  including the markup for any hooked blocks before it.
 */
function make_before_block_visitor($hooked_blocks, $context)
{
    /**
     * Injects hooked blocks before the given block, injects the `theme` attribute into Template Part blocks, and returns the serialized markup.
     *
     * If the current block is a Template Part block, inject the `theme` attribute.
     * Furthermore, prepend the markup for any blocks hooked `before` the given block and as its parent's
     * `first_child`, respectively, to the serialized markup for the given block.
     *
     * @param  array  $block        The block to inject the theme attribute into, and hooked blocks before. Passed by reference.
     * @param  array  $parent_block The parent block of the given block. Passed by reference. Default null.
     * @param  array  $prev         The previous sibling block of the given block. Default null.
     * @return string The serialized markup for the given block, with the markup for any hooked blocks prepended to it.
     */
    return function (&$block, &$parent_block = null, $prev = null) use ($hooked_blocks, $context) {
        _inject_theme_attribute_in_template_part_block($block);

        $markup = '';

        if ($parent_block && !$prev) {
            // Candidate for first-child insertion.
            $relative_position = 'first_child';
            $anchor_block_type = $parent_block['blockName'];
            $hooked_block_types = isset($hooked_blocks[$anchor_block_type][$relative_position])
                ? $hooked_blocks[$anchor_block_type][$relative_position]
                : [];

            /**
             * Filters the list of hooked block types for a given anchor block type and relative position.
             *
             * @since 6.4.0
             *
             * @param  string[]  $hooked_block_types  The list of hooked block types.
             * @param  string  $relative_position   The relative position of the hooked blocks.
             *                                                     Can be one of 'before', 'after', 'first_child', or 'last_child'.
             * @param  string  $anchor_block_type   The anchor block type.
             * @param  WP_Block_Template|array  $context             The block template, template part, or pattern that the anchor block belongs to.
             */
            $hooked_block_types = apply_filters('hooked_block_types', $hooked_block_types, $relative_position, $anchor_block_type, $context);
            foreach ($hooked_block_types as $hooked_block_type) {
                $markup .= get_comment_delimited_block_content($hooked_block_type, [], '');
            }
        }

        $relative_position = 'before';
        $anchor_block_type = $block['blockName'];
        $hooked_block_types = isset($hooked_blocks[$anchor_block_type][$relative_position])
            ? $hooked_blocks[$anchor_block_type][$relative_position]
            : [];

        /** This filter is documented in wp-includes/blocks.php */
        $hooked_block_types = apply_filters('hooked_block_types', $hooked_block_types, $relative_position, $anchor_block_type, $context);
        foreach ($hooked_block_types as $hooked_block_type) {
            $markup .= get_comment_delimited_block_content($hooked_block_type, [], '');
        }

        return $markup;
    };
}

/**
 * Returns a function that injects the hooked blocks after a given block.
 *
 * The returned function can be used as `$post_callback` argument to `traverse_and_serialize_block(s)`,
 * where it will append the markup for any blocks hooked `after` the given block and as its parent's
 * `last_child`, respectively.
 *
 * This function is meant for internal use only.
 *
 * @since 6.4.0
 *
 * @param  array  $hooked_blocks An array of blocks hooked to another block.
 * @param  WP_Block_Template|array  $context       A block template, template part, or pattern that the blocks belong to.
 * @return callable A function that returns the serialized markup for the given block,
 *                  including the markup for any hooked blocks after it.
 */
function make_after_block_visitor($hooked_blocks, $context)
{
    /**
     * Injects hooked blocks after the given block, and returns the serialized markup.
     *
     * Append the markup for any blocks hooked `after` the given block and as its parent's
     * `last_child`, respectively, to the serialized markup for the given block.
     *
     * @param  array  $block        The block to inject the hooked blocks after. Passed by reference.
     * @param  array  $parent_block The parent block of the given block. Passed by reference. Default null.
     * @param  array  $next         The next sibling block of the given block. Default null.
     * @return string The serialized markup for the given block, with the markup for any hooked blocks appended to it.
     */
    return function (&$block, &$parent_block = null, $next = null) use ($hooked_blocks, $context) {
        $markup = '';

        $relative_position = 'after';
        $anchor_block_type = $block['blockName'];
        $hooked_block_types = isset($hooked_blocks[$anchor_block_type][$relative_position])
                ? $hooked_blocks[$anchor_block_type][$relative_position]
                : [];

        /** This filter is documented in wp-includes/blocks.php */
        $hooked_block_types = apply_filters('hooked_block_types', $hooked_block_types, $relative_position, $anchor_block_type, $context);
        foreach ($hooked_block_types as $hooked_block_type) {
            $markup .= get_comment_delimited_block_content($hooked_block_type, [], '');
        }

        if ($parent_block && !$next) {
            // Candidate for last-child insertion.
            $relative_position = 'last_child';
            $anchor_block_type = $parent_block['blockName'];
            $hooked_block_types = isset($hooked_blocks[$anchor_block_type][$relative_position])
                ? $hooked_blocks[$anchor_block_type][$relative_position]
                : [];

            /** This filter is documented in wp-includes/blocks.php */
            $hooked_block_types = apply_filters('hooked_block_types', $hooked_block_types, $relative_position, $anchor_block_type, $context);
            foreach ($hooked_block_types as $hooked_block_type) {
                $markup .= get_comment_delimited_block_content($hooked_block_type, [], '');
            }
        }

        return $markup;
    };
}

/**
 * Given an array of attributes, returns a string in the serialized attributes
 * format prepared for post content.
 *
 * The serialized result is a JSON-encoded string, with unicode escape sequence
 * substitution for characters which might otherwise interfere with embedding
 * the result in an HTML comment.
 *
 * This function must produce output that remains in sync with the output of
 * the serializeAttributes JavaScript function in the block editor in order
 * to ensure consistent operation between PHP and JavaScript.
 *
 * @since 5.3.1
 *
 * @param  array  $block_attributes Attributes object.
 * @return string Serialized attributes.
 */
function serialize_block_attributes($block_attributes)
{
    $encoded_attributes = wp_json_encode($block_attributes, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    $encoded_attributes = preg_replace('/--/', '\\u002d\\u002d', $encoded_attributes);
    $encoded_attributes = preg_replace('/</', '\\u003c', $encoded_attributes);
    $encoded_attributes = preg_replace('/>/', '\\u003e', $encoded_attributes);
    $encoded_attributes = preg_replace('/&/', '\\u0026', $encoded_attributes);
    // Regex: /\\"/
    $encoded_attributes = preg_replace('/\\\\"/', '\\u0022', $encoded_attributes);

    return $encoded_attributes;
}

/**
 * Returns the block name to use for serialization. This will remove the default
 * "core/" namespace from a block name.
 *
 * @since 5.3.1
 *
 * @param  string|null  $block_name Optional. Original block name. Null if the block name is unknown,
 *                                e.g. Classic blocks have their name set to null. Default null.
 * @return string Block name to use for serialization.
 */
function strip_core_block_namespace($block_name = null)
{
    if (is_string($block_name) && str_starts_with($block_name, 'core/')) {
        return substr($block_name, 5);
    }

    return $block_name;
}

/**
 * Returns the content of a block, including comment delimiters.
 *
 * @since 5.3.1
 *
 * @param  string|null  $block_name       Block name. Null if the block name is unknown,
 *                                      e.g. Classic blocks have their name set to null.
 * @param  array  $block_attributes Block attributes.
 * @param  string  $block_content    Block save content.
 * @return string Comment-delimited block content.
 */
function get_comment_delimited_block_content($block_name, $block_attributes, $block_content)
{
    if (is_null($block_name)) {
        return $block_content;
    }

    $serialized_block_name = strip_core_block_namespace($block_name);
    $serialized_attributes = empty($block_attributes) ? '' : serialize_block_attributes($block_attributes) . ' ';

    if (empty($block_content)) {
        return sprintf('<!-- wp:%s %s/-->', $serialized_block_name, $serialized_attributes);
    }

    return sprintf(
        '<!-- wp:%s %s-->%s<!-- /wp:%s -->',
        $serialized_block_name,
        $serialized_attributes,
        $block_content,
        $serialized_block_name
    );
}

/**
 * Returns the content of a block, including comment delimiters, serializing all
 * attributes from the given parsed block.
 *
 * This should be used when preparing a block to be saved to post content.
 * Prefer `render_block` when preparing a block for display. Unlike
 * `render_block`, this does not evaluate a block's `render_callback`, and will
 * instead preserve the markup as parsed.
 *
 * @since 5.3.1
 *
 * @param  array  $block A representative array of a single parsed block object. See WP_Block_Parser_Block.
 * @return string String of rendered HTML.
 */
function serialize_block($block)
{
    $block_content = '';

    $index = 0;
    foreach ($block['innerContent'] as $chunk) {
        $block_content .= is_string($chunk) ? $chunk : serialize_block($block['innerBlocks'][$index++]);
    }

    if (!is_array($block['attrs'])) {
        $block['attrs'] = [];
    }

    return get_comment_delimited_block_content(
        $block['blockName'],
        $block['attrs'],
        $block_content
    );
}

/**
 * Returns a joined string of the aggregate serialization of the given
 * parsed blocks.
 *
 * @since 5.3.1
 *
 * @param  array[]  $blocks An array of representative arrays of parsed block objects. See serialize_block().
 * @return string String of rendered HTML.
 */
function serialize_blocks($blocks)
{
    return implode('', array_map('serialize_block', $blocks));
}

/**
 * Traverses a parsed block tree and applies callbacks before and after serializing it.
 *
 * Recursively traverses the block and its inner blocks and applies the two callbacks provided as
 * arguments, the first one before serializing the block, and the second one after serializing it.
 * If either callback returns a string value, it will be prepended and appended to the serialized
 * block markup, respectively.
 *
 * The callbacks will receive a reference to the current block as their first argument, so that they
 * can also modify it, and the current block's parent block as second argument. Finally, the
 * `$pre_callback` receives the previous block, whereas the `$post_callback` receives
 * the next block as third argument.
 *
 * Serialized blocks are returned including comment delimiters, and with all attributes serialized.
 *
 * This function should be used when there is a need to modify the saved block, or to inject markup
 * into the return value. Prefer `serialize_block` when preparing a block to be saved to post content.
 *
 * This function is meant for internal use only.
 *
 * @since 6.4.0
 * @see serialize_block()
 *
 * @param  array  $block         A representative array of a single parsed block object. See WP_Block_Parser_Block.
 * @param  callable  $pre_callback  Callback to run on each block in the tree before it is traversed and serialized.
 *                                It is called with the following arguments: &$block, $parent_block, $previous_block.
 *                                Its string return value will be prepended to the serialized block markup.
 * @param  callable  $post_callback Callback to run on each block in the tree after it is traversed and serialized.
 *                                It is called with the following arguments: &$block, $parent_block, $next_block.
 *                                Its string return value will be appended to the serialized block markup.
 * @return string Serialized block markup.
 */
function traverse_and_serialize_block($block, $pre_callback = null, $post_callback = null)
{
    $block_content = '';
    $block_index = 0;

    foreach ($block['innerContent'] as $chunk) {
        if (is_string($chunk)) {
            $block_content .= $chunk;
        } else {
            $inner_block = $block['innerBlocks'][$block_index];

            if (is_callable($pre_callback)) {
                $prev = $block_index === 0
                    ? null
                    : $block['innerBlocks'][$block_index - 1];

                $block_content .= call_user_func_array(
                    $pre_callback,
                    [&$inner_block, &$block, $prev]
                );
            }

            if (is_callable($post_callback)) {
                $next = count($block['innerBlocks']) - 1 === $block_index
                    ? null
                    : $block['innerBlocks'][$block_index + 1];

                $post_markup = call_user_func_array(
                    $post_callback,
                    [&$inner_block, &$block, $next]
                );
            }

            $block_content .= traverse_and_serialize_block($inner_block, $pre_callback, $post_callback);
            $block_content .= isset($post_markup) ? $post_markup : '';

            $block_index++;
        }
    }

    if (!is_array($block['attrs'])) {
        $block['attrs'] = [];
    }

    return get_comment_delimited_block_content(
        $block['blockName'],
        $block['attrs'],
        $block_content
    );
}

/**
 * Given an array of parsed block trees, applies callbacks before and after serializing them and
 * returns their concatenated output.
 *
 * Recursively traverses the blocks and their inner blocks and applies the two callbacks provided as
 * arguments, the first one before serializing a block, and the second one after serializing.
 * If either callback returns a string value, it will be prepended and appended to the serialized
 * block markup, respectively.
 *
 * The callbacks will receive a reference to the current block as their first argument, so that they
 * can also modify it, and the current block's parent block as second argument. Finally, the
 * `$pre_callback` receives the previous block, whereas the `$post_callback` receives
 * the next block as third argument.
 *
 * Serialized blocks are returned including comment delimiters, and with all attributes serialized.
 *
 * This function should be used when there is a need to modify the saved blocks, or to inject markup
 * into the return value. Prefer `serialize_blocks` when preparing blocks to be saved to post content.
 *
 * This function is meant for internal use only.
 *
 * @since 6.4.0
 * @see serialize_blocks()
 *
 * @param  array[]  $blocks        An array of parsed blocks. See WP_Block_Parser_Block.
 * @param  callable  $pre_callback  Callback to run on each block in the tree before it is traversed and serialized.
 *                                It is called with the following arguments: &$block, $parent_block, $previous_block.
 *                                Its string return value will be prepended to the serialized block markup.
 * @param  callable  $post_callback Callback to run on each block in the tree after it is traversed and serialized.
 *                                It is called with the following arguments: &$block, $parent_block, $next_block.
 *                                Its string return value will be appended to the serialized block markup.
 * @return string Serialized block markup.
 */
function traverse_and_serialize_blocks($blocks, $pre_callback = null, $post_callback = null)
{
    $result = '';
    $parent_block = null; // At the top level, there is no parent block to pass to the callbacks; yet the callbacks expect a reference.

    foreach ($blocks as $index => $block) {
        if (is_callable($pre_callback)) {
            $prev = $index === 0
                ? null
                : $blocks[$index - 1];

            $result .= call_user_func_array(
                $pre_callback,
                [&$block, &$parent_block, $prev]
            );
        }

        if (is_callable($post_callback)) {
            $next = count($blocks) - 1 === $index
                ? null
                : $blocks[$index + 1];

            $post_markup = call_user_func_array(
                $post_callback,
                [&$block, &$parent_block, $next]
            );
        }

        $result .= traverse_and_serialize_block($block, $pre_callback, $post_callback);
        $result .= isset($post_markup) ? $post_markup : '';
    }

    return $result;
}

/**
 * Filters and sanitizes block content to remove non-allowable HTML
 * from parsed block attribute values.
 *
 * @since 5.3.1
 *
 * @param  string  $text              Text that may contain block content.
 * @param  array[]|string  $allowed_html      Optional. An array of allowed HTML elements and attributes,
 *                                          or a context name such as 'post'. See wp_kses_allowed_html()
 *                                          for the list of accepted context names. Default 'post'.
 * @param  string[]  $allowed_protocols Optional. Array of allowed URL protocols.
 *                                          Defaults to the result of wp_allowed_protocols().
 * @return string The filtered and sanitized content result.
 */
function filter_block_content($text, $allowed_html = 'post', $allowed_protocols = [])
{
    $result = '';

    if (str_contains($text, '<!--') && str_contains($text, '--->')) {
        $text = preg_replace_callback('%<!--(.*?)--->%', '_filter_block_content_callback', $text);
    }

    $blocks = parse_blocks($text);
    foreach ($blocks as $block) {
        $block = filter_block_kses($block, $allowed_html, $allowed_protocols);
        $result .= serialize_block($block);
    }

    return $result;
}

/**
 * Callback used for regular expression replacement in filter_block_content().
 *
 * @private
 *
 * @since 6.2.1
 *
 * @param  array  $matches Array of preg_replace_callback matches.
 * @return string Replacement string.
 */
function _filter_block_content_callback($matches)
{
    return '<!--' . rtrim($matches[1], '-') . '-->';
}

/**
 * Filters and sanitizes a parsed block to remove non-allowable HTML
 * from block attribute values.
 *
 * @since 5.3.1
 *
 * @param  WP_Block_Parser_Block  $block             The parsed block object.
 * @param  array[]|string  $allowed_html      An array of allowed HTML elements and attributes,
 *                                                 or a context name such as 'post'. See wp_kses_allowed_html()
 *                                                 for the list of accepted context names.
 * @param  string[]  $allowed_protocols Optional. Array of allowed URL protocols.
 *                                                 Defaults to the result of wp_allowed_protocols().
 * @return array The filtered and sanitized block object result.
 */
function filter_block_kses($block, $allowed_html, $allowed_protocols = [])
{
    $block['attrs'] = filter_block_kses_value($block['attrs'], $allowed_html, $allowed_protocols);

    if (is_array($block['innerBlocks'])) {
        foreach ($block['innerBlocks'] as $i => $inner_block) {
            $block['innerBlocks'][$i] = filter_block_kses($inner_block, $allowed_html, $allowed_protocols);
        }
    }

    return $block;
}

/**
 * Filters and sanitizes a parsed block attribute value to remove
 * non-allowable HTML.
 *
 * @since 5.3.1
 *
 * @param  string[]|string  $value             The attribute value to filter.
 * @param  array[]|string  $allowed_html      An array of allowed HTML elements and attributes,
 *                                           or a context name such as 'post'. See wp_kses_allowed_html()
 *                                           for the list of accepted context names.
 * @param  string[]  $allowed_protocols Optional. Array of allowed URL protocols.
 *                                           Defaults to the result of wp_allowed_protocols().
 * @return string[]|string The filtered and sanitized result.
 */
function filter_block_kses_value($value, $allowed_html, $allowed_protocols = [])
{
    if (is_array($value)) {
        foreach ($value as $key => $inner_value) {
            $filtered_key = filter_block_kses_value($key, $allowed_html, $allowed_protocols);
            $filtered_value = filter_block_kses_value($inner_value, $allowed_html, $allowed_protocols);

            if ($filtered_key !== $key) {
                unset($value[$key]);
            }

            $value[$filtered_key] = $filtered_value;
        }
    } elseif (is_string($value)) {
        return wp_kses($value, $allowed_html, $allowed_protocols);
    }

    return $value;
}

/**
 * Parses blocks out of a content string, and renders those appropriate for the excerpt.
 *
 * As the excerpt should be a small string of text relevant to the full post content,
 * this function renders the blocks that are most likely to contain such text.
 *
 * @since 5.0.0
 *
 * @param  string  $content The content to parse.
 * @return string The parsed and filtered content.
 */
function excerpt_remove_blocks($content)
{
    if (!has_blocks($content)) {
        return $content;
    }

    $allowed_inner_blocks = [
        // Classic blocks have their blockName set to null.
        null,
        'core/freeform',
        'core/heading',
        'core/html',
        'core/list',
        'core/media-text',
        'core/paragraph',
        'core/preformatted',
        'core/pullquote',
        'core/quote',
        'core/table',
        'core/verse',
    ];

    $allowed_wrapper_blocks = [
        'core/columns',
        'core/column',
        'core/group',
    ];

    /**
     * Filters the list of blocks that can be used as wrapper blocks, allowing
     * excerpts to be generated from the `innerBlocks` of these wrappers.
     *
     * @since 5.8.0
     *
     * @param  string[]  $allowed_wrapper_blocks The list of names of allowed wrapper blocks.
     */
    $allowed_wrapper_blocks = apply_filters('excerpt_allowed_wrapper_blocks', $allowed_wrapper_blocks);

    $allowed_blocks = array_merge($allowed_inner_blocks, $allowed_wrapper_blocks);

    /**
     * Filters the list of blocks that can contribute to the excerpt.
     *
     * If a dynamic block is added to this list, it must not generate another
     * excerpt, as this will cause an infinite loop to occur.
     *
     * @since 5.0.0
     *
     * @param  string[]  $allowed_blocks The list of names of allowed blocks.
     */
    $allowed_blocks = apply_filters('excerpt_allowed_blocks', $allowed_blocks);
    $blocks = parse_blocks($content);
    $output = '';

    foreach ($blocks as $block) {
        if (in_array($block['blockName'], $allowed_blocks, true)) {
            if (!empty($block['innerBlocks'])) {
                if (in_array($block['blockName'], $allowed_wrapper_blocks, true)) {
                    $output .= _excerpt_render_inner_blocks($block, $allowed_blocks);

                    continue;
                }

                // Skip the block if it has disallowed or nested inner blocks.
                foreach ($block['innerBlocks'] as $inner_block) {
                    if (
                        !in_array($inner_block['blockName'], $allowed_inner_blocks, true) ||
                        !empty($inner_block['innerBlocks'])
                    ) {
                        continue 2;
                    }
                }
            }

            $output .= render_block($block);
        }
    }

    return $output;
}

/**
 * Parses footnotes markup out of a content string,
 * and renders those appropriate for the excerpt.
 *
 * @since 6.3.0
 *
 * @param  string  $content The content to parse.
 * @return string The parsed and filtered content.
 */
function excerpt_remove_footnotes($content)
{
    if (!str_contains($content, 'data-fn=')) {
        return $content;
    }

    return preg_replace(
        '_<sup data-fn="[^"]+" class="[^"]+">\s*<a href="[^"]+" id="[^"]+">\d+</a>\s*</sup>_',
        '',
        $content
    );
}

/**
 * Renders inner blocks from the allowed wrapper blocks
 * for generating an excerpt.
 *
 * @since 5.8.0
 *
 * @param  array  $parsed_block   The parsed block.
 * @param  array  $allowed_blocks The list of allowed inner blocks.
 * @return string The rendered inner blocks.
 */
function _excerpt_render_inner_blocks($parsed_block, $allowed_blocks)
{
    $output = '';

    foreach ($parsed_block['innerBlocks'] as $inner_block) {
        if (!in_array($inner_block['blockName'], $allowed_blocks, true)) {
            continue;
        }

        if (empty($inner_block['innerBlocks'])) {
            $output .= render_block($inner_block);
        } else {
            $output .= _excerpt_render_inner_blocks($inner_block, $allowed_blocks);
        }
    }

    return $output;
}

/**
 * Renders a single block into a HTML string.
 *
 * @since 5.0.0
 *
 * @global WP_Post $post The post to edit.
 *
 * @param  array  $parsed_block A single parsed block object.
 * @return string String of rendered HTML.
 */
function render_block($parsed_block)
{
    global $post;
    $parent_block = null;

    /**
     * Allows render_block() to be short-circuited, by returning a non-null value.
     *
     * @since 5.1.0
     * @since 5.9.0 The `$parent_block` parameter was added.
     *
     * @param  string|null  $pre_render   The pre-rendered content. Default null.
     * @param  array  $parsed_block The block being rendered.
     * @param  WP_Block|null  $parent_block If this is a nested block, a reference to the parent block.
     */
    $pre_render = apply_filters('pre_render_block', null, $parsed_block, $parent_block);
    if (!is_null($pre_render)) {
        return $pre_render;
    }

    $source_block = $parsed_block;

    /**
     * Filters the block being rendered in render_block(), before it's processed.
     *
     * @since 5.1.0
     * @since 5.9.0 The `$parent_block` parameter was added.
     *
     * @param  array  $parsed_block The block being rendered.
     * @param  array  $source_block An un-modified copy of $parsed_block, as it appeared in the source content.
     * @param  WP_Block|null  $parent_block If this is a nested block, a reference to the parent block.
     */
    $parsed_block = apply_filters('render_block_data', $parsed_block, $source_block, $parent_block);

    $context = [];

    if ($post instanceof WP_Post) {
        $context['postId'] = $post->ID;

        /*
         * The `postType` context is largely unnecessary server-side, since the ID
         * is usually sufficient on its own. That being said, since a block's
         * manifest is expected to be shared between the server and the client,
         * it should be included to consistently fulfill the expectation.
         */
        $context['postType'] = $post->post_type;
    }

    /**
     * Filters the default context provided to a rendered block.
     *
     * @since 5.5.0
     * @since 5.9.0 The `$parent_block` parameter was added.
     *
     * @param  array  $context      Default context.
     * @param  array  $parsed_block Block being rendered, filtered by `render_block_data`.
     * @param  WP_Block|null  $parent_block If this is a nested block, a reference to the parent block.
     */
    $context = apply_filters('render_block_context', $context, $parsed_block, $parent_block);

    $block = new WP_Block($parsed_block, $context);

    return $block->render();
}

/**
 * Parses blocks out of a content string.
 *
 * @since 5.0.0
 *
 * @param  string  $content Post content.
 * @return array[] Array of parsed block objects.
 */
function parse_blocks($content)
{
    /**
     * Filter to allow plugins to replace the server-side block parser.
     *
     * @since 5.0.0
     *
     * @param  string  $parser_class Name of block parser class.
     */
    $parser_class = apply_filters('block_parser_class', 'WP_Block_Parser');

    $parser = new $parser_class();

    return $parser->parse($content);
}

/**
 * Parses dynamic blocks out of `post_content` and re-renders them.
 *
 * @since 5.0.0
 *
 * @param  string  $content Post content.
 * @return string Updated post content.
 */
function do_blocks($content)
{
    $blocks = parse_blocks($content);
    $output = '';

    foreach ($blocks as $block) {
        $output .= render_block($block);
    }

    // If there are blocks in this content, we shouldn't run wpautop() on it later.
    $priority = has_filter('the_content', 'wpautop');
    if ($priority !== false && doing_filter('the_content') && has_blocks($content)) {
        remove_filter('the_content', 'wpautop', $priority);
        add_filter('the_content', '_restore_wpautop_hook', $priority + 1);
    }

    return $output;
}

/**
 * If do_blocks() needs to remove wpautop() from the `the_content` filter, this re-adds it afterwards,
 * for subsequent `the_content` usage.
 *
 * @since 5.0.0
 *
 * @param  string  $content The post content running through this filter.
 * @return string The unmodified content.
 */
function _restore_wpautop_hook($content)
{
    $current_priority = has_filter('the_content', '_restore_wpautop_hook');

    add_filter('the_content', 'wpautop', $current_priority - 1);
    remove_filter('the_content', '_restore_wpautop_hook', $current_priority);

    return $content;
}

/**
 * Returns the current version of the block format that the content string is using.
 *
 * If the string doesn't contain blocks, it returns 0.
 *
 * @since 5.0.0
 *
 * @param  string  $content Content to test.
 * @return int The block format version is 1 if the content contains one or more blocks, 0 otherwise.
 */
function block_version($content)
{
    return has_blocks($content) ? 1 : 0;
}

/**
 * Registers a new block style.
 *
 * @since 5.3.0
 * @link https://developer.wordpress.org/block-editor/reference-guides/block-api/block-styles/
 *
 * @param  string  $block_name       Block type name including namespace.
 * @param  array  $style_properties Array containing the properties of the style name, label,
 *                                 style_handle (name of the stylesheet to be enqueued),
 *                                 inline_style (string containing the CSS to be added).
 * @return bool True if the block style was registered with success and false otherwise.
 */
function register_block_style($block_name, $style_properties)
{
    return WP_Block_Styles_Registry::get_instance()->register($block_name, $style_properties);
}

/**
 * Unregisters a block style.
 *
 * @since 5.3.0
 *
 * @param  string  $block_name       Block type name including namespace.
 * @param  string  $block_style_name Block style name.
 * @return bool True if the block style was unregistered with success and false otherwise.
 */
function unregister_block_style($block_name, $block_style_name)
{
    return WP_Block_Styles_Registry::get_instance()->unregister($block_name, $block_style_name);
}

/**
 * Checks whether the current block type supports the feature requested.
 *
 * @since 5.8.0
 * @since 6.4.0 The `$feature` parameter now supports a string.
 *
 * @param  WP_Block_Type  $block_type    Block type to check for support.
 * @param  string|array  $feature       Feature slug, or path to a specific feature to check support for.
 * @param  mixed  $default_value Optional. Fallback value for feature support. Default false.
 * @return bool Whether the feature is supported.
 */
function block_has_support($block_type, $feature, $default_value = false)
{
    $block_support = $default_value;
    if ($block_type instanceof WP_Block_Type) {
        if (is_array($feature) && count($feature) === 1) {
            $feature = $feature[0];
        }

        if (is_array($feature)) {
            $block_support = _wp_array_get($block_type->supports, $feature, $default_value);
        } elseif (isset($block_type->supports[$feature])) {
            $block_support = $block_type->supports[$feature];
        }
    }

    return $block_support === true || is_array($block_support);
}

/**
 * Converts typography keys declared under `supports.*` to `supports.typography.*`.
 *
 * Displays a `_doing_it_wrong()` notice when a block using the older format is detected.
 *
 * @since 5.8.0
 *
 * @param  array  $metadata Metadata for registering a block type.
 * @return array Filtered metadata for registering a block type.
 */
function wp_migrate_old_typography_shape($metadata)
{
    if (!isset($metadata['supports'])) {
        return $metadata;
    }

    $typography_keys = [
        '__experimentalFontFamily',
        '__experimentalFontStyle',
        '__experimentalFontWeight',
        '__experimentalLetterSpacing',
        '__experimentalTextDecoration',
        '__experimentalTextTransform',
        'fontSize',
        'lineHeight',
    ];

    foreach ($typography_keys as $typography_key) {
        $support_for_key = isset($metadata['supports'][$typography_key]) ? $metadata['supports'][$typography_key] : null;

        if ($support_for_key !== null) {
            _doing_it_wrong(
                'register_block_type_from_metadata()',
                sprintf(
                    /* translators: 1: Block type, 2: Typography supports key, e.g: fontSize, lineHeight, etc. 3: block.json, 4: Old metadata key, 5: New metadata key. */
                    __('Block "%1$s" is declaring %2$s support in %3$s file under %4$s. %2$s support is now declared under %5$s.'),
                    $metadata['name'],
                    "<code>$typography_key</code>",
                    '<code>block.json</code>',
                    "<code>supports.$typography_key</code>",
                    "<code>supports.typography.$typography_key</code>"
                ),
                '5.8.0'
            );

            _wp_array_set($metadata['supports'], ['typography', $typography_key], $support_for_key);
            unset($metadata['supports'][$typography_key]);
        }
    }

    return $metadata;
}

/**
 * Helper function that constructs a WP_Query args array from
 * a `Query` block properties.
 *
 * It's used in Query Loop, Query Pagination Numbers and Query Pagination Next blocks.
 *
 * @since 5.8.0
 * @since 6.1.0 Added `query_loop_block_query_vars` filter and `parents` support in query.
 *
 * @param  WP_Block  $block Block instance.
 * @param  int  $page  Current query's page.
 * @return array Returns the constructed WP_Query arguments.
 */
function build_query_vars_from_query_block($block, $page)
{
    $query = [
        'post_type' => 'post',
        'order' => 'DESC',
        'orderby' => 'date',
        'post__not_in' => [],
    ];

    if (isset($block->context['query'])) {
        if (!empty($block->context['query']['postType'])) {
            $post_type_param = $block->context['query']['postType'];
            if (is_post_type_viewable($post_type_param)) {
                $query['post_type'] = $post_type_param;
            }
        }
        if (isset($block->context['query']['sticky']) && !empty($block->context['query']['sticky'])) {
            $sticky = get_option('sticky_posts');
            if ($block->context['query']['sticky'] === 'only') {
                /*
                 * Passing an empty array to post__in will return have_posts() as true (and all posts will be returned).
                 * Logic should be used before hand to determine if WP_Query should be used in the event that the array
                 * being passed to post__in is empty.
                 *
                 * @see https://core.trac.wordpress.org/ticket/28099
                 */
                $query['post__in'] = !empty($sticky) ? $sticky : [0];
                $query['ignore_sticky_posts'] = 1;
            } else {
                $query['post__not_in'] = array_merge($query['post__not_in'], $sticky);
            }
        }
        if (!empty($block->context['query']['exclude'])) {
            $excluded_post_ids = array_map('intval', $block->context['query']['exclude']);
            $excluded_post_ids = array_filter($excluded_post_ids);
            $query['post__not_in'] = array_merge($query['post__not_in'], $excluded_post_ids);
        }
        if (
            isset($block->context['query']['perPage']) &&
            is_numeric($block->context['query']['perPage'])
        ) {
            $per_page = absint($block->context['query']['perPage']);
            $offset = 0;

            if (
                isset($block->context['query']['offset']) &&
                is_numeric($block->context['query']['offset'])
            ) {
                $offset = absint($block->context['query']['offset']);
            }

            $query['offset'] = ($per_page * ($page - 1)) + $offset;
            $query['posts_per_page'] = $per_page;
        }
        // Migrate `categoryIds` and `tagIds` to `tax_query` for backwards compatibility.
        if (!empty($block->context['query']['categoryIds']) || !empty($block->context['query']['tagIds'])) {
            $tax_query = [];
            if (!empty($block->context['query']['categoryIds'])) {
                $tax_query[] = [
                    'taxonomy' => 'category',
                    'terms' => array_filter(array_map('intval', $block->context['query']['categoryIds'])),
                    'include_children' => false,
                ];
            }
            if (!empty($block->context['query']['tagIds'])) {
                $tax_query[] = [
                    'taxonomy' => 'post_tag',
                    'terms' => array_filter(array_map('intval', $block->context['query']['tagIds'])),
                    'include_children' => false,
                ];
            }
            $query['tax_query'] = $tax_query;
        }
        if (!empty($block->context['query']['taxQuery'])) {
            $query['tax_query'] = [];
            foreach ($block->context['query']['taxQuery'] as $taxonomy => $terms) {
                if (is_taxonomy_viewable($taxonomy) && !empty($terms)) {
                    $query['tax_query'][] = [
                        'taxonomy' => $taxonomy,
                        'terms' => array_filter(array_map('intval', $terms)),
                        'include_children' => false,
                    ];
                }
            }
        }
        if (
            isset($block->context['query']['order']) &&
                in_array(strtoupper($block->context['query']['order']), ['ASC', 'DESC'], true)
        ) {
            $query['order'] = strtoupper($block->context['query']['order']);
        }
        if (isset($block->context['query']['orderBy'])) {
            $query['orderby'] = $block->context['query']['orderBy'];
        }
        if (
            isset($block->context['query']['author'])
        ) {
            if (is_array($block->context['query']['author'])) {
                $query['author__in'] = array_filter(array_map('intval', $block->context['query']['author']));
            } elseif (is_string($block->context['query']['author'])) {
                $query['author__in'] = array_filter(array_map('intval', explode(',', $block->context['query']['author'])));
            } elseif (is_int($block->context['query']['author']) && $block->context['query']['author'] > 0) {
                $query['author'] = $block->context['query']['author'];
            }
        }
        if (!empty($block->context['query']['search'])) {
            $query['s'] = $block->context['query']['search'];
        }
        if (!empty($block->context['query']['parents']) && is_post_type_hierarchical($query['post_type'])) {
            $query['post_parent__in'] = array_filter(array_map('intval', $block->context['query']['parents']));
        }
    }

    /**
     * Filters the arguments which will be passed to `WP_Query` for the Query Loop Block.
     *
     * Anything to this filter should be compatible with the `WP_Query` API to form
     * the query context which will be passed down to the Query Loop Block's children.
     * This can help, for example, to include additional settings or meta queries not
     * directly supported by the core Query Loop Block, and extend its capabilities.
     *
     * Please note that this will only influence the query that will be rendered on the
     * front-end. The editor preview is not affected by this filter. Also, worth noting
     * that the editor preview uses the REST API, so, ideally, one should aim to provide
     * attributes which are also compatible with the REST API, in order to be able to
     * implement identical queries on both sides.
     *
     * @since 6.1.0
     *
     * @param  array  $query Array containing parameters for `WP_Query` as parsed by the block context.
     * @param  WP_Block  $block Block instance.
     * @param  int  $page  Current query's page.
     */
    return apply_filters('query_loop_block_query_vars', $query, $block, $page);
}

/**
 * Helper function that returns the proper pagination arrow HTML for
 * `QueryPaginationNext` and `QueryPaginationPrevious` blocks based
 * on the provided `paginationArrow` from `QueryPagination` context.
 *
 * It's used in QueryPaginationNext and QueryPaginationPrevious blocks.
 *
 * @since 5.9.0
 *
 * @param  WP_Block  $block   Block instance.
 * @param  bool  $is_next Flag for handling `next/previous` blocks.
 * @return string|null The pagination arrow HTML or null if there is none.
 */
function get_query_pagination_arrow($block, $is_next)
{
    $arrow_map = [
        'none' => '',
        'arrow' => [
            'next' => '→',
            'previous' => '←',
        ],
        'chevron' => [
            'next' => '»',
            'previous' => '«',
        ],
    ];
    if (!empty($block->context['paginationArrow']) && array_key_exists($block->context['paginationArrow'], $arrow_map) && !empty($arrow_map[$block->context['paginationArrow']])) {
        $pagination_type = $is_next ? 'next' : 'previous';
        $arrow_attribute = $block->context['paginationArrow'];
        $arrow = $arrow_map[$block->context['paginationArrow']][$pagination_type];
        $arrow_classes = "wp-block-query-pagination-$pagination_type-arrow is-arrow-$arrow_attribute";

        return "<span class='$arrow_classes' aria-hidden='true'>$arrow</span>";
    }

    return null;
}

/**
 * Helper function that constructs a comment query vars array from the passed
 * block properties.
 *
 * It's used with the Comment Query Loop inner blocks.
 *
 * @since 6.0.0
 *
 * @param  WP_Block  $block Block instance.
 * @return array Returns the comment query parameters to use with the
 *               WP_Comment_Query constructor.
 */
function build_comment_query_vars_from_block($block)
{

    $comment_args = [
        'orderby' => 'comment_date_gmt',
        'order' => 'ASC',
        'status' => 'approve',
        'no_found_rows' => false,
    ];

    if (is_user_logged_in()) {
        $comment_args['include_unapproved'] = [get_current_user_id()];
    } else {
        $unapproved_email = wp_get_unapproved_comment_author_email();

        if ($unapproved_email) {
            $comment_args['include_unapproved'] = [$unapproved_email];
        }
    }

    if (!empty($block->context['postId'])) {
        $comment_args['post_id'] = (int) $block->context['postId'];
    }

    if (get_option('thread_comments')) {
        $comment_args['hierarchical'] = 'threaded';
    } else {
        $comment_args['hierarchical'] = false;
    }

    if (get_option('page_comments') === '1' || get_option('page_comments') === true) {
        $per_page = get_option('comments_per_page');
        $default_page = get_option('default_comments_page');
        if ($per_page > 0) {
            $comment_args['number'] = $per_page;

            $page = (int) get_query_var('cpage');
            if ($page) {
                $comment_args['paged'] = $page;
            } elseif ($default_page === 'oldest') {
                $comment_args['paged'] = 1;
            } elseif ($default_page === 'newest') {
                $max_num_pages = (int) ( new WP_Comment_Query($comment_args) )->max_num_pages;
                if ($max_num_pages !== 0) {
                    $comment_args['paged'] = $max_num_pages;
                }
            }
            // Set the `cpage` query var to ensure the previous and next pagination links are correct
            // when inheriting the Discussion Settings.
            if ($page === 0 && isset($comment_args['paged']) && $comment_args['paged'] > 0) {
                set_query_var('cpage', $comment_args['paged']);
            }
        }
    }

    return $comment_args;
}

/**
 * Helper function that returns the proper pagination arrow HTML for
 * `CommentsPaginationNext` and `CommentsPaginationPrevious` blocks based on the
 * provided `paginationArrow` from `CommentsPagination` context.
 *
 * It's used in CommentsPaginationNext and CommentsPaginationPrevious blocks.
 *
 * @since 6.0.0
 *
 * @param  WP_Block  $block           Block instance.
 * @param  string  $pagination_type Optional. Type of the arrow we will be rendering.
 *                                  Accepts 'next' or 'previous'. Default 'next'.
 * @return string|null The pagination arrow HTML or null if there is none.
 */
function get_comments_pagination_arrow($block, $pagination_type = 'next')
{
    $arrow_map = [
        'none' => '',
        'arrow' => [
            'next' => '→',
            'previous' => '←',
        ],
        'chevron' => [
            'next' => '»',
            'previous' => '«',
        ],
    ];
    if (!empty($block->context['comments/paginationArrow']) && !empty($arrow_map[$block->context['comments/paginationArrow']][$pagination_type])) {
        $arrow_attribute = $block->context['comments/paginationArrow'];
        $arrow = $arrow_map[$block->context['comments/paginationArrow']][$pagination_type];
        $arrow_classes = "wp-block-comments-pagination-$pagination_type-arrow is-arrow-$arrow_attribute";

        return "<span class='$arrow_classes' aria-hidden='true'>$arrow</span>";
    }

    return null;
}

/**
 * Strips all HTML from the content of footnotes, and sanitizes the ID.
 * This function expects slashed data on the footnotes content.
 *
 * @since 6.3.2
 *
 * @param  string  $footnotes JSON encoded string of an array containing the content and ID of each footnote.
 * @return string Filtered content without any HTML on the footnote content and with the sanitized id.
 */
function _wp_filter_post_meta_footnotes($footnotes)
{
    $footnotes_decoded = json_decode($footnotes, true);
    if (!is_array($footnotes_decoded)) {
        return '';
    }
    $footnotes_sanitized = [];
    foreach ($footnotes_decoded as $footnote) {
        if (!empty($footnote['content']) && !empty($footnote['id'])) {
            $footnotes_sanitized[] = [
                'id' => sanitize_key($footnote['id']),
                'content' => wp_unslash(wp_filter_post_kses(wp_slash($footnote['content']))),
            ];
        }
    }

    return wp_json_encode($footnotes_sanitized);
}

/**
 * Adds the filters to filter footnotes meta field.
 *
 * @since 6.3.2
 */
function _wp_footnotes_kses_init_filters()
{
    add_filter('sanitize_post_meta_footnotes', '_wp_filter_post_meta_footnotes');
}

/**
 * Removes the filters that filter footnotes meta field.
 *
 * @since 6.3.2
 */
function _wp_footnotes_remove_filters()
{
    remove_filter('sanitize_post_meta_footnotes', '_wp_filter_post_meta_footnotes');
}

/**
 * Registers the filter of footnotes meta field if the user does not have unfiltered_html capability.
 *
 * @since 6.3.2
 */
function _wp_footnotes_kses_init()
{
    _wp_footnotes_remove_filters();
    if (!current_user_can('unfiltered_html')) {
        _wp_footnotes_kses_init_filters();
    }
}

/**
 * Initializes footnotes meta field filters when imported data should be filtered.
 *
 * This filter is the last being executed on force_filtered_html_on_import.
 * If the input of the filter is true it means we are in an import situation and should
 * enable kses, independently of the user capabilities.
 * So in that case we call _wp_footnotes_kses_init_filters;
 *
 * @since 6.3.2
 *
 * @param  string  $arg Input argument of the filter.
 * @return string Input argument of the filter.
 */
function _wp_footnotes_force_filtered_html_on_import_filter($arg)
{
    // force_filtered_html_on_import is true we need to init the global styles kses filters.
    if ($arg) {
        _wp_footnotes_kses_init_filters();
    }

    return $arg;
}
