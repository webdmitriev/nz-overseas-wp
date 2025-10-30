import { addFilter } from '@wordpress/hooks';
import { __ } from '@wordpress/i18n';

// Добавляем новую категорию "WebDmitriev Blocks"
addFilter(
  'blocks.registerCategory',
  'theme/register-custom-category',
  (categories, _) => {
    if (!categories.some((cat) => cat.slug === 'webdmitriev')) {
      return [
        ...categories,
        {
          slug: 'webdmitriev',
          title: __('NZ OVERSEAS Blocks', 'theme'),
          icon: 'star-filled',
        },
      ];
    }
    return categories;
  }
);

// import blocks
import './blocks/main';
import './blocks/form';