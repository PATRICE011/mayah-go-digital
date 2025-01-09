$(document).ready(function () {
    // Function to fetch and refresh the audit list
    const fetchAuditList = () => {
        $.ajax({
            url: '/admin/audit-trail', // Adjust the route if necessary
            type: 'GET',
            dataType: 'html',
            success: function (response) {
                // Replace the table content with updated data
                const tableContent = $(response).find('.table-responsive').html();
                $('.table-responsive').html(tableContent);
            },
            error: function () {
                alert('Failed to refresh the audit list. Please try again.');
            }
        });
    };

    // Handle Refresh List button click
    $('#refreshAuditListBtn').on('click', function () {
        $(this).find('i').addClass('fa-spin'); // Add spinning effect to the icon
        fetchAuditList();
        setTimeout(() => {
            $(this).find('i').removeClass('fa-spin'); // Remove spinning effect after refresh
        }, 1000);
    });
});
