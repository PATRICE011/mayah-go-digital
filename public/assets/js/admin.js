// JavaScript to display the selected image
const addImageInput = document.getElementById("addImage");
const imagePreview = document.getElementById("imagePreview");

if (addImageInput && imagePreview) {
    addImageInput.addEventListener("change", function (event) {
        const file = event.target.files[0]; // Get the selected file
        if (file) {
            // Validate file type
            if (!file.type.startsWith("image/")) {
                alert("Please select a valid image file.");
                return;
            }

            // Validate file size (2MB limit)
            if (file.size > 2 * 1024 * 1024) {
                alert("Image size must be less than 2MB.");
                return;
            }

            const reader = new FileReader(); // Create a FileReader to read the file
            reader.onload = function (e) {
                imagePreview.src = e.target.result; // Set the image source to the file content
                imagePreview.style.display = "block"; // Show the image
            };
            reader.readAsDataURL(file); // Read the file as a data URL
        } else {
            imagePreview.style.display = "none"; // Hide the image if no file is selected
        }
    });
}

jQuery(document).ready(function ($) {
    "use strict";

    // Notification list scroll
    if ($(".notification-list").length) {
        $(".notification-list").slimScroll({
            height: "250px",
        });
    }

    // Menu list scroll
    if ($(".menu-list").length) {
        $(".menu-list").slimScroll();
    }

    // Sidebar navigation scroll
    if ($(".sidebar-nav-fixed a").length) {
        $(".sidebar-nav-fixed a").click(function (event) {
            const target = $(this.hash);
            if (
                location.pathname.replace(/^\//, "") ===
                    this.pathname.replace(/^\//, "") &&
                location.hostname === this.hostname &&
                target.length
            ) {
                event.preventDefault();
                $("html, body").animate(
                    { scrollTop: target.offset().top - 90 },
                    1000,
                    function () {
                        target.focus();
                        if (!target.is(":focus")) {
                            target.attr("tabindex", "-1"); // Add tabindex if not focusable
                            target.focus();
                        }
                    }
                );
            }
            $(".sidebar-nav-fixed a").removeClass("active");
            $(this).addClass("active");
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
    if ($(".chat-list").length) {
        $(".chat-list").slimScroll({
            width: "100%",
        });
    }

    // Location map setup
    if ($("#locationmap").length) {
        $("#locationmap").vectorMap({
            map: "world_mill_en",
            backgroundColor: "transparent",
            zoomOnScroll: false,
            regionStyle: {
                initial: {
                    fill: "#e3eaef",
                },
            },
            markerStyle: {
                initial: {
                    r: 9,
                    fill: "#25d5f2",
                    "fill-opacity": 0.9,
                    stroke: "#fff",
                    "stroke-width": 7,
                    "stroke-opacity": 0.4,
                },
                hover: {
                    "fill-opacity": 1,
                    stroke: "#fff",
                    "stroke-width": 1.5,
                },
            },
            markers: [
                { latLng: [40.71, -74], name: "New York" },
                { latLng: [37.77, -122.41], name: "San Francisco" },
                { latLng: [-33.86, 151.2], name: "Sydney" },
                { latLng: [1.3, 103.8], name: "Singapore" },
            ],
            onRegionClick: function (element, code, region) {
                alert(
                    `You clicked "${region}" which has the code: ${code.toUpperCase()}`
                );
            },
        });
    }

    // Revenue sparkline charts
    const sparklineOptions = [
        {
            selector: "#sparkline-1",
            data: [5, 5, 7, 7, 9, 5, 3, 5, 2, 4, 6, 7],
            lineColor: "#5969ff",
            fillColor: "#dbdeff",
        },
        {
            selector: "#sparkline-2",
            data: [3, 7, 6, 4, 5, 4, 3, 5, 5, 2, 3, 1],
            lineColor: "#ff407b",
            fillColor: "#ffdbe6",
        },
        {
            selector: "#sparkline-3",
            data: [5, 3, 4, 6, 5, 7, 9, 4, 3, 5, 6, 1],
            lineColor: "#25d5f2",
            fillColor: "#dffaff",
        },
        {
            selector: "#sparkline-4",
            data: [6, 5, 3, 4, 2, 5, 3, 8, 6, 4, 5, 1],
            lineColor: "#fec957",
            fillColor: "#fff2d5",
        },
    ];

    sparklineOptions.forEach((option) => {
        if ($(option.selector).length) {
            $(option.selector).sparkline(option.data, {
                type: "line",
                width: "99.5%",
                height: "100",
                lineColor: option.lineColor,
                fillColor: option.fillColor,
                lineWidth: 2,
                resize: true,
            });
        }
    });
});

// Dashboard graphs
document.addEventListener("DOMContentLoaded", function () {
    "use strict";

   // Revenue Chart
const revenueCanvas = document.getElementById("revenue");
const revenueData = window.revenueData || { 
    currentWeekRevenue: [0, 0, 0, 0, 0, 0, 0], 
    previousWeekRevenue: [0, 0, 0, 0, 0, 0, 0] 
};

if (revenueCanvas) {
    const revenueCtx = revenueCanvas.getContext("2d");

    // Debugging: Check the data being passed
    console.log("Current Week Revenue:", revenueData.currentWeekRevenue);
    console.log("Previous Week Revenue:", revenueData.previousWeekRevenue);

    new Chart(revenueCtx, {
        type: "line",
        data: {
            labels: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
            datasets: [
                {
                    label: "Current Week",
                    data: revenueData.currentWeekRevenue,
                    borderColor: "rgba(255, 99, 132, 1)",
                    backgroundColor: "rgba(255, 99, 132, 0.2)",
                    pointBackgroundColor: "rgba(255, 99, 132, 1)",
                    fill: true,
                    tension: 0.4,
                },
                {
                    label: "Previous Week",
                    data: revenueData.previousWeekRevenue,
                    borderColor: "rgba(54, 162, 235, 1)",
                    backgroundColor: "rgba(54, 162, 235, 0.2)",
                    pointBackgroundColor: "rgba(54, 162, 235, 1)",
                    fill: true,
                    tension: 0.4,
                },
            ],
        },
        options: {
            plugins: {
                legend: {
                    display: true,
                    position: "top",
                },
            },
            scales: {
                x: {
                    title: { display: true, text: "Days of the Week" },
                },
                y: {
                    title: { display: true, text: "Revenue (₱)" },
                    beginAtZero: true,
                },
            },
        },
    });
}


    // Total Sales Pie Chart
    const totalSaleCanvas = document.getElementById("total-sale");
    if (totalSaleCanvas && window.salesData) {
        const totalSaleCtx = totalSaleCanvas.getContext("2d");

        new Chart(totalSaleCtx, {
            type: "pie",
            data: {
                labels: window.salesData.labels || [],
                datasets: [
                    {
                        data: window.salesData.data || [],
                        backgroundColor: window.salesData.colors || [],
                    },
                ],
            },
            options: {
                plugins: { legend: { display: false } },
            },
        });
    }
});
