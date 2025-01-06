$(document).ready(function () {
    // Base URL (modify if needed)
    const baseURL = "/assets/img";

    /** 
     * Get the correct image URL, handling both relative and full URLs.
     * @param {string} imagePath - The image path from the database
     * @returns {string} - Full image URL
     */
    function getImageUrl(imagePath) {
        return imagePath.startsWith("http") || imagePath.startsWith("/")
            ? imagePath
            : `${baseURL}/${imagePath}`;
    }

    /**
     * Load products into the table with optional filters and pagination.
     * @param {number} page - The current page number
     * @param {object} filters - Filtering options (search, category, etc.)
     */
    function loadProducts(page = 1, filters = {}) {
        $.ajax({
            url: `/admin/products?page=${page}`,
            type: "GET",
            data: filters,
            dataType: "json",
            success: function (response) {
                renderProducts(response);
            },
            error: function () {
                toastr.error("Error loading products. Please try again.");
            },
        });
    }

    /**
     * Render products and pagination controls.
     * @param {object} response - The response object containing product data
     */
    function renderProducts(response) {
        const tableBody = response.data
            .map((product, index) => `
                <tr>
                    <td>${(response.current_page - 1) * response.per_page + index + 1}</td>
                    <td>
                        <div class="m-r-10">
                            <img src="${getImageUrl(product.product_image || 'default-placeholder.png')}"
                                 alt="${product.product_name}"
                                 class="rounded product-image" width="45">
                        </div>
                    </td>
                    <td>${product.product_name}</td>
                    <td class="text-truncate" style="max-width: 200px;" title="${product.product_description || 'N/A'}">
                        ${product.product_description || 'N/A'}
                    </td>
                    <td>${product.category ? product.category.category_name : 'N/A'}</td>
                    <td>₱${product.product_price}</td>
                    <td>${product.product_stocks}</td>
                    <td>${product.product_stocks > 0 ? 'Active' : 'Inactive'}</td>
                    <td>
                        <div class="action__btn">
                            <button class="edit" data-toggle="modal" data-target="#editModal"
                                    data-id="${product.id}"
                                    data-name="${product.product_name}"
                                    data-description="${product.product_description}"
                                    data-category="${product.category ? product.category.id : ''}"
                                    data-price="${product.product_price}"
                                    data-status="${product.product_stocks > 0 ? 'active' : 'inactive'}"
                                    data-image="${getImageUrl(product.product_image || 'default-placeholder.png')}">
                                <i class="ri-pencil-line"></i>
                            </button>
                            <button class="archive" data-bs-toggle="modal" data-bs-target="#archiveModal">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `)
            .join("");

        // Pagination controls
        const pagination = generatePagination(response);
        $("#productTable tbody").html(tableBody + pagination);
    }

    /**
     * Generate pagination links dynamically.
     * @param {object} response - The pagination data
     * @returns {string} - The HTML for pagination links
     */
    function generatePagination(response) {
        return `
            <tr>
                <td colspan="9">
                    <div id="paginationLinks">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-end mb-0">
                                ${response.current_page > 1
                                    ? `<li class="page-item">
                                        <a class="page-link" href="#" data-page="${response.current_page - 1}">«</a>
                                    </li>`
                                    : `<li class="page-item disabled"><a class="page-link" href="#">«</a></li>`}
                                ${Array.from({ length: response.last_page }, (_, i) => `
                                    <li class="page-item ${response.current_page === i + 1 ? 'active' : ''}">
                                        <a class="page-link" href="#" data-page="${i + 1}">${i + 1}</a>
                                    </li>`).join("")}
                                ${response.current_page < response.last_page
                                    ? `<li class="page-item">
                                        <a class="page-link" href="#" data-page="${response.current_page + 1}">»</a>
                                    </li>`
                                    : `<li class="page-item disabled"><a class="page-link" href="#">»</a></li>`}
                            </ul>
                        </nav>
                    </div>
                </td>
            </tr>
        `;
    }

    /**
     * Load categories into the dropdown.
     */
    

    /**
     * Handle the click event on the edit button.
     */
    $(document).on("click", ".edit", function () {
        const productData = $(this).data();

        $("#editId").val(productData.id);
        $("#editName").val(productData.name);
        $("#editDescription").val(productData.description);
        $("#editCategory").val(productData.category);
        $("#editPrice").val(productData.price);
        $("#editStatus").val(productData.status);

        $("#imagePreview").attr("src", productData.image).css({
            display: "block",
            border: "1px solid #ddd",
            padding: "5px",
            maxWidth: "150px"
        });

        $("#editModal").modal("show");
    });

    /**
     * Handle the image upload preview in edit modal.
     */
    $("#editImage").on("change", function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                $("#imagePreview").attr("src", e.target.result).show();
            };
            reader.readAsDataURL(file);
        }
    });

    /**
     * Export Products Button Click Event
     */
    $("#exportProductsBtn").on("click", function () {
        window.open("/admin/products/export", "_blank");
    });

    /**
     * Apply search and filter parameters.
     */
    function applyFilters() {
        const filters = {
            query: $("#searchInput").val(),
            category: $("#filterCategory").val(),
            minPrice: $("#minPrice").val(),
            maxPrice: $("#maxPrice").val(),
            status: $("#filterStatus").val(),
        };
        loadProducts(1, filters);
    }

    /**
     * Handle Adding Product
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
                    loadProducts();
                }
            },
            error: function (xhr) {
                toastr.error("An unexpected error occurred.");
            },
        });
    });

    // Event Listeners
    $("#searchInput").on("keyup", applyFilters);
    $("#applyFiltersBtn").on("click", () => {
        applyFilters();
        $("#filterModal").modal("hide");
    });
    $(document).on("click", ".pagination .page-link", function (e) {
        e.preventDefault();
        loadProducts($(this).data("page"));
    });

    // Initialize
    loadProducts();
   
});
function loadCategories() {
    $.ajax({
        url: "/admin/all-categories",
        type: "GET",
        success: function (categories) {
            const categoryDropdown = $("#addCategory");
            categoryDropdown.empty().append('<option value="">Select Category</option>');

            categories.forEach((category) => {
                categoryDropdown.append(
                    `<option value="${category.id}">${category.category_name}</option>`
                );
            });
        },
        error: function () {
            toastr.error("Failed to load categories.");
        },
    });
}

$(document).ready(function () {
    // Ensure categories load when the page is ready
    loadCategories();
    
    /**
     * Handle pagination click
     */
    $(document).on("click", ".pagination .page-link", function (e) {
        e.preventDefault();
        const page = $(this).data("page");
        if (page) {
            loadProducts(page); // Ensure this function is also globally available
            loadCategories(); // Ensure categories reload on pagination change
        }
    });
});