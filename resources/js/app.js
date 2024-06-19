import './bootstrap';
import 'jquery-ui/dist/jquery-ui';
import './settings/ajax.js';
import './settings/datatable.js';

import Alpine from 'alpinejs';

import.meta.glob([
  '../assets/images/**',
  '../assets/fonts/**',
]);

window.Alpine = Alpine;

Alpine.start();
