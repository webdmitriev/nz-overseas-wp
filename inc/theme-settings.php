<?php
// Добавляем в меню
function theme_settings_page() {
  // Главное меню
  add_menu_page(
    'Theme Settings',
    'Theme Settings',
    'manage_options',
    'theme-settings',
    'theme_settings_page_content',
    'dashicons-admin-tools',
    80
  );

  // Подменю - Основные настройки
  add_submenu_page(
    'theme-settings',
    'Theme Settings',
    'Settings',
    'manage_options',
    'theme-settings',
    'theme_settings_page_content'
  );

  // Подменю - Видео инструкции
  add_submenu_page(
    'theme-settings',
    'Video Instructions',
    'Video Instructions',
    'manage_options',
    'theme-instructions',
    'theme_instructions_page_content'
  );
}
add_action('admin_menu', 'theme_settings_page');

// Регистрируем настройки
function theme_settings_init() {
  // Регистрируем настройки для theme_mod
  register_setting('theme_settings_group', 'theme_settings', 'theme_settings_sanitize');
}
add_action('admin_init', 'theme_settings_init');

// Функция санитизации
function theme_settings_sanitize($input) {
  $sanitized_input = array();

  // Санитизация контактной информации
  if (isset($input['contact_info'])) {
    $sanitized_input['contact_info'] = array(
      'phone' => sanitize_text_field($input['contact_info']['phone']),
      'email' => sanitize_email($input['contact_info']['email'])
    );
  }

  // Санитизация партнеров
  if (isset($input['partners'])) {
    $sanitized_input['partners'] = array();
    foreach ($input['partners'] as $partner) {
      if (!empty($partner['text'])) {
        $sanitized_input['partners'][] = array(
          'text' => sanitize_text_field($partner['text']),
          'link' => esc_url_raw($partner['link'])
        );
      }
    }
  }

  return $sanitized_input;
}

