function loadCategories() {
    $.ajax({
        url: "/admin/categories",
        type: "GET",
        success: function (categories) {
            const categoryDropdown = $("#addCategory");
            categoryDropdown
                .empty()
                .append('<option value="">Select Category</option>'); // Default placeholder

            // Append categories
            categories.forEach((category) => {
                categoryDropdown.append(
                    `<option value="${category.id}">${category.category_name}</option>` // Use `id` as the value
                );
            });
        },
        error: function () {
            toastr.error("Failed to load categories.");
        },
    });
}

// Load categories on page load
loadCategories();