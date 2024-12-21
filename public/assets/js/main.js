// ORDERS TABS SWITCHING

// Restore the active tab on page load
document.addEventListener("DOMContentLoaded", () => {
    // Retrieve the active tab from localStorage or default to "dashboard"
    const savedTab = localStorage.getItem("activeTab") || "dashboard";

    // Activate the saved tab
    switchTab(savedTab);

    // Attach "Back to Orders" listener globally
    attachBackToOrdersListener();
});

// Function to fetch and display order details dynamically
function showOrderDetails(event, orderId) {
    event.preventDefault();

    const ordersContent = document.querySelector("#orders");
    if (!ordersContent) {
        console.error("Orders content container not found.");
        return;
    }

    // Fetch order details from the server
    fetch(`/user/order-status/orderdetails/${orderId}`, {
        headers: { "X-Requested-With": "XMLHttpRequest" },
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error("Failed to fetch order details.");
            }
            return response.json();
        })
        .then((data) => {
            if (!data.html) {
                throw new Error("Invalid response: Missing 'html' key.");
            }

            // Replace the content inside the "Orders" tab
            ordersContent.innerHTML = data.html;

            // Attach "Back to Orders" event listener dynamically for the new content
            attachBackToOrdersListener();
        })
        .catch((error) => {
            console.error("Error loading order details:", error);
            toastr.error("Failed to load order details. Please try again.");
        });
}
// Function to handle "View" button clicks in the Dashboard tab
function showDashboardOrderDetails(event, orderId) {
    event.preventDefault();

    const dashboardContent = document.querySelector("#dashboard");
    if (!dashboardContent) {
        console.error("Dashboard content container not found.");
        return;
    }

    // Fetch order details via AJAX
    fetch(`/user/order-status/orderdetails/${orderId}`, {
        headers: { "X-Requested-With": "XMLHttpRequest" },
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error("Failed to fetch order details.");
            }
            return response.json();
        })
        .then((data) => {
            if (!data.html) {
                throw new Error("Invalid response: Missing 'html' key.");
            }

            // Replace the content inside the "Dashboard" tab
            dashboardContent.innerHTML = data.html;

            // Attach "Back to Dashboard" event listener dynamically for the new content
            attachBackToDashboardListener();
        })
        .catch((error) => {
            console.error("Error loading order details:", error);
            toastr.error("Failed to load order details. Please try again.");
        });
}

// Attach the "Back to Dashboard" button listener
function attachBackToDashboardListener() {
    const backToDashboardButton = document.querySelector(
        ".back-to-dashboard-btn"
    );
    if (backToDashboardButton) {
        backToDashboardButton.addEventListener("click", () => {
            console.log("Back to Dashboard clicked!");

            window.location.reload();
        });
    } else {
        console.warn(
            "Back to Dashboard button not found. It may not exist yet."
        );
    }
}

// Attach the "Back to Orders" button listener
function attachBackToOrdersListener() {
    const backToOrdersButton = document.querySelector(".back-to-orders-btn");
    if (backToOrdersButton) {
        console.log("Attaching 'Back to Orders' listener.");
        backToOrdersButton.addEventListener("click", () => {
            console.log("Back to Orders clicked!");

            // Save the active tab state as "orders"
            localStorage.setItem("activeTab", "orders");

            // Reload the page to reflect the Orders tab
            window.location.reload();
        });
    } else {
        console.warn("Back to Orders button not found. It may not exist yet.");
    }
}

// Function to switch tabs dynamically
function switchTab(tabId) {
    // Remove active-tab class from all tabs and contents
    document
        .querySelectorAll(".account__tab")
        .forEach((tab) => tab.classList.remove("active-tab"));
    document
        .querySelectorAll(".tab__content")
        .forEach((content) => content.classList.remove("active-tab"));

    // Find and activate the target tab and content
    const targetTab = document.querySelector(`[data-target="#${tabId}"]`);
    const targetContent = document.querySelector(`#${tabId}`);

    if (targetTab && targetContent) {
        targetTab.classList.add("active-tab");
        targetContent.classList.add("active-tab");

        // Save the active tab to localStorage
        localStorage.setItem("activeTab", tabId);
        console.log(`Switched to tab: ${tabId}`);
    } else {
        console.error(`Tab or content not found for ID: ${tabId}`);
    }
}

