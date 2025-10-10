import './bootstrap';
import Alpine from 'alpinejs';
import countrySelect from './countrySelect';

// Register custom Alpine components here
Alpine.data('countrySelect', countrySelect);

// Optional: global Alpine plugin pattern placeholder
// Alpine.plugin();

window.Alpine = Alpine;
Alpine.start();
