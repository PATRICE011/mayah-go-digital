$(document).ready(function () {
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
                        </div>`);
                    },
                    success: function (data) {
                        if (data.success) {
                            let changesTable = "";

                            if (data.changes && Object.keys(data.changes).length > 0) {
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

                                for (const field in data.changes) {
                                    if (["id", "created_at", "updated_at"].includes(field)) continue;

                                    const formattedField = field
                                        .replace(/_/g, " ")
                                        .replace(/\b\w/g, (l) => l.toUpperCase());

                                    let oldValue = data.old_values[field] || "N/A";
                                    let newValue = data.changes[field] || "N/A";

                                    if (field.includes("image") && newValue && newValue.startsWith("/")) {
                                        newValue = `<img src="${newValue}" alt="Image" style="max-height: 50px">`;
                                    }
                                    if (field.includes("image") && oldValue && oldValue.startsWith("/")) {
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

                            $(".audit-details-content").html(`
                            <div class="p-3">
                                <h6 class="border-bottom pb-2 mb-3">Action Information</h6>
                                <div class="row mb-3">
                                    <div class="col-md-4 font-weight-bold">Performed By:</div>
                                    <div class="col-md-8">${data.user?.name || "System"}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4 font-weight-bold">IP Address:</div>
                                    <div class="col-md-8">192.168.1.1</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4 font-weight-bold">Date & Time:</div>
                                    <div class="col-md-8">${data.audit.created_at}</div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4 font-weight-bold">Action Type:</div>
                                    <div class="col-md-8">${data.audit.action}</div>
                                </div>
                                <h6 class="border-bottom pb-2 mb-3 mt-4">Changes Made</h6>
                                ${changesTable}
                            </div>`);
                        } else {
                            $(".audit-details-content").html(`
                            <div class="alert alert-danger">
                                Failed to load audit details. ${data.message || ""}
                            </div>`);
                        }
                    },
                });
            });
    };

    // Initial attachment of event handlers
    attachEventHandlers();

    // Date range validation on form submit
    $('form').on('submit', function(e) {
        const startDate = $('input[name="start_date"]').val();
        const endDate = $('input[name="end_date"]').val();

        // If one date is provided, both must be provided
        if ((startDate && !endDate) || (!startDate && endDate)) {
            e.preventDefault();
            alert('Please provide both start and end dates for date filtering.');
        }

        // End date must be after start date
        if (startDate && endDate && new Date(startDate) > new Date(endDate)) {
            e.preventDefault();
            alert('End date must be after start date.');
        }
    });

    // Auto-refresh the "Last updated" time every minute
    setInterval(function() {
        const now = new Date();
        const hours = now.getHours();
        const minutes = now.getMinutes().toString().padStart(2, '0');
        const ampm = hours >= 12 ? 'PM' : 'AM';
        const formattedHours = (hours % 12 || 12).toString();
        const day = now.getDate().toString().padStart(2, '0');
        const month = (now.getMonth() + 1).toString().padStart(2, '0');
        const year = now.getFullYear();

        $('#last-updated').html(`<i class="fa fa-clock"></i> Last updated: ${formattedHours}:${minutes} ${ampm}, ${day}-${month}-${year}`);
    }, 60000); // Update every minute
});
