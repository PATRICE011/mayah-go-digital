$(document).ready(function () {
    let searchTerm = ''; // Initialize search term

    const fetchSalesReport = (page = 1, perPage = 10) => {
        $.ajax({
            url: `/admin/products-report`,
            type: 'GET',
            data: { page, per_page: perPage, search: searchTerm },
            dataType: 'json',
            success: function (response) {
                // Populate the sales report table
                let salesReportHTML = '';
                response.data.forEach((item, index) => {
                    const amount = item.quantity * item.price; // Calculate total amount
                    salesReportHTML += `
                        <tr>
                            <td>${(response.current_page - 1) * response.per_page + index + 1}</td>
                            <td>${item.product_name}</td>
                            <td>${item.quantity}</td>
                            <td>₱ ${item.price}</td>
                            <td>₱ ${amount}</td>
                        </tr>
                    `;
                });

                // Add pagination row as part of the tbody
                salesReportHTML += `
                    <tr>
                        <td colspan="8" class="text-right">
                            <nav aria-label="Page navigation">
                                <ul class="pagination justify-content-end mb-0">
                                    ${response.prev_page_url
                                        ? `<li class="page-item">
                                            <a class="page-link" href="#" data-page="${response.current_page - 1}" tabindex="-1">&lt;</a>
                                        </li>`
                                        : `<li class="page-item disabled">
                                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">&lt;</a>
                                        </li>`}
                                    ${Array.from({ length: response.last_page }, (_, i) => `
                                        <li class="page-item ${response.current_page === i + 1 ? 'active' : ''}">
                                            <a class="page-link" href="#" data-page="${i + 1}">${i + 1}</a>
                                        </li>
                                    `).join('')}
                                    ${response.next_page_url
                                        ? `<li class="page-item">
                                            <a class="page-link" href="#" data-page="${response.current_page + 1}">&gt;</a>
                                        </li>`
                                        : `<li class="page-item disabled">
                                            <a class="page-link" href="#" aria-disabled="true">&gt;</a>
                                        </li>`}
                                </ul>
                            </nav>
                        </td>
                    </tr>
                `;

                $('#salesReportBody').html(salesReportHTML);
            },
            error: function () {
                alert('Failed to fetch sales report. Please try again.');
            }
        });
    };

    // Load sales report on page load
    fetchSalesReport();

    // Handle pagination click
    $(document).on('click', '#salesReportBody .page-link', function (e) {
        e.preventDefault();
        const page = $(this).data('page');
        fetchSalesReport(page);
    });

    // Handle search input
    $('input[placeholder="Search..."]').on('input', function () {
        searchTerm = $(this).val().trim(); // Update the search term
        fetchSalesReport(); // Fetch filtered results
    });

    // $('#printReportBtn').on('click', function () {
    //     const search = $('input[placeholder="Search..."]').val(); 
    //     const printUrl = `/admin/print-product-report?search=${encodeURIComponent(search)}`;
    //     window.open(printUrl); 
    // });
});
