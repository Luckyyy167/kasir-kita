import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Global Rupiah Formatter
window.formatRupiah = function(number) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(number || 0).replace(/\s+/g, ' ');
};

Alpine.start();
