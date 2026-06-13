import './bootstrap';
import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';

// Initialize Alpine.js
window.Alpine = Alpine;
Alpine.start();

// Initialize Charts when the DOM is loaded
document.addEventListener('DOMContentLoaded', function () {
    // 1. Monthly Events Area Chart
    const monthlyEventsCtx = document.getElementById('monthlyEventsChart');
    if (monthlyEventsCtx) {
        new Chart(monthlyEventsCtx, {
            type: 'line',
            data: {
                labels: ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN'],
                datasets: [{
                    label: 'Monthly Events',
                    data: [20, 40, 10, 70, 50, 80],
                    borderColor: '#adc6ff',
                    backgroundColor: 'rgba(173, 198, 255, 0.2)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#131315',
                    pointBorderColor: '#adc6ff',
                    pointBorderWidth: 1.5,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: '#2a2a2c',
                        titleColor: '#e5e1e4',
                        bodyColor: '#e5e1e4',
                        borderColor: '#424754',
                        borderWidth: 1
                    }
                },
                scales: {
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { color: '#8c909f', font: { family: 'JetBrains Mono', size: 10 } }
                    },
                    y: {
                        display: false,
                        min: 0,
                        max: 100
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                }
            }
        });
    }

    // 2. Attendance Trends Bar Chart
    const attendanceTrendsCtx = document.getElementById('attendanceTrendsChart');
    if (attendanceTrendsCtx) {
        new Chart(attendanceTrendsCtx, {
            type: 'bar',
            data: {
                labels: ['W1', 'W2', 'W3', 'W4', 'W5'],
                datasets: [
                    {
                        label: 'Registered',
                        data: [80, 60, 90, 75, 40],
                        backgroundColor: 'rgba(173, 198, 255, 0.2)',
                        hoverBackgroundColor: 'rgba(173, 198, 255, 0.3)',
                        borderRadius: { topLeft: 4, topRight: 4, bottomLeft: 0, bottomRight: 0 },
                        barPercentage: 0.6,
                        categoryPercentage: 0.8
                    },
                    {
                        label: 'Attended',
                        data: [60, 45, 85, 65, 35],
                        backgroundColor: '#adc6ff',
                        borderRadius: { topLeft: 4, topRight: 4, bottomLeft: 0, bottomRight: 0 },
                        barPercentage: 0.6,
                        categoryPercentage: 0.8
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#2a2a2c',
                        titleColor: '#e5e1e4',
                        bodyColor: '#e5e1e4',
                        borderColor: '#424754',
                        borderWidth: 1
                    }
                },
                scales: {
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { color: '#8c909f', font: { family: 'JetBrains Mono', size: 10 } }
                    },
                    y: {
                        display: false,
                        min: 0,
                        max: 100
                    }
                }            }
        });
    }

    // 3. Participation Growth Chart (Analytics)
    const participationGrowthCtx = document.getElementById('participationGrowthChart');
    if (participationGrowthCtx) {
        new Chart(participationGrowthCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [
                    {
                        label: 'Unique',
                        data: [15, 25, 20, 50, 40, 60],
                        borderColor: '#adc6ff',
                        backgroundColor: 'rgba(173, 198, 255, 0.2)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#131315',
                        pointBorderColor: '#adc6ff',
                        pointBorderWidth: 1.5,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    },
                    {
                        label: 'Returning',
                        data: [5, 10, 8, 20, 15, 25],
                        borderColor: '#adc6ff',
                        borderWidth: 2,
                        borderDash: [4, 4],
                        fill: false,
                        tension: 0.4,
                        pointBackgroundColor: '#131315',
                        pointBorderColor: '#adc6ff',
                        pointBorderWidth: 1.5,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: '#2a2a2c',
                        titleColor: '#e5e1e4',
                        bodyColor: '#e5e1e4',
                        borderColor: '#424754',
                        borderWidth: 1
                    }
                },
                scales: {
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { color: '#8c909f', font: { family: 'JetBrains Mono', size: 10 } }
                    },
                    y: {
                        display: false,
                        min: 0,
                        max: 80
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                }
            }
        });
    }

    // 4. Certificate Status Donut Chart (Analytics)
    const certificateStatusCtx = document.getElementById('certificateStatusChart');
    if (certificateStatusCtx) {
        new Chart(certificateStatusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Issued', 'Pending'],
                datasets: [{
                    data: [72, 28],
                    backgroundColor: ['#adc6ff', 'rgba(66, 71, 84, 0.3)'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '80%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#2a2a2c',
                        titleColor: '#e5e1e4',
                        bodyColor: '#e5e1e4',
                        borderColor: '#424754',
                        borderWidth: 1
                    }
                }
            }
        });
    }
});
