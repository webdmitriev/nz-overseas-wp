import { registerBlockType } from '@wordpress/blocks';
import edit from './edit';
import save from './save';
import attributes from './attributes';

import './editor.scss';
import './style.scss';

registerBlockType('theme/advanced-block', {
  title: 'Главный блок',
  category: 'webdmitriev',
  icon: 'admin-customizer',
  description: ' ',
  attributes,
  edit,
  save,
});

console.log('✅ Главный блок');