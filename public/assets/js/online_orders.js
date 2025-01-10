$(document).ready(function () {
     // Load initial orders on page load
     loadOrders();

     // Function to load orders dynamically
     function loadOrders(page = 1, search = '', filters = {}) {
         toggleSpinner(true); // Show spinner
         $.ajax({
             url: `/admin/online-orders?page=${page}&search=${search}`,
             type: "GET",
             data: filters, // Include filters in the request
             success: function (response) {
                 toggleSpinner(false); // Hide spinner
                 renderOrders(response); // Render the table and pagination
             },
             error: function () {
                 toggleSpinner(false); // Hide spinner on error
                 toastr.error("Failed to load orders. Please try again.");
             }
         });
     }
 
     // Function to render orders in the table
     function renderOrders(response) {
         const tableBody = response.data
             .map((order, index) => `
                 <tr>
                     <td>${(response.current_page - 1) * response.per_page + index + 1}</td>
                     <td>${order.orderdetails ? order.orderdetails.order_id_custom : 'N/A'}</td>
                     <td>${order.user ? order.user.name : 'Guest'}</td>
                     <td>₱${order.orderdetails ? order.orderdetails.total_amount : '0.00'}</td>
                     <td>${formatDate(order.created_at)}</td>
                    <td>${order.status === "paid" ? "Pending" : capitalizeFirstLetter(order.status)}</td>

                     <td>
                         <div class="action__btn">
                             <button class="edit" data-toggle="modal" data-target="#orderDetailsModal" 
                                     data-id="${order.id}">
                                 <i class="ri-mail-line"></i>
                             </button>
                             <button class="archive" data-bs-toggle="modal" data-bs-target="#archiveModal"
                                     data-id="${order.id}">
                                 <i class="ri-delete-bin-line"></i>
                             </button>
                         </div>
                     </td>
                 </tr>
             `).join("");
 
         $("#orderTableBody").html(tableBody);
         $(".pagination-container").html(renderPagination(response));
     }
 
     // Event listener for Apply Filters button
     $("#applyFilters").on("click", function () {
         const filters = {
             order_id: $("#filterOrderID").val(),
             date: $("#filterDate").val(),
             status: $("#filterStatus").val(),
         };
         $("#filterModal").modal("hide"); // Close the modal
         loadOrders(1, '', filters); // Reload orders with filters
     });
 
     // Function to render pagination
     function renderPagination(response) {
         const prevDisabled = response.current_page <= 1 ? 'disabled' : '';
         const nextDisabled = response.current_page >= response.last_page ? 'disabled' : '';
         const pages = Array.from({ length: response.last_page }, (_, i) => `
             <li class="page-item ${response.current_page === i + 1 ? 'active' : ''}">
                 <a class="page-link" href="#" data-page="${i + 1}">${i + 1}</a>
             </li>
         `).join("");
 
         return `
             <nav>
                 <ul class="pagination justify-content-end mb-0">
                     <li class="page-item ${prevDisabled}">
                         <a class="page-link" href="#" data-page="${response.current_page - 1}">«</a>
                     </li>
                     ${pages}
                     <li class="page-item ${nextDisabled}">
                         <a class="page-link" href="#" data-page="${response.current_page + 1}">»</a>
                     </li>
                 </ul>
             </nav>
         `;
     }

    // Event listener for pagination links
    $(document).on("click", ".pagination a", function (e) {
        e.preventDefault();
        const page = $(this).data("page");
        const search = $("#searchOrder").val();
        loadOrders(page, search); // Load selected page
    });

    // Event listener for search input
    $("#searchOrder").on("keyup", function () {
        const searchValue = $(this).val();
        loadOrders(1, searchValue); // Search starts from page 1
    });

    // Event listener for "View" button to load order details
    $(document).on("click", ".edit", function () {
        const orderId = $(this).data("id");

        // Fetch order details via AJAX
        $.ajax({
            url: `/admin/order-details/${orderId}`,
            type: "GET",
            success: function (response) {
                populateOrderDetails(response);
                $("#orderDetailsModal").modal("show"); // Show the modal
            },
            error: function () {
                toastr.error("Failed to load order details. Please try again.");
            }
        });
    });

    // Function to populate the modal with order details
    function populateOrderDetails(order) {
        // Update modal title with custom order ID
        $("#orderDetailsModal .modal-title").text(`Order Details: #${order.orderdetails.order_id_custom}`);
    
        // Update Order ID field
        $("#orderCustomID").text(`#${order.orderdetails.order_id_custom}`);
    
        // Update Order Date field
        $("#orderDate").text(formatDate(order.created_at));
    
        // Determine and display Order Status
        let displayOrderStatus = order.status === "paid" ? "Pending" : capitalizeFirstLetter(order.status); // If "paid", show "Pending"
        let orderStatusClass = getStatusBadgeClass(order.status); // Get badge class for styling
        $("#orderStatus").text(displayOrderStatus).attr("class", `badge ${orderStatusClass}`);
    
        // Display Payment Status as always "Paid" for all statuses
        let paymentStatusText = "Paid"; // All orders are considered "Paid"
        let paymentStatusClass = "bg-success"; // Green badge for paid
        $("#paymentStatus").text(paymentStatusText).attr("class", `badge ${paymentStatusClass}`);
    
        // Display Payment Method
        $("#paymentMethod").text(order.orderdetails.payment_method || "N/A");
    
        // Populate Order Summary Table
        const summaryRows = order.order_items
            .map(item => `
                <tr>
                    <td>${item.product.product_name}</td>
                    <td>₱${item.price}</td>
                    <td>${item.quantity}</td>
                </tr>
            `)
            .join("");
    
        const subtotalRow = `
            <tr>
                <td colspan="2"><strong>Subtotal</strong></td>
                <td>₱${order.orderdetails.total_amount}</td>
            </tr>
        `;
    
        // Update the table content
        $("#orderDetailsModal tbody").html(summaryRows + subtotalRow);
    
        // Set current status in dropdown
        $("#viewStatus").val(order.status);
    }
    
    // Helper function to get Bootstrap badge class for order status
    function getStatusBadgeClass(status) {
        switch (status.toLowerCase()) {
            case "paid":
            case "pending":
                return "bg-warning text-dark";
            case "confirmed":
                return "bg-primary";
            case "ready-for-pickup":
                return "bg-info";
            case "completed":
                return "bg-success";
            default:
                return "bg-secondary";
        }
    }
    
    // Helper function to capitalize the first letter of a string
    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }
    
    // Function to format date (optional if already used elsewhere)
    function formatDate(dateString) {
        const date = new Date(dateString);
        const options = { hour: "2-digit", minute: "2-digit", year: "numeric", month: "2-digit", day: "2-digit" };
        return date.toLocaleString("en-US", options).replace(",", "");
    }
    
    // Helper function to capitalize the first letter of a string
    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }
    
    // Function to format date (optional if already used elsewhere)
    function formatDate(dateString) {
        const date = new Date(dateString);
        const options = { hour: "2-digit", minute: "2-digit", year: "numeric", month: "2-digit", day: "2-digit" };
        return date.toLocaleString("en-US", options).replace(",", "");
    }
    

    // Event listener for "Apply Changes" button
    $(document).on("click", "#applyOrderChanges", function () {
        const orderId = $(".edit").data("id"); // Get current order ID
        const status = $("#updateStatus").val(); // Get the selected status

        $.ajax({
            url: `/admin/update-order-status/${orderId}`,
            type: "POST",
            headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
            data: { status },
            success: function (response) {
                toastr.success(response.message);
                $("#orderDetailsModal").modal("hide"); // Close the modal
                loadOrders(); // Reload orders to reflect changes
            },
            error: function () {
                toastr.error("Failed to update order status. Please try again.");
            }
        });
    });

    function toggleSpinner(show) {
        $("#spinner").toggle(show);
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        const options = { hour: '2-digit', minute: '2-digit', year: 'numeric', month: '2-digit', day: '2-digit' };
        return date.toLocaleString('en-US', options).replace(',', '');
    }

    
    
});
