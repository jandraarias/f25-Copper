import './bootstrap';

import Alpine from 'alpinejs';
import countrySelect from './countrySelect';

window.Alpine = Alpine;

Alpine.data('countrySelect', countrySelect);
Alpine.start();
