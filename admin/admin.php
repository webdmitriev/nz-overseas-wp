<?php

/**
 * Регистрация блоков Гутенберга и их ассетов
 */
function theme_blocks_assets() {
  // Регистрируем скрипты
  wp_register_script(
    'theme-blocks',
    get_template_directory_uri() . '/build/index.js',
    array('wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n'),
    '1.0.0',
    true
  );

  // ✅ Добавляем локализацию (глобальные переменные JS)
  wp_localize_script('theme-blocks', 'themeData', array(
    'siteUrl'   => get_site_url(),
    'uploadUrl' => get_template_directory_uri() . '/admin/instructions', // Путь к папке загрузок темы
  ));

  // Регистрируем стили редактора
  wp_register_style(
    'theme-blocks-editor',
    get_template_directory_uri() . '/build/index.css',
    array('wp-edit-blocks'),
    '1.0.0'
  );

  // Регистрируем блоки
  register_block_type('theme/simple-block', array(
    'editor_script' => 'theme-blocks',
    'editor_style'  => 'theme-blocks-editor',
  ));
}
add_action('init', 'theme_blocks_assets');