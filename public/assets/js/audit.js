$(document).ready(function () {
    // Function to fetch and refresh the audit list
    const fetchAuditList = () => {
        $.ajax({
            url: "/admin/audit/audit-trail", // Adjust the route if necessary
            type: "GET",
            data: {
                action: $("#actionFilter").val(),
                user: $("#userFilter").val(),
                start_date: $("#startDate").val(),
                end_date: $("#endDate").val(),
                search: $("#auditSearch").val(),
            },
            dataType: "html",
            beforeSend: function () {
                $("#refreshAuditListBtn").find("i").addClass("fa-spin");
            },
            success: function (response) {
                // Extract the entire page content to include both table and pagination
                const newContent = $(response).find(".card-body").html();
                $(".card-body").html(newContent);

                // Also update pagination if it exists separately
                const newPagination = $(response).find(".card-footer").html();
                if (newPagination) {
                    $(".card-footer").html(newPagination);
                }

                // Reattach event handlers to newly loaded content
                attachEventHandlers();

                // Update the last updated timestamp
                $("#last-updated").html(
                    '<i class="fa fa-clock"></i> Last updated: ' +
                        new Date().toLocaleString()
                );
            },
            error: function (xhr, status, error) {
                console.error("Error refreshing audit list:", error);
                alert("Failed to refresh the audit list. Please try again.");
            },
            complete: function () {
                // Remove spinning effect after completion (success or error)
                setTimeout(() => {
                    $("#refreshAuditListBtn").find("i").removeClass("fa-spin");
                }, 500);
            },
        });
    };

    // Function to attach event handlers to dynamic content
    const attachEventHandlers = () => {
        // View details button click handler
        $(".view-details")
            .off("click")
            .on("click", function () {
                const auditId = $(this).data("id");
                $("#auditDetailsModal").modal("show");

                // Load the details via AJAX
                $.ajax({
                    url: "/admin/audit/audit-trail/" + auditId,
                    type: "GET",
                    dataType: "json",
                    beforeSend: function () {
                        $(".audit-details-content").html(`
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    `);
                    },
                    // Inside your success handler for the audit details AJAX request
                    success: function (data) {
                        if (data.success) {
                            // Render the audit details
                            let changesTable = "";

                            if (
                                data.changes &&
                                Object.keys(data.changes).length > 0
                            ) {
                                changesTable = `
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Field</th>
                                                <th>Before</th>
                                                <th>After</th>
                                            </tr>
                                        </thead>
                                        <tbody>`;

                                // Loop through the changes
                                for (const field in data.changes) {
                                    // Skip id and timestamps
                                    if (
                                        [
                                            "id",
                                            "created_at",
                                            "updated_at",
                                        ].includes(field)
                                    )
                                        continue;

                                    // Format the field name for display
                                    const formattedField = field
                                        .replace(/_/g, " ")
                                        .replace(/\b\w/g, (l) =>
                                            l.toUpperCase()
                                        );

                                    // Handle different value types
                                    let oldValue =
                                        data.old_values[field] || "N/A";
                                    let newValue = data.changes[field] || "N/A";

                                    // Special handling for image fields
                                    if (
                                        field.includes("image") &&
                                        newValue &&
                                        newValue.startsWith("/")
                                    ) {
                                        newValue = `<img src="${newValue}" alt="Image" style="max-height: 50px">`;
                                    }
                                    if (
                                        field.includes("image") &&
                                        oldValue &&
                                        oldValue.startsWith("/")
                                    ) {
                                        oldValue = `<img src="${oldValue}" alt="Image" style="max-height: 50px">`;
                                    }

                                    changesTable += `
                                    <tr>
                                        <td>${formattedField}</td>
                                        <td>${oldValue}</td>
                                        <td>${newValue}</td>
                                    </tr>`;
                                }

                                changesTable += `</tbody></table></div>`;
                            } else {
                                changesTable = `<p>No detailed changes available for this action.</p>`;
                            }

                            // Include model info if available (for "add" or "delete" actions)
                            let modelInfoRow = "";
                            if (data.audit.model_info) {
                                modelInfoRow = `
                                <div class="row mb-3">
                                    <div class="col-md-4 font-weight-bold">Resource:</div>
                                    <div class="col-md-8">${data.audit.model_info}</div>
                                </div>`;
                            }

                            // Check for product add/delete actions
                            if (
                                data.audit.action === "add" &&
                                data.audit.model === "Product"
                            ) {
                                modelInfoRow = `
                                <div class="row mb-3">
                                    <div class="col-md-4 font-weight-bold">Product Added:</div>
                                    <div class="col-md-8">${data.audit.model_info}</div>
                                </div>`;
                            } else if (
                                data.audit.action === "delete" &&
                                data.audit.model === "Product"
                            ) {
                                modelInfoRow = `
                                <div class="row mb-3">
                                    <div class="col-md-4 font-weight-bold">Product Deleted:</div>
                                    <div class="col-md-8">${data.audit.model_info}</div>
                                </div>`;
                            }

                            $(".audit-details-content").html(`
                            <div class="p-3">
                                <h6 class="border-bottom pb-2 mb-3">Action Information</h6>
                                <div class="row mb-3">
                                    <div class="col-md-4 font-weight-bold">Performed By:</div>
                                    <div class="col-md-8">${
                                        data.user?.name || "System"
                                    }</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4 font-weight-bold">IP Address:</div>
                                    <div class="col-md-8">192.168.1.1</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4 font-weight-bold">Date & Time:</div>
                                    <div class="col-md-8">${
                                        data.audit.created_at
                                    }</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4 font-weight-bold">Action Type:</div>
                                    <div class="col-md-8">${
                                        data.audit.action
                                    }</div>
                                </div>
                                ${modelInfoRow}

                                <h6 class="border-bottom pb-2 mb-3 mt-4">Changes Made</h6>
                                ${changesTable}
                            </div>
                        `);
                        } else {
                            $(".audit-details-content").html(`
                            <div class="alert alert-danger">
                                Failed to load audit details. ${
                                    data.message || ""
                                }
                            </div>`);
                        }
                    },
                });
            });
    };

    // Initial attachment of event handlers
    attachEventHandlers();

    // Handle Refresh List button click
    $("#refreshAuditListBtn").on("click", function (e) {
        e.preventDefault();
        fetchAuditList();
    });

    // // Handle search input
    // $("#auditSearch").on("keyup", function (e) {
    //     if (e.keyCode === 13) {
    //         // Enter key
    //         fetchAuditList();
    //     }
    // });

    // // Handle filter application
    // $("#applyFilters").on("click", function () {
    //     fetchAuditList();
    // });

    // // Handle filter reset
    // $("#resetFilters").on("click", function () {
    //     $("#actionFilter").val("");
    //     $("#userFilter").val("");
    //     $("#startDate").val("");
    //     $("#endDate").val("");
    //     fetchAuditList();
    // });

    // // Handle export buttons
    // $("#exportCsvBtn").on("click", function () {
    //     window.location.href =
    //         "/admin/audit-trail/export?format=csv&" +
    //         $.param({
    //             action: $("#actionFilter").val(),
    //             user: $("#userFilter").val(),
    //             start_date: $("#startDate").val(),
    //             end_date: $("#endDate").val(),
    //             search: $("#auditSearch").val(),
    //         });
    // });

    // $("#exportExcelBtn").on("click", function () {
    //     window.location.href =
    //         "/admin/audit-trail/export?format=excel&" +
    //         $.param({
    //             action: $("#actionFilter").val(),
    //             user: $("#userFilter").val(),
    //             start_date: $("#startDate").val(),
    //             end_date: $("#endDate").val(),
    //             search: $("#auditSearch").val(),
    //         });
    // });

    // Populate user filter dropdown dynamically
    $.ajax({
        url: "/admin/audit/users/list",
        type: "GET",
        dataType: "json",
        success: function (data) {
            if (data.success) {
                const userSelect = $("#userFilter");
                data.users.forEach((user) => {
                    userSelect.append(
                        `<option value="${user.id}">${user.name}</option>`
                    );
                });
            }
        },
    });

    // Optional: Auto-refresh every 5 minutes (adjust as needed)
    // const autoRefreshInterval = 300000; // 5 minutes in milliseconds
    // setInterval(fetchAuditList, autoRefreshInterval);
});
