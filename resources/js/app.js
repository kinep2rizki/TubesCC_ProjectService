import './bootstrap';
import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';

// Initialize Alpine.js
window.Alpine = Alpine;
Alpine.start();

// Expose Chart.js globally for inline scripts
window.Chart = Chart;

// Initialize Charts when the DOM is loaded
document.addEventListener('DOMContentLoaded', function () {
    // 1. Monthly Events Area Chart (Handled dynamically in Dashboard.blade.php)

    // 2. Attendance Trends Bar Chart (Handled dynamically in Dashboard.blade.php)

    // 3. Participation Growth Chart (Analytics) - Handled dynamically in Analytics.blade.php

    // 4. Certificate Status Donut Chart (Analytics) - Handled dynamically in Analytics.blade.php
});
