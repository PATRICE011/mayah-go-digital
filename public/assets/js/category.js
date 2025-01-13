$(document).ready(function () {
    $.ajaxSetup({
        headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") }
    });

    const placeholderImage = '/assets/img/default-placeholder.png';

    loadCategories2();

    function loadCategories2(page = 1, search = '') {
        toggleSpinner(true);
        $.ajax({
            url: `/admin/categories?page=${page}&search=${search}`,
            type: "GET",
            dataType: "json",
            success: function (response) {
                toggleSpinner(false);

                const tableBody = response.data
                    .map((category, index) => `
                        <tr>
                            <td>${(response.current_page - 1) * response.per_page + index + 1}</td>
                            <td>
                                <img src="${category.category_image ? `/assets/img/${category.category_image}` : placeholderImage}"
                                     alt="${category.category_name}" class="rounded category-image" width="45">
                            </td>
                            <td>${category.category_name}</td>
                            <td>
                                <div class="action__btn">
                                    <button class="edit" data-toggle="modal" data-target="#editModal"
                                            data-id="${category.id}" data-name="${category.category_name}"
                                            data-image="${category.category_image ? `/assets/img/${category.category_image}` : placeholderImage}">
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

                const pagination = `
                    <tr>
                        <td colspan="8">
                            <nav>
                                <ul class="pagination justify-content-end mb-0">
                                    ${response.current_page > 1
                                        ? `<li class="page-item"><a class="page-link" href="#" data-page="${response.current_page - 1}" aria-label="Previous">«</a></li>`
                                        : `<li class="page-item disabled"><a class="page-link" href="#" aria-label="Previous">«</a></li>`}
                                    ${Array.from({ length: response.last_page }, (_, i) => `
                                        <li class="page-item ${response.current_page === i + 1 ? 'active' : ''}">
                                            <a class="page-link" href="#" data-page="${i + 1}" aria-label="Page ${i + 1}">${i + 1}</a>
                                        </li>
                                    `).join("")}
                                    ${response.current_page < response.last_page
                                        ? `<li class="page-item"><a class="page-link" href="#" data-page="${response.current_page + 1}" aria-label="Next">»</a></li>`
                                        : `<li class="page-item disabled"><a class="page-link" href="#" aria-label="Next">»</a></li>`}
                                </ul>
                            </nav>
                        </td>
                    </tr>
                `;

                $("#categoryTableBody").html(tableBody + pagination);
            },
            error: function (xhr) {
                toggleSpinner(false);
                if (xhr.status === 404) {
                    toastr.error("No categories found.");
                } else if (xhr.status === 500) {
                    toastr.error("Server error occurred. Please try again later.");
                } else {
                    toastr.error("Error loading categories. Please try again.");
                }
            }
        });
    }

    $(document).on("click", ".pagination a", function (e) {
        e.preventDefault();
        let page = $(this).data("page");
        let search = $("#searchCategory").val();
        loadCategories2(page, search);
    });

    $("#searchCategory").on("keyup", function () {
        let searchValue = $(this).val();
        loadCategories2(1, searchValue);
    });

    $("#addImage").on("change", function (event) {
        let file = event.target.files[0];
        if (file && file.type.startsWith("image/")) {
            let reader = new FileReader();
            reader.onload = function () {
                $("#imagePreview").attr("src", reader.result).show();
            };
            reader.readAsDataURL(file);
        } else {
            toastr.error("Please upload a valid image.");
            $("#imagePreview").hide();
            $(this).val('');
        }
    });

    $("#addForm").on("submit", function (e) {
        e.preventDefault();

        let formData = new FormData(this);
        $.ajax({
            url: "/admin/store-categories",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    toastr.success(response.message);
                    $("#addModal").modal("hide");
                    $("#addForm")[0].reset();
                    $("#imagePreview").hide();
                    loadCategories2();
                } else {
                    toastr.error("Failed to add category.");
                }
            },
            error: function (xhr) {
                try {
                    let errors = xhr.responseJSON.errors;
                    if (errors && errors.category_name) toastr.error(errors.category_name[0]);
                    if (errors && errors.category_image) toastr.error(errors.category_image[0]);
                } catch (e) {
                    toastr.error("An unexpected error occurred. Please check the console.");
                    console.error(xhr.responseText);
                }
            }
        });
    });

    $(document).ready(function () {
        let archiveCategoryId = null; // To store the category ID to archive
    
        // Open the archive modal and set data
        $(document).on("click", ".archive", function () {
            archiveCategoryId = $(this).closest("tr").find(".edit").data("id"); // Get the category ID
            $("#archiveModal").modal("show"); // Show the modal
        });
    
        // Handle the confirm archive button click
        $("#archiveModal .btn-danger").on("click", function () {
            if (!archiveCategoryId) {
                toastr.error("Invalid category. Please try again.");
                return;
            }
    
            // Send the DELETE request
            $.ajax({
                url: `/admin/delete-category/${archiveCategoryId}`, // Adjust route as needed
                type: "DELETE",
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        toastr.success(response.message);
                        $("#archiveModal").modal("hide"); // Close the modal
                        loadCategories2(); // Reload the categories
                    } else {
                        toastr.error("Failed to archive category. Please try again.");
                    }
                },
                error: function (xhr) {
                    $("#archiveModal").modal("hide");
                    toastr.error("An error occurred while archiving the category.");
                    console.error(xhr.responseText); // Log error details
                }
            });
        });
    
        // Handle cancel button in the modal
        $("#archiveModal .btn-secondary").on("click", function () {
            archiveCategoryId = null; // Reset the archiveCategoryId
            $("#archiveModal").modal("hide");
        });
    });
    // edit 
    $(document).ready(function () {
        let editCategoryId = null; // Store the ID of the category being edited
    
        // Open the edit modal and populate current data
        $(document).on("click", ".edit", function () {
            editCategoryId = $(this).data("id"); // Get the category ID
            const categoryName = $(this).data("name"); // Get the category name
            const categoryImage = $(this).data("image"); // Get the category image
    
            // Populate the modal fields
            $("#editCategoryName").val(categoryName);
            $("#editImagePreview").attr("src", categoryImage).show();
    
            // Show the modal
            $("#editModal").modal("show");
        });
    
        // Handle the file input change for edit image preview
        $("#editImage").on("change", function (event) {
            let file = event.target.files[0];
            if (file && file.type.startsWith("image/")) {
                let reader = new FileReader();
                reader.onload = function () {
                    $("#editImagePreview").attr("src", reader.result).show();
                };
                reader.readAsDataURL(file);
            } else {
                toastr.error("Please upload a valid image.");
                $("#editImagePreview").hide();
                $(this).val(''); // Reset the input
            }
        });
    
        // Handle the edit form submission
        $("#editForm").on("submit", function (e) {
            e.preventDefault();
    
            if (!editCategoryId) {
                toastr.error("Invalid category. Please try again.");
                return;
            }
    
            let formData = new FormData(this);
            formData.append("id", editCategoryId); // Include the category ID
    
            $.ajax({
                url: `/admin/update-category/${editCategoryId}`, // Adjust route to match your backend
                type: "POST", // Use PUT if your backend requires it
                data: formData,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        toastr.success(response.message);
                        $("#editModal").modal("hide"); // Close the modal
                        $("#editForm")[0].reset(); // Reset the form
                        $("#editImagePreview").hide(); // Hide the preview
                        loadCategories2(); // Reload the categories
                    } else {
                        toastr.error("Failed to update category. Please try again.");
                    }
                },
                error: function (xhr) {
                    try {
                        let errors = xhr.responseJSON.errors;
                        if (errors && errors.category_name) toastr.error(errors.category_name[0]);
                        if (errors && errors.category_image) toastr.error(errors.category_image[0]);
                    } catch (e) {
                        toastr.error("An unexpected error occurred. Please check the console.");
                        console.error(xhr.responseText);
                    }
                }
            });
        });
    });
    
    $("#exportCategoryBtn").on("click", function () {
        window.open("/admin/print-categories");
    });

    function toggleSpinner(show) {
        if (show) {
            $("#spinner").show();
        } else {
            $("#spinner").hide();
        }
    }
});
