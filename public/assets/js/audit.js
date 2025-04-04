$(document).ready(function () {
    // Function to fetch and refresh the audit list
    const fetchAuditList = () => {
        $.ajax({
            url: '/admin/audit-trail', // Adjust the route if necessary
            type: 'GET',
            dataType: 'html',
            beforeSend: function() {
                $('#refreshAuditListBtn').find('i').addClass('fa-spin');
            },
            success: function (response) {
                // Extract the entire page content to include both table and pagination
                const newContent = $(response).find('.card-body').html();
                $('.card-body').html(newContent);
                
                // Also update pagination if it exists separately
                const newPagination = $(response).find('.card-footer').html();
                if (newPagination) {
                    $('.card-footer').html(newPagination);
                }
            },
            error: function (xhr, status, error) {
                console.error("Error refreshing audit list:", error);
                alert('Failed to refresh the audit list. Please try again.');
            },
            complete: function() {
                // Remove spinning effect after completion (success or error)
                setTimeout(() => {
                    $('#refreshAuditListBtn').find('i').removeClass('fa-spin');
                }, 500);
            }
        });
    };

    // Handle Refresh List button click
    $('#refreshAuditListBtn').on('click', function (e) {
        e.preventDefault();
        fetchAuditList();
    });
    
    // Optional: Auto-refresh every 5 minutes (adjust as needed)
    // const autoRefreshInterval = 300000; // 5 minutes in milliseconds
    // setInterval(fetchAuditList, autoRefreshInterval);
});