// Define the goToOrdersTab function globally
// Define a universal "Go to Active Tab" function globally
window.goToActiveTab = function () {
    const activeTab = localStorage.getItem("activeTab") || "dashboard"; // Default to "dashboard" if no tab is saved
    console.log(`Navigating to the active tab: ${activeTab}`);

    // Reload the page to reflect the currently active tab
    window.location.reload();
};

// Save active tab to localStorage when clicking a tab
document.querySelectorAll(".account__tab").forEach((tab) => {
    tab.addEventListener("click", (event) => {
        const targetTabId = tab.getAttribute("data-target").replace("#", "");
        localStorage.setItem("activeTab", targetTabId);
        console.log(`Tab clicked: ${targetTabId}`);
    });
});
// ADD TO WISHLIST
function addToWishlist(productId) {
    const form = document.getElementById(`wish-button-${productId}`);
    const url = form.getAttribute("action"); // Use the 'action' attribute here
    const token = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");

    fetch(url, {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
            "X-CSRF-TOKEN": token,
        },
        body: new URLSearchParams(new FormData(form)),
    })
        .then((response) => {
            if (response.ok) {
                return response.json();
            } else {
                return response.json().then((err) => {
                    throw err;
                });
            }
        })
        .then((data) => {
            // Update wishlist count dynamically
            if (data.wishlistCount !== undefined) {
                document.querySelector(".header__action-btn .count").innerText =
                    data.wishlistCount;
            }

            // Show success notification
            toastr.success(data.message || "Product added to wishlist!");
        })
        .catch((error) => {
            if (error.error === "This product is already in your wishlist.") {
                toastr.warning(error.error);
            } else {
                toastr.error(
                    error.error ||
                        "An error occurred while adding to the wishlist."
                );
            }
            console.error("Error:", error);
        });
}

// PRODUCT FILTER AND ADD TO CART
document.addEventListener("DOMContentLoaded", function () {
    // Function to handle product filtering
    function handleProductFiltering() {
        document.querySelectorAll(".brand-filter").forEach((filter) => {
            filter.addEventListener("change", function () {
                const selectedCategories = Array.from(
                    document.querySelectorAll(".brand-filter:checked")
                ).map((input) => input.value);

                fetch("/filter-products", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute("content"),
                    },
                    body: JSON.stringify({ categories: selectedCategories }),
                })
                    .then((response) => response.json())
                    .then((data) => {
                        // Replace product grid with filtered products
                        document.querySelector(
                            ".products__container"
                        ).innerHTML = data.html;

                        // Update total products count
                        document.querySelector(
                            ".total__products span"
                        ).innerText = data.count;

                        // Reattach Add to Cart event listeners for newly loaded products
                        attachAddToCartListeners();
                    })
                    .catch((error) => console.error("Error:", error));
            });
        });
    }

    // Function to attach Add to Cart event listeners
    function attachAddToCartListeners() {
        document
            .querySelectorAll("form.d-inline, form.add-to-cart-form")
            .forEach((form) => {
                const button = form.querySelector(
                    "button[type='button'], button[type='submit']"
                );
                if (!button) return;

                // Remove any previously attached event listeners to avoid duplication
                button.replaceWith(button.cloneNode(true));
                const clonedButton = form.querySelector(
                    "button[type='button'], button[type='submit']"
                );

                // Add event listener to the cloned button
                clonedButton.addEventListener("click", function (e) {
                    e.preventDefault();

                    // Get the URL either from 'action' or 'data-url' depending on the form
                    const url =
                        form.getAttribute("action") ||
                        form.getAttribute("data-url");

                    // Convert form data to a URL-encoded string
                    const formData = new URLSearchParams(new FormData(form));

                    // Show loading state on the button
                    clonedButton.disabled = true;
                    const originalText = clonedButton.innerHTML;
                    clonedButton.innerHTML =
                        '<i class="bx bx-loader bx-spin"></i>';

                    // Send AJAX request
                    fetch(url, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded",
                            "X-CSRF-TOKEN": document
                                .querySelector('meta[name="csrf-token"]')
                                .getAttribute("content"),
                        },
                        body: formData,
                    })
                        .then((response) => {
                            // 1) If the controller returned a redirect (302),
                            //    it means user is not logged in (per your controller code).
                            if (response.redirected) {
                                // Follow the redirect to the login page
                                window.location.href = response.url;
                                return;
                            }

                            // 2) If it's not redirected but also not OK (e.g., 400, 401, 500)
                            if (!response.ok) {
                                return response
                                    .json()
                                    .then((err) => Promise.reject(err));
                            }

                            // 3) Otherwise (status 2xx), parse JSON
                            return response.json();
                        })
                        .then((data) => {
                            // If we get here, it's a successful JSON response

                            // Update the cart count dynamically
                            const cartCountElement =
                                document.getElementById("cart-count");
                            if (cartCountElement) {
                                cartCountElement.innerText = data.cartCount;
                            }

                            // Display success message and update button
                            toastr.success(
                                data.message || "Product added to cart!"
                            );
                            clonedButton.innerHTML =
                                '<i class="bx bx-check"></i>';
                        })
                        .catch((error) => {
                            // For errors returned from the server (e.g., out of stock or other issues)
                            console.error("Error:", error);
                            toastr.error(
                                error.error || "An unexpected error occurred."
                            );
                            clonedButton.innerHTML = originalText; // Reset button text
                        })
                        .finally(() => {
                            // Re-enable button (unless we redirected)
                            clonedButton.disabled = false;
                        });
                });
            });
    }

    // Initial attachment of listeners
    handleProductFiltering();
    attachAddToCartListeners();

    // Reattach listeners if the DOM is updated dynamically
    document.addEventListener("productsUpdated", attachAddToCartListeners);
});