// HTML контент страницы настроек
function theme_settings_page_content() {
  // Получаем текущие настройки
  $theme_settings = get_option('theme_settings', array());
  $contact_info = isset($theme_settings['contact_info']) ? $theme_settings['contact_info'] : array('phone' => '', 'email' => '');
  $partners = isset($theme_settings['partners']) ? $theme_settings['partners'] : array();
  ?>
  <div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

    <?php if (isset($_GET['settings-updated'])): ?>
      <div class="notice notice-success is-dismissible">
        <p>Settings saved successfully!</p>
      </div>
    <?php endif; ?>

    <form method="post" action="options.php">
      <?php settings_fields('theme_settings_group'); ?>

      <h2>Contact Information</h2>
      <table class="form-table">
        <tr>
          <th scope="row">Phone</th>
          <td>
            <input type="text" name="theme_settings[contact_info][phone]"
                  value="<?php echo esc_attr($contact_info['phone']); ?>"
                  class="regular-text" />
          </td>
        </tr>
        <tr>
          <th scope="row">Email</th>
          <td>
            <input type="email" name="theme_settings[contact_info][email]" 
                    value="<?php echo esc_attr($contact_info['email']); ?>" 
                    class="regular-text" />
          </td>
        </tr>
      </table>

      <h2>Partners</h2>
      <div id="partners-repeater">
        <?php if (!empty($partners)): ?>
          <?php foreach ($partners as $index => $partner): ?>
            <div class="partner-item" style="margin-bottom: 15px; padding: 15px; border: 1px solid #ddd;">
              <h3>Partner <?php echo $index + 1; ?></h3>
              <table class="form-table">
                <tr>
                  <th scope="row">Text</th>
                  <td>
                    <input type="text" name="theme_settings[partners][<?php echo $index; ?>][text]" 
                          value="<?php echo esc_attr($partner['text']); ?>" 
                          class="regular-text" />
                  </td>
                </tr>
                <tr>
                  <th scope="row">Link</th>
                  <td>
                    <input type="url" name="theme_settings[partners][<?php echo $index; ?>][link]" 
                          value="<?php echo esc_attr($partner['link']); ?>" 
                          class="regular-text" />
                  </td>
                </tr>
              </table>
              <button type="button" class="button remove-partner">Remove Partner</button>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

      <button type="button" id="add-partner" class="button">Add Partner</button>
      <?php submit_button('Save Settings'); ?>
      </form>
    </div>

    <script>
    jQuery(document).ready(function($) {
        let partnerIndex = <?php echo count($partners); ?>;

        $('#add-partner').on('click', function() {
            const newItem = `
                <div class="partner-item" style="margin-bottom: 15px; padding: 15px; border: 1px solid #ddd;">
                    <h3>Partner ${partnerIndex + 1}</h3>
                    <table class="form-table">
                        <tr>
                            <th scope="row">Text</th>
                            <td>
                                <input type="text" 
                                       name="theme_settings[partners][${partnerIndex}][text]" 
                                       value="" 
                                       class="regular-text" />
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Link</th>
                            <td>
                                <input type="url" 
                                       name="theme_settings[partners][${partnerIndex}][link]" 
                                       value="" 
                                       class="regular-text" />
                            </td>
                        </tr>
                    </table>
                    <button type="button" class="button remove-partner">Remove Partner</button>
                </div>
            `;
            $('#partners-repeater').append(newItem);
            partnerIndex++;
        });

        $(document).on('click', '.remove-partner', function() {
            $(this).closest('.partner-item').remove();
            // Переиндексируем оставшиеся элементы
            $('.partner-item').each(function(index) {
                $(this).find('h3').text('Partner ' + (index + 1));
                $(this).find('input[name*="[text]"]').attr('name', `theme_settings[partners][${index}][text]`);
                $(this).find('input[name*="[link]"]').attr('name', `theme_settings[partners][${index}][link]`);
            });
            partnerIndex = $('.partner-item').length;
        });
    });
    </script>
    <style>
    .partner-item {
        position: relative;
    }
    .remove-partner {
        margin-top: 10px;
    }
    </style>
    <?php
}



// Функция для страницы с видео инструкциями
function theme_instructions_page_content() {
    // Пути к видео файлам
    $videos = array(
        'main' => array(
            'title' => 'Main Setup',
            'path' => get_template_directory_uri() . '/admin/instructions/main.mov',
            'description' => 'Main block'
        ),
        'form' => array(
            'title' => 'Form Configuration',
            'path' => get_template_directory_uri() . '/admin/instructions/form.mov',
            'description' => 'Form block'
        ),
        'global' => array(
            'title' => 'Global Settings',
            'path' => get_template_directory_uri() . '/admin/instructions/global.mov',
            'description' => 'Global theme settings and options'
        ),
        'menu' => array(
            'title' => 'Menu Management',
            'path' => get_template_directory_uri() . '/admin/instructions/menu.mov',
            'description' => 'How to manage menus and navigation'
        )
    );
    ?>
    <div class="wrap theme-instructions-wrap">
        <h1>🎬 Video Instructions</h1>
        
        <div class="instructions-intro">
            <p>Welcome to the video instructions section. Here you'll find helpful tutorials on how to use various theme features.</p>
        </div>

        <div class="video-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 20px; margin-top: 30px;">
            <?php foreach ($videos as $key => $video): ?>
                <div class="video-card" style="background: #fff; border: 1px solid #ccd0d4; border-radius: 8px; padding: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                    <h3 style="margin-top: 0; color: #2271b1;"><?php echo esc_html($video['title']); ?></h3>
                    
                    <div class="video-player" style="margin: 15px 0;">
                        <video 
                            controls 
                            style="width: 100%; max-width: 100%;height: inherit;aspect-ratio: 16 / 9; border-radius: 4px;"
                            poster="<?php echo get_template_directory_uri(); ?>/admin/instructions/<?php echo $key; ?>-preview.jpg"
                        >
                            <source src="<?php echo esc_url($video['path']); ?>" type="video/mp4">
                            <source src="<?php echo esc_url($video['path']); ?>" type="video/quicktime">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                    
                    <p class="video-description" style="color: #666; font-size: 14px; line-height: 1.4;">
                        <?php echo esc_html($video['description']); ?>
                    </p>
                    
                    <!-- <div class="video-actions" style="margin-top: 15px;">
                        <a href="<?php echo esc_url($video['path']); ?>" 
                           download 
                           class="button button-secondary"
                           style="text-decoration: none;">
                            📥 Download Video
                        </a>
                    </div> -->
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (is_file(get_template_directory() . '/admin/instructions/readme.txt')): ?>
            <div class="additional-help" style="margin-top: 40px; padding: 20px; background: #f6f7f7; border-radius: 8px;">
                <h3>📋 Additional Resources</h3>
                <p>For written instructions and troubleshooting, check out our documentation:</p>
                <a href="<?php echo get_template_directory_uri() . '/admin/instructions/readme.txt'; ?>" 
                   target="_blank" 
                   class="button button-primary">
                    📖 Open Documentation
                </a>
            </div>
        <?php endif; ?>
    </div>

    <style>
    .theme-instructions-wrap {
        max-width: 1400px;
    }
    
    .instructions-intro {
        background: #f0f6ff;
        border-left: 4px solid #2271b1;
        padding: 15px 20px;
        margin: 20px 0;
        border-radius: 4px;
    }
    
    .video-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .video-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .video-player video:focus {
        outline: 2px solid #2271b1;
        outline-offset: 2px;
    }
    
    @media (max-width: 768px) {
        .video-grid {
            grid-template-columns: 1fr !important;
        }
    }
    </style>
    <?php
}



