$(document).ready(function () {
    // ✅ Set CSRF Token Globally
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        }
    });

    loadCategories2(); // Load categories on page load

    function loadCategories2(page = 1, search = '') {
        $.ajax({
            url: `/admin/categories?page=${page}&search=${search}`,
            type: "GET",
            dataType: "json",
            success: function (response) {
                const tableBody = response.data
                    .map((category, index) => `
                        <tr>
                            <td>${(response.current_page - 1) * response.per_page + index + 1}</td>
                            <td>
                                <div class="m-r-10">
                                    <img src="${category.category_image ? `/assets/img/${category.category_image}` : '/assets/img/default-placeholder.png'}"
                                         alt="${category.category_name}"
                                         class="rounded category-image" width="45">
                                </div>
                            </td>
                            <td>${category.category_name}</td>
                            <td>
                                <div class="action__btn">
                                    <!-- EDIT BUTTON -->
                                    <button class="edit" data-toggle="modal" data-target="#editModal"
                                            data-id="${category.id}"
                                            data-name="${category.category_name}"
                                            data-image="${category.category_image ? `/assets/img/${category.category_image}` : '/assets/img/default-placeholder.png'}">
                                        <i class="ri-pencil-line"></i>
                                    </button>

                                    <!-- ARCHIVE BUTTON -->
                                    <button class="archive" data-bs-toggle="modal" data-bs-target="#archiveModal">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `)
                    .join("");

                // Pagination controls
                const pagination = `
                    <tr>
                        <td colspan="8">
                            <div id="paginationLinks">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end mb-0">
                                        ${response.current_page > 1
                                            ? `<li class="page-item">
                                                <a class="page-link" href="#" data-page="${response.current_page - 1}" data-search="${search}">«</a>
                                            </li>`
                                            : `<li class="page-item disabled"><a class="page-link" href="#">«</a></li>`
                                        }
                                        ${Array.from({ length: response.last_page }, (_, i) => {
                                            const pageNum = i + 1;
                                            return `
                                                <li class="page-item ${response.current_page === pageNum ? 'active' : ''}">
                                                    <a class="page-link" href="#" data-page="${pageNum}" data-search="${search}">${pageNum}</a>
                                                </li>`;
                                        }).join("")}
                                        ${response.current_page < response.last_page
                                            ? `<li class="page-item">
                                                <a class="page-link" href="#" data-page="${response.current_page + 1}" data-search="${search}">»</a>
                                            </li>`
                                            : `<li class="page-item disabled"><a class="page-link" href="#">»</a></li>`
                                        }
                                    </ul>
                                </nav>
                            </div>
                        </td>
                    </tr>
                `;

                // Combine category rows and pagination into the table body
                $("#categoryTableBody").html(tableBody + pagination);
            },
            error: function () {
                toastr.error("Error loading categories. Please try again.");
            },
        });
    }

    // Handle pagination clicks dynamically (keep search filters)
    $(document).on("click", ".pagination a", function (e) {
        e.preventDefault();
        let page = $(this).data("page");
        let search = $("#searchCategory").val(); // Get the search value
        loadCategories(page, search);
    });

    // Handle search input
    $("#searchCategory").on("keyup", function () {
        let searchValue = $(this).val();
        loadCategories(1, searchValue); // Reload categories on search
    });

    // Image preview on file select
    $("#addImage").on("change", function (event) {
        let reader = new FileReader();
        reader.onload = function () {
            $("#imagePreview").attr("src", reader.result).show();
        };
        reader.readAsDataURL(event.target.files[0]);
    });

    // ✅ Handle Add Category Form Submission
    $("#addForm").on("submit", function (e) {
        e.preventDefault();

        let formData = new FormData(this); // ✅ Automatically includes all form fields

        $.ajax({
            url: "/admin/store-categories", // ✅ Ensure this matches Laravel route
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
                    loadCategories(); // ✅ Reload category list
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
});
