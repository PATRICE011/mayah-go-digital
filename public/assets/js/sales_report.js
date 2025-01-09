$(document).ready(function () {
    let searchTerm = "";
    let fromDate = "";
    let toDate = "";

    const fetchSalesReport = (page = 1, perPage = 10) => {
        $.ajax({
            url: `/admin/sales-report`,
            type: "GET",
            data: {
                page,
                per_page: perPage,
                search: searchTerm,
                from_date: fromDate || '',  
                to_date: toDate || ''  
            },
            dataType: "json",
            success: function (response) {
                let salesReportHTML = "";

                response.data.forEach((item, index) => {
                    salesReportHTML += `
                        <tr>
                            <td>${
                                (response.current_page - 1) *
                                    response.per_page +
                                index +
                                1
                            }</td>
                            <td>${item.product_name}</td>
                            <td>${item.quantity}</td>
                            <td>₱ ${item.unit_price}</td>
                            <td>₱ ${item.total_amount}</td>
                            <td>${new Date(item.date).toLocaleTimeString([], {
                                hour: "2-digit",
                                minute: "2-digit",
                            })}, ${new Date(
                        item.date
                    ).toLocaleDateString()}</td>
                            <td>${item.customer_name || "Guest"}</td>
                        </tr>
                    `;
                });

                // Add pagination row
                salesReportHTML += `
                    <tr>
                        <td colspan="8" class="text-right">
                            <nav aria-label="Page navigation">
                                <ul class="pagination justify-content-end mb-0">
                                    ${
                                        response.prev_page_url
                                            ? `<li class="page-item">
                                            <a class="page-link" href="#" data-page="${
                                                response.current_page - 1
                                            }">&lt;</a>
                                        </li>`
                                            : `<li class="page-item disabled">
                                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">&lt;</a>
                                        </li>`
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
                                        </li>
                                    `
                                    ).join("")}
                                    ${
                                        response.next_page_url
                                            ? `<li class="page-item">
                                            <a class="page-link" href="#" data-page="${
                                                response.current_page + 1
                                            }">&gt;</a>
                                        </li>`
                                            : `<li class="page-item disabled">
                                            <a class="page-link" href="#" aria-disabled="true">&gt;</a>
                                        </li>`
                                    }
                                </ul>
                            </nav>
                        </td>
                    </tr>
                `;

                $("#salesReportBody").html(salesReportHTML);
            },
            error: function () {
                alert("Failed to fetch sales report. Please try again.");
            },
        });
    };

    // Load sales report on page load
    fetchSalesReport();

    // Handle pagination click
    $(document).on("click", "#salesReportBody .page-link", function (e) {
        e.preventDefault();
        const page = $(this).data("page");
        fetchSalesReport(page);
    });

   

    $(document).ready(function () {
        $("#exportSalesReportBtn").on("click", function () {
            const search = $('input[placeholder="Search..."]').val(); // Get the search term
            const exportUrl = `/admin/export-sales-report?search=${encodeURIComponent(
                search
            )}`;
            window.open(exportUrl, "_blank"); // Open the export view in a new tab
        });
    });
     // Handle search input
     $('input[placeholder="Search..."]').on("input", function () {
        searchTerm = $(this).val().trim();
        fetchSalesReport();
    });
    // Handle date filters
    $("#fromDate, #toDate").on("change", function () {
        fromDate = $("#fromDate").val();
        toDate = $("#toDate").val();
        fetchSalesReport();
    });
});