// styles
function theme_settings_enqueue_styles($hook) {
  if ($hook != 'toplevel_page_theme-settings') { return; }

  wp_enqueue_style(
    'theme-settings-css',
    get_template_directory_uri() . '/assets/scss/theme-settings.css',
    array(),
    '1.0.0'
  );
}
add_action('admin_enqueue_scripts', 'theme_settings_enqueue_styles');


// Функции для получения данных настроек
function get_theme_phone() {
    $theme_settings = get_option('theme_settings', array());
    return isset($theme_settings['contact_info']['phone']) ? $theme_settings['contact_info']['phone'] : '';
}

function get_theme_email() {
    $theme_settings = get_option('theme_settings', array());
    return isset($theme_settings['contact_info']['email']) ? $theme_settings['contact_info']['email'] : '';
}

function get_theme_partners() {
    $theme_settings = get_option('theme_settings', array());
    return isset($theme_settings['partners']) ? $theme_settings['partners'] : array();
}

// Шорткоды для вывода данных
function theme_phone_shortcode() {
    $phone = get_theme_phone();
    if ($phone) {
        return '<a href="tel:' . esc_attr($phone) . '">' . esc_html($phone) . '</a>';
    }
    return '';
}
add_shortcode('theme_phone', 'theme_phone_shortcode');

function theme_email_shortcode() {
    $email = get_theme_email();
    if ($email) {
        return '<a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a>';
    }
    return '';
}
add_shortcode('theme_email', 'theme_email_shortcode');

function theme_partners_shortcode($atts) {
    $partners = get_theme_partners();
    if (empty($partners)) {
        return '';
    }

    $output = '<div class="theme-partners">';
    foreach ($partners as $partner) {
        $output .= '<div class="partner-item">';
        if (!empty($partner['link'])) {
            $output .= '<a href="' . esc_url($partner['link']) . '" target="_blank" rel="noopener">';
        }
        $output .= esc_html($partner['text']);
        if (!empty($partner['link'])) {
            $output .= '</a>';
        }
        $output .= '</div>';
    }
    $output .= '</div>';

    return $output;
}
add_shortcode('theme_partners', 'theme_partners_shortcode');