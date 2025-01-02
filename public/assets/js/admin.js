// JavaScript to display the selected image
const addImageInput = document.getElementById('addImage');
const imagePreview = document.getElementById('imagePreview');

if (addImageInput && imagePreview) {
    addImageInput.addEventListener('change', function (event) {
        const file = event.target.files[0]; // Get the selected file
        if (file) {
            const reader = new FileReader(); // Create a FileReader to read the file
            reader.onload = function (e) {
                imagePreview.src = e.target.result; // Set the image source to the file content
                imagePreview.style.display = 'block'; // Show the image
            };
            reader.readAsDataURL(file); // Read the file as a data URL
        } else {
            imagePreview.style.display = 'none'; // Hide the image if no file is selected
        }
    });
}

jQuery(document).ready(function ($) {
    'use strict';

    // Notification list scroll
    if ($(".notification-list").length) {
        $('.notification-list').slimScroll({
            height: '250px'
        });
    }

    // Menu list scroll
    if ($(".menu-list").length) {
        $('.menu-list').slimScroll();
    }

    // Sidebar navigation scroll
    if ($(".sidebar-nav-fixed a").length) {
        $('.sidebar-nav-fixed a').click(function (event) {
            const target = $(this.hash);
            if (
                location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') &&
                location.hostname === this.hostname &&
                target.length
            ) {
                event.preventDefault();
                $('html, body').animate(
                    { scrollTop: target.offset().top - 90 },
                    1000,
                    function () {
                        target.focus();
                        if (!target.is(":focus")) {
                            target.attr('tabindex', '-1'); // Add tabindex if not focusable
                            target.focus();
                        }
                    }
                );
            }
            $('.sidebar-nav-fixed a').removeClass('active');
            $(this).addClass('active');
        });
    }

    // Tooltips
    if ($('[data-toggle="tooltip"]').length) {
        $('[data-toggle="tooltip"]').tooltip();
    }

    // Popovers
    if ($('[data-toggle="popover"]').length) {
        $('[data-toggle="popover"]').popover();
    }

    // Chat list scroll
    if ($('.chat-list').length) {
        $('.chat-list').slimScroll({
            width: '100%'
        });
    }

    // Location map setup
    if ($('#locationmap').length) {
        $('#locationmap').vectorMap({
            map: 'world_mill_en',
            backgroundColor: 'transparent',
            zoomOnScroll: false,
            regionStyle: {
                initial: {
                    fill: "#e3eaef"
                }
            },
            markerStyle: {
                initial: {
                    r: 9,
                    fill: "#25d5f2",
                    "fill-opacity": 0.9,
                    stroke: "#fff",
                    "stroke-width": 7,
                    "stroke-opacity": 0.4
                },
                hover: {
                    "fill-opacity": 1,
                    stroke: "#fff",
                    "stroke-width": 1.5
                }
            },
            markers: [
                { latLng: [40.71, -74], name: "New York" },
                { latLng: [37.77, -122.41], name: "San Francisco" },
                { latLng: [-33.86, 151.2], name: "Sydney" },
                { latLng: [1.3, 103.8], name: "Singapore" }
            ],
            onRegionClick: function (element, code, region) {
                alert(`You clicked "${region}" which has the code: ${code.toUpperCase()}`);
            }
        });
    }

    // Revenue sparkline charts
    const sparklineOptions = [
        { selector: "#sparkline-1", data: [5, 5, 7, 7, 9, 5, 3, 5, 2, 4, 6, 7], lineColor: "#5969ff", fillColor: "#dbdeff" },
        { selector: "#sparkline-2", data: [3, 7, 6, 4, 5, 4, 3, 5, 5, 2, 3, 1], lineColor: "#ff407b", fillColor: "#ffdbe6" },
        { selector: "#sparkline-3", data: [5, 3, 4, 6, 5, 7, 9, 4, 3, 5, 6, 1], lineColor: "#25d5f2", fillColor: "#dffaff" },
        { selector: "#sparkline-4", data: [6, 5, 3, 4, 2, 5, 3, 8, 6, 4, 5, 1], lineColor: "#fec957", fillColor: "#fff2d5" },
    ];

    sparklineOptions.forEach(option => {
        if ($(option.selector).length) {
            $(option.selector).sparkline(option.data, {
                type: 'line',
                width: '99.5%',
                height: '100',
                lineColor: option.lineColor,
                fillColor: option.fillColor,
                lineWidth: 2,
                resize: true
            });
        }
    });
});
// dashboard graphs
document.addEventListener("DOMContentLoaded", function() {
    // Define common chart options
    const defaultOptions = {
        responsive: true,
        plugins: {
            legend: {
                display: false, // Disable legend for both charts
            },
        },
    };

    // Define scales configuration for charts that need axes
    const scalesConfig = (xLabel, yLabel) => ({
        x: {
            title: {
                display: true,
                text: xLabel,
            },
        },
        y: {
            title: {
                display: true,
                text: yLabel,
            },
            beginAtZero: true,
        },
    });

    // Revenue Chart (Line Chart)
    const revenueCanvas = document.getElementById('revenue');
    if (revenueCanvas) {
        const revenueCtx = revenueCanvas.getContext('2d');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
                datasets: [{
                        label: 'Current Week',
                        data: [7000, 6800, 6500, 7200, 7500, 8000, 7500],
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        fill: true,
                        tension: 0.4,
                    },
                    {
                        label: 'Previous Week',
                        data: [6900, 6400, 6100, 7000, 7200, 7700, 7400],
                        borderColor: 'rgba(201, 203, 207, 1)',
                        backgroundColor: 'rgba(201, 203, 207, 0.2)',
                        fill: true,
                        tension: 0.4,
                    },
                ],
            },
            options: {
                ...defaultOptions,
                plugins: {
                    ...defaultOptions.plugins,
                    legend: {
                        display: true, // Enable legend for the revenue chart
                        position: 'top',
                    },
                },
                scales: scalesConfig('Days of the Week', 'Revenue (₱)'),
            },
        });
    }

    // Total Sale Pie Chart
    const totalSaleCanvas = document.getElementById('total-sale');
    
        if (totalSaleCanvas && window.salesData) {
            const totalSaleCtx = totalSaleCanvas.getContext('2d');
    
            const labels = window.salesData.labels || [];
            const data = window.salesData.data || [];
            const colors = window.salesData.colors || [];
    
            new Chart(totalSaleCtx, {
                type: 'pie',
                data: {
                    labels: labels, // Dynamic labels from the Blade template
                    datasets: [{
                        data: data, // Dynamic data from the Blade template
                        backgroundColor: colors, // Dynamic colors
                        borderColor: colors, // Use the same colors for borders
                        borderWidth: 1,
                    }],
                },
                options: {
                    plugins: {
                        legend: {
                            display: false, // Disable the legend
                        },
                    },
                    tooltips: {
                        callbacks: {
                            label: function (tooltipItem, data) {
                                const value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                                return `₱${value.toFixed(2)}`; // Format tooltip as currency
                            },
                        },
                    },
                },
            });
        }
});