import './bootstrap';

import $ from 'jquery';
window.$ = window.jQuery = $;

import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

import 'datatables.net-bs5';

// Initialisation par défaut des tableaux (utilisé dans les vues liste :
// véhicules, chauffeurs, affectations, entretiens, pleins)
window.initDataTable = function (selector, options = {}) {
    return $(selector).DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/2.1.4/i18n/fr-FR.json',
        },
        ...options,
    });
};

// Auto-fermeture des alertes flash après 5s
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.alert-auto-dismiss').forEach((el) => {
        setTimeout(() => bootstrap.Alert.getOrCreateInstance(el)?.close(), 5000);
    });
});