// PRODUCT DETAILS REALTIME QUANTITY UPDATE
document.addEventListener("DOMContentLoaded", function () {
    // Attach event listeners to all quantity inputs
    document.querySelectorAll(".quantity").forEach((input) => {
        input.addEventListener("input", function () {
            const productId = this.dataset.productId;
            const hiddenInput = document.getElementById(
                `quantity-${productId}`
            );
            hiddenInput.value = this.value; // Sync hidden input value
        });
    });
});

// UPDATE PROFILE
$(document).ready(function () {
    // Handle tab clicks
    $(".account__tab").on("click", function () {
        // Remove active class from all tabs and contents
        $(".account__tab").removeClass("active-tab");
        $(".tab__content").removeClass("active-tab");

        // Add active class to the clicked tab and corresponding content
        $(this).addClass("active-tab");
        const target = $(this).data("target"); // Get target content's ID
        $(target).addClass("active-tab");
    });

    // Use the activeTab variable from the Blade script
    if (typeof activeTab !== "undefined" && activeTab) {
        // Remove active class from all tabs and contents
        $(".account__tab").removeClass("active-tab");
        $(".tab__content").removeClass("active-tab");

        // Set the active tab and content based on session
        const targetTab = $(`[data-target="#${activeTab}"]`);
        const targetContent = $(`#${activeTab}`);
        if (targetTab.length && targetContent.length) {
            targetTab.addClass("active-tab");
            targetContent.addClass("active-tab");
        } else {
            // Default to Dashboard
            $('[data-target="#dashboard"]').addClass("active-tab");
            $("#dashboard").addClass("active-tab");
        }
    }

    $(document).ready(function () {
        let countdownTimers = {}; // Object to store individual timers for buttons/links

        // Reusable Countdown Function
        function startCountdown(time, element, timerKey) {
            let countdown = time;

            // Clear any existing countdown for this element
            if (countdownTimers[timerKey]) {
                clearInterval(countdownTimers[timerKey]);
            }

            // Check if the element is a button or link
            const isButton = element.is("button");

            // Disable the element and update text
            if (isButton) {
                element.prop("disabled", true).text(`Wait ${countdown}s`);
            } else {
                element
                    .css({ "pointer-events": "none", opacity: "0.5" })
                    .text(`Wait ${countdown}s`);
            }

            // Start the countdown
            countdownTimers[timerKey] = setInterval(function () {
                countdown--;
                if (isButton) {
                    element.text(`Wait ${countdown}s`);
                } else {
                    element.text(`Wait ${countdown}s`);
                }

                if (countdown <= 0) {
                    clearInterval(countdownTimers[timerKey]);
                    delete countdownTimers[timerKey]; // Remove the timer reference

                    // Re-enable the element
                    if (isButton) {
                        element.prop("disabled", false).text("Get OTP");
                    } else {
                        element
                            .css({ "pointer-events": "auto", opacity: "1" })
                            .text("Resend Code");
                    }
                }
            }, 1000);
        }

        // Handle Get OTP for Update Profile Section
        $("#get-otp-button-update-profile").on("click", function () {
            const url = $(this).data("url");
            const csrfToken = $(this).data("csrf");
            const button = $(this);

            button.prop("disabled", true).text("Sending OTP...");

            $.ajax({
                url: url,
                method: "POST",
                data: { action: "update-profile", _token: csrfToken },
                success: function (response) {
                    toastr.success(response.message);
                    startCountdown(60, button, "updateProfile");
                },
                error: function (xhr) {
                    const remainingTime =
                        xhr.responseJSON?.remaining_time || 60;
                    toastr.error(
                        xhr.responseJSON?.error ||
                            "An unexpected error occurred."
                    );
                    startCountdown(remainingTime, button, "updateProfile");
                },
            });
        });

        // Handle Get OTP for Change Password Section
        $("#get-otp-button-change-password").on("click", function () {
            const url = $(this).data("url");
            const csrfToken = $(this).data("csrf");
            const button = $(this);

            button.prop("disabled", true).text("Sending OTP...");

            $.ajax({
                url: url,
                method: "POST",
                data: { action: "change-password", _token: csrfToken },
                success: function (response) {
                    toastr.success(response.message);
                    startCountdown(60, button, "changePassword");
                },
                error: function (xhr) {
                    const remainingTime =
                        xhr.responseJSON?.remaining_time || 60;
                    toastr.error(
                        xhr.responseJSON?.error ||
                            "An unexpected error occurred."
                    );
                    startCountdown(remainingTime, button, "changePassword");
                },
            });
        });

        // Handle Resend OTP for Registration Section
        const resendOtpLink = $("#resend-otp-link");

        resendOtpLink.on("click", function (e) {
            e.preventDefault();

            const url = $(this).data("url");
            const csrfToken = $(this).data("csrf");

            resendOtpLink
                .css({ "pointer-events": "none", opacity: "0.5" })
                .text("Sending...");

            $.ajax({
                url: url,
                method: "POST",
                data: { _token: csrfToken },
                success: function (response) {
                    toastr.success(response.message);
                    startCountdown(60, resendOtpLink, "registrationResend");
                },
                error: function (xhr) {
                    const remainingTime =
                        xhr.responseJSON?.remaining_time || 60;
                    toastr.error(
                        xhr.responseJSON?.error || "An error occurred."
                    );
                    startCountdown(
                        remainingTime,
                        resendOtpLink,
                        "registrationResend"
                    );
                },
            });
        });
    });
});

