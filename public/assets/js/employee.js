function fetchEmployees(page = 1) {
    $.ajax({
        url: employeeUrl,
        type: 'GET',
        data: { page: page }, // Passing the page number to the backend
        dataType: 'json',
        success: function(data) {
            updateEmployeeTable(data);
            updatePagination(data); // You'll need to create this function
        },
        error: function(error) {
            console.log('Error:', error);
        }
    });
}

function updateEmployeeTable(data) {
    let tableRows = '';
    $.each(data.data, function(index, employee) {
        tableRows += `
            <tr>
                <td>${employee.id}</td>
                <td>${employee.name}</td>
                <td>${employee.mobile}</td>
                <td>${employee.role ? employee.role.name : 'N/A'}</td>
                <td>${employee.status}</td>
                <td>
                 
                        <div class="action__btn">
                            <button class="edit" data-toggle="modal" data-target="#editModal">
                                <i class="ri-pencil-line"></i>
                            </button>
                            <button class="archive" data-toggle="modal" data-target="#archiveModal">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </div>
                </td>
            </tr>
        `;
    });
    $('#employeeTableBody').html(tableRows);
}



// Trigger fetch on page load
$(document).ready(function() {
    fetchEmployees();
});

// Optionally, handle search or filters
$('#searchInput').on('input', function() {
    fetchEmployees($(this).val());
});

function applyFilters() {
    let searchQuery = $('#filterName').val();
    fetchEmployees(searchQuery);
}
$(document).on('click', '.pagination a', function(event) {
    event.preventDefault(); 
    var page = $(this).attr('href').split('page=')[1];
    fetchEmployees(page); // Modify fetchEmployees to accept a page parameter
});
function updatePagination(data) {
    let paginationLinks = '';
    if (data.last_page > 1) { // Check if pagination is needed
        paginationLinks += `<ul class="pagination justify-content-end mb-0">`;
        for (let i = 1; i <= data.last_page; i++) {
            const activeClass = data.current_page === i ? 'active' : '';
            paginationLinks += `<li class="page-item ${activeClass}"><a class="page-link" href="#" onclick="fetchEmployees(${i})">${i}</a></li>`;
        }
        paginationLinks += `</ul>`;
    }
    $('.pagination').html(paginationLinks); // Assume there's a container for pagination
}