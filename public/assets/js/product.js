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
                            <img src="${getImageUrl(
                                product.product_image ||
                                    "default-placeholder.png"
                            )}"
                                 alt="${product.product_name}"
                                 class="rounded product-image" width="45">
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
                        product.product_stocks > 0 ? "Active" : "Inactive"
                    }</td>
                    <td>
                        <div class="action__btn">
                            <button class="edit" data-toggle="modal" data-target="#editModal"
                                    data-id="${product.id}"
                                    data-name="${product.product_name}"
                                    data-description="${
                                        product.product_description
                                    }"
                                    data-category="${
                                        product.category
                                            ? product.category.id
                                            : ""
                                    }"
                                    data-price="${product.product_price}"
                                    data-stocks="${product.product_stocks}" 
                                    
                                    data-image="${getImageUrl(
                                        product.product_image ||
                                            "default-placeholder.png"
                                    )}">
                                <i class="ri-pencil-line"></i>
                            </button>
                            <button class="archive" data-id="${
                                product.id
                            }" data-name="${
                    product.product_name
                }" data-bs-toggle="modal" data-bs-target="#archiveModal">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `
            )
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
    function createEditModal() {
        // Check if the modal already exists to avoid duplication
        if ($("#editModal").length === 0) {
            // Create the modal dynamically
            const modalHtml = `
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
                                <form id="editForm" method="POST" action="" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
    
                                    <input type="hidden" name="id" id="editId">
    
                                    <div class="form-group">
                                        <label for="editImage">Product Image</label>
                                        <input type="file" class="form-control" name="product_image" id="editImage" accept="image/*">
                                        <small class="form-text text-muted">Choose an image file to upload (JPG, PNG).</small>
                                        <div class="mt-3">
                                            <img id="imagePreview" src="" alt="Selected Image" style="max-width: 150px; display: none; border: 1px solid #ddd; padding: 5px;">
                                        </div>
                                    </div>
    
                                    <div class="form-group">
                                        <label for="editName">Product Name</label>
                                        <input type="text" class="form-control" name="product_name" id="editName">
                                    </div>
    
                                    <div class="form-group">
                                        <label for="editDescription">Product Description</label>
                                        <textarea class="form-control" name="product_description" id="editDescription" rows="5"></textarea>
                                    </div>
    
                                    <div class="form-group">
                                        <label for="editCategory">Category</label>
                                        <select class="form-control" name="category_id" id="editCategory">
                                            <!-- Categories will be loaded dynamically -->
                                        </select>
                                    </div>
    
                                    <div class="form-group">
                                        <label for="editPrice">Price</label>
                                        <div class="input-group">
                                            <span class="input-group-text">₱</span>
                                            <input type="number" class="form-control" name="product_price" id="editPrice" min="0" step="0.01">
                                        </div>
                                    </div>
    
                                    <div class="form-group">
                                        <label for="editStocks">Stocks</label>
                                        <input type="number" class="form-control" name="product_stocks" id="editStocks" min="0">
                                    </div>
    
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            // Append modal to the body
            $("body").append(modalHtml);
        }
    }

    function generatePagination(response) {
        return `
            <tr>
                <td colspan="9">
                    <div id="paginationLinks">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-end mb-0">
                                ${
                                    response.current_page > 1
                                        ? `<li class="page-item">
                                        <a class="page-link" href="#" data-page="${
                                            response.current_page - 1
                                        }">«</a>
                                    </li>`
                                        : `<li class="page-item disabled"><a class="page-link" href="#">«</a></li>`
                                }
                                ${Array.from(
                                    { length: response.last_page },
                                    (_, i) => `
                                    <li class="page-item ${
                                        response.current_page === i + 1
                                            ? "active"
                                            : ""
                                    }">
                                        <a class="page-link" href="#" data-page="${
                                            i + 1
                                        }">${i + 1}</a>
                                    </li>`
                                ).join("")}
                                ${
                                    response.current_page < response.last_page
                                        ? `<li class="page-item">
                                        <a class="page-link" href="#" data-page="${
                                            response.current_page + 1
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
    }

    /**
     * Load categories into the dropdown.
     */

    $(document).on("click", ".edit", function () {
        const product = $(this).data();

        // Populate form fields in the modal with product data
        $("#editProductId").val(product.id);
        $("#editProductName").val(product.name);
        $("#editProductDescription").val(product.description);
        $("#editProductPrice").val(product.price);
        $("#editProductStocks").val(product.stocks);

        // Fetch and populate categories dynamically
        $.ajax({
            url: "/admin/all-categories", // Backend route to fetch categories
            type: "GET",
            success: function (categories) {
                const categoryDropdown = $("#editCategory");
                categoryDropdown.empty(); // Clear existing options

                // Populate categories dynamically
                categories.forEach((category) => {
                    categoryDropdown.append(
                        `<option value="${category.id}" ${
                            category.id === product.category ? "selected" : ""
                        }>
                            ${category.category_name}
                        </option>`
                    );
                });
            },
            error: function () {
                toastr.error("Failed to load categories.");
            },
        });

        // Display the current image preview if it exists
        if (product.image) {
            $("#currentImagePreview").attr("src", product.image).show();
        } else {
            $("#currentImagePreview").hide();
        }

        // Show the modal
        $("#editProductModal").modal("show");
    });

    /**
     * Event listener for submitting the edit form.
     */
    $("#editProductBtn").on("click", function () {
        const formData = new FormData($("#editProductForm")[0]); // Get all form data
        const productId = $("#editProductId").val(); // Get the product ID

        $.ajax({
            url: `/admin/update-product/${productId}`, // Laravel update route
            type: "POST", // Use POST method
            data: formData,
            processData: false, // Prevent jQuery from transforming the data
            contentType: false, // Prevent jQuery from setting the content type
            success: function (response) {
                toastr.success("Product updated successfully!");
                $("#editProductModal").modal("hide"); // Close the modal
                loadProducts(); // Reload the product list
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    // Handle validation errors
                    const errors = xhr.responseJSON.errors;
                    for (const field in errors) {
                        toastr.error(errors[field][0]);
                    }
                } else {
                    toastr.error("An unexpected error occurred.");
                }
            },
        });
    });

    $(document).on("click", ".archive", function () {
        const productId = $(this).data("id");
        const productName = $(this).data("name");

        // Set the product name and ID in the modal
        $("#productToDelete").text(productName);
        $("#confirmDeleteButton").data("id", productId);

        // Show the modal
        $("#deleteConfirmationModal").modal("show");
    });
    $(document).ready(function () {
        let productIdToDelete = null;

        // Handle opening of delete confirmation modal
        $(document).on("click", ".archive", function () {
            productIdToDelete = $(this).data("id");
            const productName = $(this).data("name");

            // Update modal content
            $("#productToDelete").text(productName);

            // Show the delete confirmation modal
            $("#deleteConfirmationModal").modal("show");
        });

        // Handle the Cancel button
        $("#cancelDeleteButton").on("click", function () {
            // Clear the productIdToDelete when Cancel is clicked
            productIdToDelete = null;

            // Explicitly hide the modal (optional, as Bootstrap handles it automatically)
            $("#deleteConfirmationModal").modal("hide");
        });

        // Handle Confirm Delete button
        $("#confirmDeleteButton").on("click", function () {
            if (productIdToDelete) {
                $.ajax({
                    url: `/admin/delete-product/${productIdToDelete}`,
                    type: "DELETE",
                    success: function (response) {
                        toastr.success(response.message);
                        $("#deleteConfirmationModal").modal("hide"); // Hide modal on success
                        loadProducts(); // Reload the product list
                    },
                    error: function (xhr) {
                        toastr.error(
                            "Failed to delete the product. Please try again."
                        );
                    },
                });
            }
        });
    });

    $(document).ready(function () {
        // Add CSRF token to AJAX requests
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        $("#editForm").on("submit", function (e) {
            e.preventDefault(); // Prevent default form submission

            const formData = new FormData(this);
            formData.append("_method", "PUT"); // Use Laravel's method spoofing

            $.ajax({
                url: $(this).attr("action"), // Form action URL
                type: "POST", // Send as POST but include _method=PUT
                data: formData,
                processData: false, // Prevent jQuery from automatically transforming the data
                contentType: false, // Prevent jQuery from setting the content type
                success: function (response) {
                    toastr.success("Product updated successfully!");
                    $("#editModal").modal("hide"); // Hide modal
                    loadProducts(); // Reload product list
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        displayErrors(errors); // Display validation errors
                    } else {
                        toastr.error("An unexpected error occurred.");
                    }
                },
            });
        });

        function displayErrors(errors) {
            $(".error-message").remove(); // Clear previous errors
            for (const field in errors) {
                const errorMessage = errors[field][0];
                const input = $(`[name="${field}"]`);
                input.after(
                    `<span class="text-danger error-message">${errorMessage}</span>`
                );
            }
        }
    });

    // Image Upload Preview
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
            categoryDropdown
                .empty()
                .append('<option value="">Select Category</option>');

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
