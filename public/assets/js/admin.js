// JavaScript to display the selected image
const addImageInput = document.getElementById("addImage");
const imagePreview = document.getElementById("imagePreview");

if (addImageInput && imagePreview) {
    addImageInput.addEventListener("change", function (event) {
        const file = event.target.files[0]; // Get the selected file
        if (file) {
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
// dashboard graphs
document.addEventListener("DOMContentLoaded", function () {
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
    const revenueCanvas = document.getElementById("revenue");
    if (revenueCanvas) {
        const revenueCtx = revenueCanvas.getContext("2d");
        new Chart(revenueCtx, {
            type: "line",
            data: {
                labels: [
                    "Monday",
                    "Tuesday",
                    "Wednesday",
                    "Thursday",
                    "Friday",
                    "Saturday",
                    "Sunday",
                ],
                datasets: [
                    {
                        label: "Current Week",
                        data: [7000, 6800, 6500, 7200, 7500, 8000, 7500],
                        borderColor: "rgba(255, 99, 132, 1)", // Vibrant red-pink
                        backgroundColor: "rgba(255, 99, 132, 0.2)", // Soft red fill
                        pointBackgroundColor: "rgba(255, 99, 132, 1)", // Red dots
                        pointBorderColor: "#fff",
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderColor: "rgba(255, 99, 132, 1)",
                        fill: true,
                        tension: 0.4,
                    },
                    {
                        label: "Previous Week",
                        data: [6900, 6400, 6100, 7000, 7200, 7700, 7400],
                        borderColor: "rgba(54, 162, 235, 1)", // Strong blue
                        backgroundColor: "rgba(54, 162, 235, 0.2)", // Light blue fill
                        pointBackgroundColor: "rgba(54, 162, 235, 1)", // Blue dots
                        pointBorderColor: "#fff",
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderColor: "rgba(54, 162, 235, 1)",
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
                        position: "top",
                    },
                },
                scales: scalesConfig("Days of the Week", "Revenue (₱)"),
            },
        });
    }

    // Total Sale Pie Chart
    const totalSaleCanvas = document.getElementById("total-sale");

    if (totalSaleCanvas && window.salesData) {
        const totalSaleCtx = totalSaleCanvas.getContext("2d");

        const labels = window.salesData.labels || [];
        const data = window.salesData.data || [];
        const colors = window.salesData.colors || [];

        new Chart(totalSaleCtx, {
            type: "pie",
            data: {
                labels: labels, // Dynamic labels from the Blade template
                datasets: [
                    {
                        data: data, // Dynamic data from the Blade template
                        backgroundColor: colors, // Dynamic colors
                        borderColor: colors, // Use the same colors for borders
                        borderWidth: 1,
                    },
                ],
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
                            const value =
                                data.datasets[tooltipItem.datasetIndex].data[
                                    tooltipItem.index
                                ];
                            return `₱${value.toFixed(2)}`; // Format tooltip as currency
                        },
                    },
                },
            },
        });
    }
});
// PRODUCTS

function getImageUrl(imagePath) {
    if (imagePath.startsWith("http") || imagePath.startsWith("/")) {
        // If it's a full URL or relative path, use it as is
        return imagePath;
    } else {
        // Otherwise, prepend the base URL
        return `${baseURL}/${imagePath}`;
    }
}

// Function to load products
$(document).ready(function () {
    /**
     * Load Products with Optional Filters
     * @param {number} page - The current page to load
     * @param {object} filters - The filters to apply (search, category, price, etc.)
     */
    function loadProducts(page = 1, filters = {}) {
        $.ajax({
            url: `/admin/products?page=${page}`,
            type: "GET",
            data: filters,
            dataType: "json",
            success: function (response) {
                // Product rows
                const tableBody = response.data
                    .map(
                        (product, index) => `
                    <tr>
                        <td>${
                            (response.current_page - 1) * response.per_page +
                            index +
                            1
                        }</td>
                        <td>
                            <div class="m-r-10">
                                <img src="/assets/img/${
                                    product.product_image
                                }" alt="${
                            product.product_name
                        }" class="rounded" width="45">
                            </div>
                        </td>
                        <td>${product.product_name}</td>
                        <td class="text-truncate" style="max-width: 200px;" title="${
                            product.product_description || "N/A"
                        }">
                            ${product.product_description || "N/A"}
                        </td>
                        <td>${
                            product.category
                                ? product.category.category_name
                                : "N/A"
                        }</td>
                        <td>₱${product.product_price}</td>
                        <td>${product.product_stocks}</td>
                        <td>${
                            product.product_stocks > 0
                                ? "Active"
                                : "Out of Stock"
                        }</td>
 <td>
                                            <div class="action__btn">
                                                <!-- EDIT BUTTON -->
                                                <button class="edit" data-toggle="modal" data-target="#editModal">
                                                    <i class="ri-mail-line"></i>
                                                </button>

                                                <!-- EDIT MODAL -->
                                                <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="editModalLabel">Edit Product</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>

                                                            <div class="modal-body">
                                                                <form id="editForm">
                                                                    <div class="form-group">
                                                                        <label for="editImage">Product Image</label>
                                                                        <input type="file" class="form-control" id="editImage" accept="image/*">
                                                                        <small class="form-text text-muted">Choose an image file to upload (e.g., JPG, PNG).</small>

                                                                        <div class="mt-3">
                                                                            <img id="imagePreview" src="" alt="Selected Image" style="max-width: 150px; display: none; border: 1px solid #ddd; padding: 5px;">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label for="editName">Product Name</label>
                                                                        <input type="text" class="form-control" id="editName" placeholder="Enter product name">
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label for="editDescription">Product Description</label>
                                                                        <textarea class="form-control" id="editDescription" rows="5" placeholder="Enter product description"></textarea>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label for="editCategory">Category</label>
                                                                        <select class="form-control" id="editCategory">
                                                                            <option value="">Biscuits</option>
                                                                            <option value="">Drinks</option>
                                                                            <option value="">School Supplies</option>
                                                                        </select>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label for="editPrice">Price</label>
                                                                        <div class="input-group">
                                                                            <span class="input-group-text">₱</span>
                                                                            <input type="number" class="form-control" id="editPrice" placeholder="Enter price" min="0" step="0.01">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label for="editStatus">Status</label>
                                                                        <select class="form-control" id="editStatus">
                                                                            <option value="active">Active</option>
                                                                            <option value="inactive">Inactive</option>
                                                                        </select>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-primary" onclick="applyFilters()">Apply Changes</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- ARCHIVE BUTTON -->
                                                <button class="archive" data-bs-toggle="modal" data-bs-target="#archiveModal">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>

                                                <!-- ARCHIVE MODAL -->
                                                <div class="modal fade" id="archiveModal" tabindex="-1" aria-labelledby="archiveModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="archiveModalLabel">Archive Item</h5>
                                                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Are you sure you want to archive this item? This action cannot be undone.
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                <button type="button" class="btn btn-danger">Archive</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                        
                    </tr>
                `
                    )
                    .join("");

                // Pagination controls
                const pagination = `
                    <tr>
                        <td colspan="9">
                            <div id="paginationLinks">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end mb-0">
                                        ${
                                            response.current_page > 1
                                                ? `<li class="page-item">
                                                    <a class="page-link" href="#" data-page="${
                                                        response.current_page -
                                                        1
                                                    }">«</a>
                                                </li>`
                                                : `<li class="page-item disabled"><a class="page-link" href="#">«</a></li>`
                                        }
                                        ${Array.from(
                                            { length: response.last_page },
                                            (_, i) => {
                                                const pageNum = i + 1;
                                                return `
                                                <li class="page-item ${
                                                    response.current_page ===
                                                    pageNum
                                                        ? "active"
                                                        : ""
                                                }">
                                                    <a class="page-link" href="#" data-page="${pageNum}">${pageNum}</a>
                                                </li>`;
                                            }
                                        ).join("")}
                                        ${
                                            response.current_page <
                                            response.last_page
                                                ? `<li class="page-item">
                                                    <a class="page-link" href="#" data-page="${
                                                        response.current_page +
                                                        1
                                                    }">»</a>
                                                </li>`
                                                : `<li class="page-item disabled"><a class="page-link" href="#">»</a></li>`
                                        }
                                    </ul>
                                </nav>
                            </div>
                        </td>
                    </tr>
                `;

                // Combine product rows and pagination into the table body
                $("#productTable tbody").html(tableBody + pagination);
            },
            error: function () {
                toastr.error("Error loading products. Please try again.");
            },
        });
    }

    // DELETE PRODUCT HANDLER
    // $(document).on("click", ".delete-product", function () {
    //     const productId = $(this).data("id");
    //     if (confirm("Are you sure you want to delete this product?")) {
    //         $.ajax({
    //             url: `/admin/products/${productId}`,
    //             type: "DELETE",
    //             headers: {
    //                 "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
    //                     "content"
    //                 ),
    //             },
    //             success: function () {
    //                 toastr.success("Product deleted successfully.");
    //                 loadProducts(); // Reload the product list
    //             },
    //             error: function () {
    //                 toastr.error("Failed to delete product. Please try again.");
    //             },
    //         });
    //     }
    // });

    /**
     * Apply Filters
     */
    function applyFilters() {
        const filters = {
            query: $("#searchInput").val(),
            category: $("#filterCategory").val(),
            minPrice: $("#minPrice").val(),
            maxPrice: $("#maxPrice").val(),
            status: $("#filterStatus").val(),
        };
        loadProducts(1, filters); // Load products with filters
    }

    /**
     * Handle Form Submission for Adding Product
     */
    $("#addProductBtn").on("click", function () {
        const formData = new FormData();
        formData.append("product_name", $("#addName").val());
        formData.append("product_description", $("#addDescription").val());
        formData.append("product_image", $("#addImage")[0].files[0]);
        formData.append("product_price", $("#addPrice").val());
        formData.append("product_stocks", $("#addStocks").val());
        formData.append("category_id", $("#addCategory").val());
        formData.append("status", $("#addStatus").val());
        formData.append("_token", $('meta[name="csrf-token"]').attr("content"));

        $.ajax({
            url: "/admin/store-products",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    toastr.success(response.message);
                    $("#addModal").modal("hide");
                    loadProducts(); // Reload products
                }
            },
            error: function (xhr) {
                const errors = xhr.responseJSON?.errors;
                if (errors) {
                    Object.values(errors).forEach((messages) => {
                        messages.forEach((msg) => toastr.error(msg));
                    });
                } else {
                    toastr.error("An unexpected error occurred.");
                }
            },
        });
    });

    // Initial product load
    loadProducts();

    // Event handlers
    $("#searchInput").on("keyup", applyFilters); // Search input
    $("#applyFiltersBtn").on("click", function () {
        applyFilters();
        $("#filterModal").modal("hide");
    });
    $(document).on("click", ".pagination .page-link", function (e) {
        e.preventDefault();
        const page = $(this).data("page");
        if (page)
            loadProducts(page, {
                query: $("#searchInput").val(),
                category: $("#filterCategory").val(),
                minPrice: $("#minPrice").val(),
                maxPrice: $("#maxPrice").val(),
                status: $("#filterStatus").val(),
            });
    });

    // Preview uploaded image
    $("#addImage").on("change", function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) =>
                $("#imagePreview").attr("src", e.target.result).show();
            reader.readAsDataURL(file);
        }
    });
});
$(document).ready(function () {
    $("#exportProductsBtn").on("click", function () {
        // Open the export page in a new tab
        window.open("/admin/products/export", "_blank");
    });
});