// UPDATE CART QUANTITY
document.addEventListener("DOMContentLoaded", () => {
    const quantityInputs = document.querySelectorAll(".quantity");

    quantityInputs.forEach((input) => {
        input.addEventListener("input", function () {
            // Use 'input' event for real-time updates
            const cartItemId = this.name.match(/\d+/)[0]; // Extract cart_item_id from input name
            const quantity = parseInt(this.value);

            if (isNaN(quantity) || quantity < 1) {
                console.warn("Invalid quantity entered!");
                return;
            }

            // Send AJAX request to update quantity
            fetch("user/cart/update-quantity", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"), // CSRF token for Laravel
                },
                body: JSON.stringify({
                    cart_item_id: cartItemId,
                    quantity: quantity,
                }),
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        console.log("Quantity updated successfully!");
                    } else {
                        console.error(
                            "Failed to update quantity:",
                            data.message
                        );
                    }
                })
                .catch((error) => console.error("Error:", error));
        });
    });
});

// DYNAMIC STOCK TRACKING IN CART PAGE
document.addEventListener("DOMContentLoaded", function () {
    const quantityInputs = document.querySelectorAll(".quantity");
    const subtotalElement = document.getElementById("subtotal");
    const totalElement = document.getElementById("total");

    // Function to update totals dynamically
    function updateCartTotals() {
        let total = 0;

        // Loop through each cart item row
        document.querySelectorAll(".cart-item-row").forEach((row) => {
            const quantityInput = row.querySelector(".quantity");
            const priceElement = row.querySelector(".table__price");
            const subtotalElement = row.querySelector(".table__subtotal");
            const price = parseFloat(priceElement.dataset.price) || 0; // Default to 0 if invalid price
            const quantity = parseInt(quantityInput.value) || 0; // Default to 0 if invalid quantity

            // Calculate the subtotal for this row
            const rowSubtotal = price * quantity;
            subtotalElement.textContent = `₱ ${rowSubtotal.toFixed(2)}`;

            total += rowSubtotal;
        });

        // Update the grand total
        document.getElementById("subtotal").textContent = `₱ ${total.toFixed(
            2
        )}`;
        document.getElementById("total").textContent = `₱ ${total.toFixed(2)}`;
    }

    // Add event listeners for quantity changes
    quantityInputs.forEach((input) => {
        input.addEventListener("input", function (event) {
            const maxStock = parseInt(input.dataset.stock); // Get max stock
            let quantity = parseInt(input.value); // Get the entered quantity

            // Ensure quantity does not exceed the available stock
            if (quantity > maxStock) {
                toastr.warning(
                    `You cannot add more than ${maxStock} items in stock!`,
                    "Quantity Limit Exceeded",
                    {
                        positionClass: "toast-top-right", // Toast position
                        timeOut: 5000, // Duration of the toast message
                    }
                );

                input.value = maxStock; // Set the quantity to the max stock
            }

            // Call the update totals function after quantity change
            updateCartTotals();
        });
    });

    // Initial total calculation
    updateCartTotals();
});

