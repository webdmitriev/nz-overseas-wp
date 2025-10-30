<?php
// Добавляем в меню
function theme_settings_page() {
  add_menu_page(
    'Theme Settings',          // Заголовок страницы
    'Theme Settings',          // Название в меню
    'manage_options',          // Права доступа
    'theme-settings',          // SLUG страницы
    'theme_settings_page_content', // Функция отображения
    'dashicons-admin-tools', // Иконка (опционально)
    80                         // Позиция в меню (опционально)
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