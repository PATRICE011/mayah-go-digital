$(document).ready(function () {
    let selectedOrderId = null;

    loadOrders();

    function loadOrders(page = 1, search = '', filters = {}) {
        toggleSpinner(true);
        $.ajax({
            url: `/admin/online-orders?page=${page}&search=${search}`,
            type: "GET",
            data: filters,
            success: function (response) {
                toggleSpinner(false);
                renderOrders(response);
            },
            error: function () {
                toggleSpinner(false);
                toastr.error("Failed to load orders. Please try again.");
            }
        });
    }

    function renderOrders(response) {
        const tableBody = response.data.map((order, index) => `
            <tr>
                <td>${(response.current_page - 1) * response.per_page + index + 1}</td>
                <td>${order.orderdetails?.order_id_custom || 'N/A'}</td>
                <td>${order.user?.name || 'Guest'}</td>
                <td>₱${order.orderdetails?.total_amount || '0.00'}</td>
                <td>${formatDate(order.created_at)}</td>
                <td>${order.status === "paid" ? "Pending" : capitalizeFirstLetter(order.status)}</td>
                <td>
                    <div class="action__btn">
                        <button class="edit" data-id="${order.id}">
                            <i class="ri-mail-line"></i>
                        </button>
                        <button class="archive" data-bs-toggle="modal" data-bs-target="#archiveModal" data-id="${order.id}">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `).join("");

        $("#orderTableBody").html(tableBody);
        $(".pagination-container").html(renderPagination(response));
    }

    function renderPagination(response) {
        const pages = Array.from({ length: response.last_page }, (_, i) => `
            <li class="page-item ${response.current_page === i + 1 ? 'active' : ''}">
                <a class="page-link" href="#" data-page="${i + 1}">${i + 1}</a>
            </li>
        `).join("");

        return `
            <nav>
                <ul class="pagination justify-content-end mb-0">
                    <li class="page-item ${response.current_page <= 1 ? 'disabled' : ''}">
                        <a class="page-link" href="#" data-page="${response.current_page - 1}">«</a>
                    </li>
                    ${pages}
                    <li class="page-item ${response.current_page >= response.last_page ? 'disabled' : ''}">
                        <a class="page-link" href="#" data-page="${response.current_page + 1}">»</a>
                    </li>
                </ul>
            </nav>
        `;
    }

    $(document).on("click", ".pagination a", function (e) {
        e.preventDefault();
        const page = $(this).data("page");
        loadOrders(page, $("#searchOrder").val());
    });

    $("#searchOrder").on("keyup", function () {
        loadOrders(1, $(this).val());
    });

    $("#applyFilters").on("click", function () {
        const filters = {
            order_id: $("#filterOrderID").val(),
            date: $("#filterDate").val(),
            status: $("#filterStatus").val(),
        };
        $("#filterModal").modal("hide");
        loadOrders(1, '', filters);
    });

    $(document).on("click", ".edit", function () {
        selectedOrderId = $(this).data("id");

        $.ajax({
            url: `/admin/order-details/${selectedOrderId}`,
            type: "GET",
            success: function (order) {
                $("#modalOrderTitle").text(`Order Details: #${order.orderdetails.order_id_custom}`);
                $("#orderCustomID").text(`#${order.orderdetails.order_id_custom}`);
                $("#orderDate").text(formatDate(order.created_at));

                let statusLabel = order.status === "paid" ? "Pending" : capitalizeFirstLetter(order.status);
                $("#orderStatus").text(statusLabel).attr("class", `status-badge ${getStatusBadgeClass(order.status)}`);

                $("#paymentStatus").text("Paid").attr("class", "payment-badge paid");
                $("#paymentMethod").text(order.orderdetails.payment_method || "N/A");

                const summaryRows = order.order_items.map(item => `
                    <tr>
                        <td>${item.product.product_name}</td>
                        <td>${item.quantity}</td>
                        <td>₱${item.price}</td>
                    </tr>
                `).join("");

                $("#orderSummaryBody").html(summaryRows);
                $("#orderSubtotal").text(`₱${order.orderdetails.total_amount}`);
                $("#updateStatus").val(order.status);

                showCustomModal();
            },
            error: function () {
                toastr.error("Failed to load order details.");
            }
        });
    });

    $(document).on("click", "#applyOrderChanges", function () {
        const status = $("#updateStatus").val();
        if (!selectedOrderId) return toastr.error("No order selected.");

        $.ajax({
            url: `/admin/update-order-status/${selectedOrderId}`,
            type: "POST",
            headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
            data: { status },
            success: function (response) {
                toastr.success(response.message);
                hideCustomModal();
                loadOrders();
            },
            error: function () {
                toastr.error("Failed to update order status.");
            }
        });
    });

    function showCustomModal() {
        $("#orderDetailsModal").addClass("show");
    }

    function hideCustomModal() {
        $("#orderDetailsModal").removeClass("show");
    }

    $(document).on("click", "#closeOrderModal", hideCustomModal);

    $(document).on("click", "#orderDetailsModal", function (e) {
        if (e.target.id === "orderDetailsModal") hideCustomModal();
    });

    function toggleSpinner(show) {
        $("#spinner").toggle(show);
    }

    function capitalizeFirstLetter(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleString("en-US", {
            hour: "2-digit",
            minute: "2-digit",
            year: "numeric",
            month: "2-digit",
            day: "2-digit"
        }).replace(",", "");
    }

    function getStatusBadgeClass(status) {
        switch (status.toLowerCase()) {
            case "paid":
            case "pending":
                return "pending";
            case "confirmed":
                return "bg-primary";
            case "readyForPickup":
                return "bg-info";
            case "completed":
                return "bg-success";
            case "returned":
                return "bg-orange ";
            case "refunded":
                return "bg-danger";
            default:
                return "bg-secondary";
        }
    }
    
});