/*=============== IMAGE GALLERY ===============*/
function imgGallery() {
    const mainImg = document.querySelector(".details__img"),
        smallImg = document.querySelectorAll(".details__small-img");

    smallImg.forEach((img) => {
        img.addEventListener("click", function () {
            mainImg.src = this.src;
        });
    });
}

/*=============== SWIPER CATEGORIES ===============*/
document.addEventListener("DOMContentLoaded", function () {
    var swiperCategories = new Swiper(".categories__container", {
        spaceBetween: 16, // Adjust spacing between slides
        loop: true, // Infinite loop
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        breakpoints: {
            350: { slidesPerView: 2, spaceBetween: 16 },
            768: { slidesPerView: 3, spaceBetween: 16 },
            992: { slidesPerView: 4, spaceBetween: 20 },
            1200: { slidesPerView: 5, spaceBetween: 24 },
            1400: { slidesPerView: 6, spaceBetween: 24 },
        },
    });
});

/*=============== SWIPER PRODUCTS ===============*/
var swiperProducts = new Swiper(".new__container", {
    spaceBetween: 24,
    loop: true,
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },

    breakpoints: {
        768: {
            slidesPerView: 2,
            spaceBetween: 24,
        },
        992: {
            slidesPerView: 3,
            spaceBetween: 24,
        },
        1400: {
            slidesPerView: 4,
            spaceBetween: 24,
        },
    },
});

/*=============== PRODUCTS TABS ===============*/
const tabs = document.querySelectorAll("[data-target]"),
    tabContents = document.querySelectorAll("[content]");

tabs.forEach((tab) => {
    tab.addEventListener("click", () => {
        const target = document.querySelector(tab.dataset.target);
        // console.log(target);
        tabContents.forEach((tabContent) => {
            tabContent.classList.remove("active-tab");
        });

        target.classList.add("active-tab");

        tabs.forEach((tab) => {
            tab.classList.remove("active-tab");
        });

        tab.classList.add("active-tab");
    });
});
