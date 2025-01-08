$(document).ready(function () {
    $.ajaxSetup({
        headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") }
    });

    loadEmployees();

    function loadEmployees(page = 1, search = '') {
        $.ajax({
            url: `/admin/employees?page=${page}&search=${search}`, // Adjust endpoint as needed
            type: "GET",
            dataType: "json",
            success: function (response) {
                renderEmployees(response);
            },
            error: function (xhr) {
                toastr.error("Error loading employees. Please try again.");
            }
        });
    }

    function renderEmployees(response) {
        const tableBody = response.data
            .map((employee, index) => `
                <tr>
                    <td>${(response.current_page - 1) * response.per_page + index + 1}</td>
                    <td>${employee.name}</td>
                    <td>${employee.mobile}</td>
                    <td>
                        <div class="action__btn">
                            <button class="edit" data-toggle="modal" data-target="#editModal"
                                    data-id="${employee.id}" data-name="${employee.name}"
                                    data-mobile="${employee.mobile}" >
                                <i class="ri-pencil-line"></i>
                            </button>
                            <button class="archive" data-toggle="modal" data-target="#archiveModal"
                                    data-id="${employee.id}">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join("");

        const pagination = generatePagination(response);

        $("#employeeTableBody").html(tableBody);
        $("#paginationContainer").html(pagination);
    }

    function generatePagination(response) {
        return `
            <nav>
                <ul class="pagination justify-content-end mb-0">
                    ${response.current_page > 1 ? `<li class="page-item"><a class="page-link" href="#" data-page="${response.current_page - 1}" aria-label="Previous">«</a></li>` : `<li class="page-item disabled"><a class="page-link" href="#" aria-label="Previous">«</a></li>`}
                    ${Array.from({ length: response.last_page }, (_, i) => `<li class="page-item ${response.current_page === i + 1 ? 'active' : ''}"><a class="page-link" href="#" data-page="${i + 1}" aria-label="Page ${i + 1}">${i + 1}</a></li>`).join("")}
                    ${response.current_page < response.last_page ? `<li class="page-item"><a class="page-link" href="#" data-page="${response.current_page + 1}" aria-label="Next">»</a></li>` : `<li class="page-item disabled"><a class="page-link" href="#" aria-label="Next">»</a></li>`}
                </ul>
            </nav>
        `;
    }

    $(document).on("click", ".pagination a", function (e) {
        e.preventDefault();
        const page = $(this).data("page");
        const search = $("#searchInput").val();
        loadEmployees(page, search);
    });

    $("#searchInput").on("keyup", function () {
        const searchValue = $(this).val();
        loadEmployees(1, searchValue);
    });

    $("#addForm").on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        $.ajax({
            url: '/admin/employees/store',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    toastr.success('Employee added successfully!');
                    $('#addModal').modal('hide');
                    loadEmployees();
                } else {
                    toastr.error('Failed to add employee.');
                }
            },
            error: function(xhr) {
                toastr.error('Error adding employee: ' + xhr.responseJSON.message);
                console.error(xhr.responseText);
            }
        });
    });
    let employeeIdToDelete = null; // Variable to store the ID of the employee to be deleted

// Show modal and store employee ID when the archive button is clicked
$(document).on("click", ".archive", function () {
    employeeIdToDelete = $(this).data("id"); // Get the ID of the employee
    $("#archiveModal").modal("show"); // Show the confirmation modal
});

// Handle the delete action when the "Delete" button in the modal is clicked
$("#archiveModal .btn-danger").on("click", function () {
    if (employeeIdToDelete) {
        $.ajax({
            url: `/admin/employees/delete/${employeeIdToDelete}`, // Backend route for deletion
            type: "DELETE",
            success: function (response) {
                if (response.success) {
                    toastr.success("Employee deleted successfully.");
                    $("#archiveModal").modal("hide"); // Close the modal
                    employeeIdToDelete = null; // Reset the variable
                    loadEmployees(); // Reload the employees table
                } else {
                    toastr.error("Failed to delete employee.");
                }
            },
            error: function (xhr) {
                toastr.error("Error deleting employee. Please try again.");
                console.error(xhr.responseText);
            }
        });
    }
});

// Handle the cancel action when the "Cancel" button in the modal is clicked
$("#archiveModal .btn-secondary").on("click", function () {
    employeeIdToDelete = null; // Reset the variable
    $("#archiveModal").modal("hide"); // Close the modal
});

});
