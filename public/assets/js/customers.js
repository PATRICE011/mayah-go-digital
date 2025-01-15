$(document).ready(function () {
    // Set up CSRF token for AJAX
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    // Variables for storing IDs
    let employeeIdToDelete = null;
    let employeeIdToEdit = null;

    // Load employees on page load
    loadEmployees();

    /**
     * Fetch and display employees
     */
    function loadEmployees(page = 1, search = "") {
        $.ajax({
            url: `/admin/customers?page=${page}&search=${encodeURIComponent(search)}`,
            type: "GET",
            dataType: "json",
            success: function (response) {
                renderEmployees(response);
            },
            error: function (xhr) {
                console.error("Error response:", xhr.responseText);
                toastr.error("Error loading employees. Please try again.");
            },
        });
    }

    /**
     * Render employees and pagination
     */
    function renderEmployees(response) {
        const tableBody = response.data
            .map(
                (employee, index) => `
                    <tr>
                        <td>${(response.current_page - 1) * response.per_page + index + 1}</td>
                        <td>123456</td>
                        <td>${employee.name}</td>
                        <td>${employee.mobile}</td>
                        <td>
                            <div class="action__btn">
                                <button class="edit" data-id="${employee.id}" data-name="${employee.name}" 
                                    data-mobile="${employee.mobile}" data-toggle="modal" data-target="#editModal">
                                    <i class="ri-pencil-line"></i>
                                </button>
                                <button class="archive" data-id="${employee.id}" data-toggle="modal" 
                                    data-target="#archiveModal">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `
            )
            .join("");

        const pagination = generatePagination(response);
        $("#employeeTableBody").html(tableBody);
        $("#paginationContainer").html(pagination);
    }

    /**
     * Generate pagination
     */
    function generatePagination(response) {
        return `
            <nav>
                <ul class="pagination justify-content-end mb-0">
                    ${
                        response.current_page > 1
                            ? `<li class="page-item"><a class="page-link" href="#" data-page="${response.current_page - 1}">«</a></li>`
                            : `<li class="page-item disabled"><a class="page-link">«</a></li>`
                    }
                    ${Array.from({ length: response.last_page }, (_, i) => `
                        <li class="page-item ${response.current_page === i + 1 ? "active" : ""}">
                            <a class="page-link" href="#" data-page="${i + 1}">${i + 1}</a>
                        </li>
                    `).join("")}
                    ${
                        response.current_page < response.last_page
                            ? `<li class="page-item"><a class="page-link" href="#" data-page="${response.current_page + 1}">»</a></li>`
                            : `<li class="page-item disabled"><a class="page-link">»</a></li>`
                    }
                </ul>
            </nav>
        `;
    }

    /**
     * Handle pagination click
     */
    $(document).on("click", ".pagination a", function (e) {
        e.preventDefault();
        const page = $(this).data("page");
        const search = $("#searchInput").val();
        loadEmployees(page, search);
    });

    /**
     * Handle search input
     */
    $("#searchInput").on("keyup", function () {
        const searchQuery = $(this).val().trim();
        loadEmployees(1, searchQuery);
    });

    /**
     * Handle add employee form submission
     */
    $("#addForm").on("submit", function (e) {
        e.preventDefault();
        const formData = new FormData(this);

        $.ajax({
            url: "/admin/employees/store",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    toastr.success("Employee added successfully!");
                    $("#addModal").modal("hide");
                    loadEmployees();
                } else {
                    toastr.error("Failed to add employee.");
                }
            },
            error: function (xhr) {
                toastr.error("Error adding employee: " + xhr.responseJSON.message);
            },
        });
    });

    /**
     * Show delete confirmation modal
     */
    $(document).on("click", ".archive", function () {
        employeeIdToDelete = $(this).data("id");
        $("#archiveModal").modal("show");
    });

    /**
     * Handle employee deletion
     */
    $("#archiveModal .btn-danger").on("click", function () {
        if (employeeIdToDelete) {
            $.ajax({
                url: `/admin/customers/delete/${employeeIdToDelete}`,
                type: "DELETE",
                success: function (response) {
                    if (response.success) {
                        toastr.success("Employee deleted successfully.");
                        $("#archiveModal").modal("hide");
                        loadEmployees();
                    } else {
                        toastr.error("Failed to delete employee.");
                    }
                },
                error: function () {
                    toastr.error("Error deleting employee. Please try again.");
                },
            });
            employeeIdToDelete = null;
        }
    });

    /**
     * Handle delete cancel action
     */
    $("#archiveModal .btn-secondary").on("click", function () {
        employeeIdToDelete = null;
        $("#archiveModal").modal("hide");
    });

    /**
     * Show edit modal and populate fields
     */
    $(document).on("click", ".edit", function () {
        employeeIdToEdit = $(this).data("id");
        $("#editEmployeeName").val($(this).data("name"));
        $("#editPhoneNumber").val($(this).data("mobile"));
        $("#editModal").modal("show");
    });

    /**
     * Handle update employee details
     */
    $("#applyChangesButton").on("click", function () {
        const updatedEmployeeData = {
            name: $("#editEmployeeName").val(),
            mobile: $("#editPhoneNumber").val(),
        };

        if (employeeIdToEdit) {
            $.ajax({
                url: `/admin/customers/update/${employeeIdToEdit}`,
                type: "PUT",
                data: updatedEmployeeData,
                success: function (response) {
                    if (response.success) {
                        toastr.success("Employee updated successfully.");
                        $("#editModal").modal("hide");
                        loadEmployees();
                    } else {
                        toastr.error("Failed to update employee.");
                    }
                },
                error: function () {
                    toastr.error("Error updating employee. Please try again.");
                },
            });
            employeeIdToEdit = null;
        }
    });

    /**
     * Reset edit modal state
     */
    $(".close").on("click", function () {
        employeeIdToEdit = null;
    });

    /**
     * Export employees for printing
     */
    $(document).on("click", ".btn-export", function () {
         window.open("/admin/customers/export");
        
    });
});
