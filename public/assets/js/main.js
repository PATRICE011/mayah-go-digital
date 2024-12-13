// ORDERS TABS SWITCHING
document.addEventListener('DOMContentLoaded', function () {
    // Function to show order details
    window.showOrderDetails = function (event, orderId) {
        event.preventDefault(); // Prevent default link behavior

        // Activate the "Orders" tab
        const tabs = document.querySelectorAll('.account__tab');
        const contents = document.querySelectorAll('.tab__content');
        const ordersContent = document.querySelector('#orders');

        // Remove active class from all tabs and contents
        tabs.forEach(tab => tab.classList.remove('active-tab'));
        contents.forEach(content => content.classList.remove('active-tab'));

        // Add active class to the Orders tab
        const ordersTab = document.querySelector('[data-target="#orders"]');
        ordersTab?.classList.add('active-tab');
        ordersContent?.classList.add('active-tab');

        // Fetch and display order details
        fetch(`/user/order-status/orderdetails/${orderId}?active_tab=orders`)
            .then(response => {
                if (!response.ok) throw new Error('Failed to fetch order details.');
                return response.text(); // Expect an HTML response
            })
            .then(html => {
                // Replace the content inside the "Orders" tab
                ordersContent.innerHTML = html;

                // Attach "Back to Orders" event listener
                attachBackToOrdersListener();
            })
            .catch(error => {
                console.error('Error loading order details:', error);
                toastr.error('Failed to load order details. Please try again.');
            });
    };

    // Function to go back to the orders list
    window.backToOrders = function () {
        // Activate the "Orders" tab
        const tabs = document.querySelectorAll('.account__tab');
        const contents = document.querySelectorAll('.tab__content');
        const ordersContent = document.querySelector('#orders');

        tabs.forEach(tab => tab.classList.remove('active-tab'));
        contents.forEach(content => content.classList.remove('active-tab'));

        const ordersTab = document.querySelector('[data-target="#orders"]');
        ordersTab?.classList.add('active-tab');
        ordersContent?.classList.add('active-tab');

        // Fetch and display the orders list
        fetch('/user/myaccount?active_tab=orders')
            .then(response => {
                if (!response.ok) throw new Error('Failed to load orders list.');
                return response.text();
            })
            .then(html => {
                ordersContent.innerHTML = html;

                // Reattach event listeners for "View Order" buttons
                attachOrderDetailsListeners();
            })
            .catch(error => {
                console.error('Error loading orders list:', error);
                toastr.error('Failed to load orders list. Please try again.');
            });
    };

    // Attach "Back to Orders" event listener
    function attachBackToOrdersListener() {
        const backToOrdersButton = document.querySelector('.back-to-orders');
        if (backToOrdersButton) {
            backToOrdersButton.addEventListener('click', backToOrders);
        }
    }

    // Attach "View Order" event listeners
    function attachOrderDetailsListeners() {
        document.querySelectorAll('.view__order').forEach(button => {
            button.addEventListener('click', function (e) {
                const orderId = this.getAttribute('data-order-id');
                showOrderDetails(e, orderId);
            });
        });
    }

    // Initial attach of order details listeners
    attachOrderDetailsListeners();
});



// PRODUCT FILTER AND ADD TO CART
document.addEventListener('DOMContentLoaded', function () {
    // Handle product filtering
    document.querySelectorAll('.brand-filter').forEach(filter => {
        filter.addEventListener('change', function () {
            const selectedCategories = Array.from(document.querySelectorAll('.brand-filter:checked')).map(input => input.value);

            fetch('/filter-products', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ categories: selectedCategories })
            })
                .then(response => response.json())
                .then(data => {
                    // Replace product grid with filtered products
                    document.querySelector('.products__container').innerHTML = data.html;

                    // Update total products count
                    document.querySelector('.total__products span').innerText = data.count;

                    // Reattach Add to Cart event listeners for newly loaded products
                    attachAddToCartListeners();
                })
                .catch(error => console.error('Error:', error));
        });
    });

    // Function to attach Add to Cart event listeners
    function attachAddToCartListeners() {
        document.querySelectorAll('.action__btn.cart__btn').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();

                const form = button.closest('form');
                const url = form.getAttribute('data-url');
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: new URLSearchParams(new FormData(form))
                })
                    .then(response => {
                        // If the response is successful, parse JSON
                        if (response.ok) {
                            return response.json();
                        } else {
                            // Handle errors (like product already in cart)
                            return response.json().then(err => {
                                throw err;
                            });
                        }
                    })
                    .then(data => {
                        // Update cart count dynamically
                        if (data.cartCount !== undefined) {
                            document.getElementById('cart-count').innerText = data.cartCount;
                        }

                        // Show success notification
                        toastr.success(data.message || 'Product added to cart!');
                    })
                    .catch(error => {
                        // Specific handling for "already in cart" error
                        if (error.error === 'This product is already in your cart.') {
                            toastr.warning(error.error);
                        } else {
                            // Generic error notification
                            toastr.error(error.error || 'An error occurred while adding the product to the cart.');
                        }
                        console.error('Error:', error);
                    });
            });
        });
    }

    // Attach event listeners for initially loaded products
    attachAddToCartListeners();
});
// PRODUCT DETAILS REALTIME QUANTITY UPDATE
document.addEventListener('DOMContentLoaded', function () {
  // Attach event listeners to all quantity inputs
  document.querySelectorAll('.quantity').forEach(input => {
      input.addEventListener('input', function () {
          const productId = this.dataset.productId;
          const hiddenInput = document.getElementById(`quantity-${productId}`);
          hiddenInput.value = this.value; // Sync hidden input value
      });
  });
});

// UPDATE PROFILE
$(document).ready(function () {
  // Handle tab clicks
  $('.account__tab').on('click', function () {
      // Remove active class from all tabs and contents
      $('.account__tab').removeClass('active-tab');
      $('.tab__content').removeClass('active-tab');

      // Add active class to the clicked tab and corresponding content
      $(this).addClass('active-tab');
      const target = $(this).data('target'); // Get target content's ID
      $(target).addClass('active-tab');
  });

  // Use the activeTab variable from the Blade script
  if (typeof activeTab !== 'undefined' && activeTab) {
      // Remove active class from all tabs and contents
      $('.account__tab').removeClass('active-tab');
      $('.tab__content').removeClass('active-tab');

      // Set the active tab and content based on session
      const targetTab = $(`[data-target="#${activeTab}"]`);
      const targetContent = $(`#${activeTab}`);
      if (targetTab.length && targetContent.length) {
          targetTab.addClass('active-tab');
          targetContent.addClass('active-tab');
      } else {
          // Default to Dashboard
          $('[data-target="#dashboard"]').addClass('active-tab');
          $('#dashboard').addClass('active-tab');
      }
  }

  // Handle Get OTP button click
  $('#get-otp-button').on('click', function () {
      // Fetch data attributes for configuration
      const url = $(this).data('url');
      const action = $(this).data('action');
      const csrfToken = $(this).data('csrf');

      // Send AJAX POST request to the server
      $.ajax({
          url: url,
          method: 'POST',
          data: {
              action: action,
              _token: csrfToken // CSRF token for security
          },
          success: function (response) {
              // Handle success
              if (response.message) {
                  toastr.success(response.message);
              }
          },
          error: function (xhr) {
              // Handle error
              const errorMessage = xhr.responseJSON?.error || 'An unexpected error occurred.';
              toastr.error(errorMessage);
          }
      });
  });
});
// UPDATE CART QUANTITY
document.addEventListener('DOMContentLoaded', () => {
  const quantityInputs = document.querySelectorAll('.quantity');

  quantityInputs.forEach(input => {
      input.addEventListener('input', function () { // Use 'input' event for real-time updates
          const cartItemId = this.name.match(/\d+/)[0]; // Extract cart_item_id from input name
          const quantity = parseInt(this.value);

          if (isNaN(quantity) || quantity < 1) {
              console.warn('Invalid quantity entered!');
              return;
          }

          // Send AJAX request to update quantity
          fetch('user/cart/update-quantity', {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // CSRF token for Laravel
              },
              body: JSON.stringify({
                  cart_item_id: cartItemId,
                  quantity: quantity
              })
          })
          .then(response => response.json())
          .then(data => {
              if (data.success) {
                  console.log('Quantity updated successfully!');
              } else {
                  console.error('Failed to update quantity:', data.message);
              }
          })
          .catch(error => console.error('Error:', error));
      });
  });
});

// DYNAMIC STOCK TRACKING IN CART PAGE
document.addEventListener('DOMContentLoaded', function () {
  const quantityInputs = document.querySelectorAll('.quantity');
  const subtotalElement = document.getElementById('subtotal');
  const totalElement = document.getElementById('total');

  // Function to update totals dynamically
  function updateCartTotals() {
      let total = 0;

      // Loop through each cart item row
      document.querySelectorAll('.cart-item-row').forEach(row => {
          const quantityInput = row.querySelector('.quantity');
          const priceElement = row.querySelector('.table__price');
          const subtotalElement = row.querySelector('.table__subtotal');
          const price = parseFloat(priceElement.dataset.price) || 0; // Default to 0 if invalid price
          const quantity = parseInt(quantityInput.value) || 0; // Default to 0 if invalid quantity

          // Calculate the subtotal for this row
          const rowSubtotal = price * quantity;
          subtotalElement.textContent = `₱ ${rowSubtotal.toFixed(2)}`;

          total += rowSubtotal;
      });

      // Update the grand total
      document.getElementById('subtotal').textContent = `₱ ${total.toFixed(2)}`;
      document.getElementById('total').textContent = `₱ ${total.toFixed(2)}`;
  }

  // Add event listeners for quantity changes
  quantityInputs.forEach(input => {
      input.addEventListener('input', function (event) {
          const maxStock = parseInt(input.dataset.stock); // Get max stock
          let quantity = parseInt(input.value); // Get the entered quantity

          // Ensure quantity does not exceed the available stock
          if (quantity > maxStock) {
              toastr.warning(`You cannot add more than ${maxStock} items in stock!`, 'Quantity Limit Exceeded', {
                  positionClass: "toast-top-right", // Toast position
                  timeOut: 5000, // Duration of the toast message
              });

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
function imgGallery(){
  const mainImg = document.querySelector('.details__img'),
  smallImg = document.querySelectorAll('.details__small-img');

  smallImg.forEach((img) => {
    img.addEventListener('click', function(){
      mainImg.src = this.src;
    });
  });
}

/*=============== SWIPER CATEGORIES ===============*/
var swiperCategories = new Swiper('.categories__container', {
    spaceBetween: 24,
    loop: true,
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },

    breakpoints: {
      350: {
        slidesPerView: 2,
        spaceBetween: 24,
      },

      768: {
        slidesPerView: 3,
        spaceBetween: 24,
      },

      992: {
        slidesPerView: 2,
        spaceBetween: 24,
      },

      1200: {
        slidesPerView: 5,
        spaceBetween: 24,
      },

      1400: {
        slidesPerView: 6,
        spaceBetween: 24,
      },
    },
});

/*=============== SWIPER PRODUCTS ===============*/
var swiperProducts = new Swiper('.new__container', {
    spaceBetween: 24,
    loop: true,
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
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
const tabs = document.querySelectorAll('[data-target]'),
    tabContents = document.querySelectorAll('[content]');

tabs.forEach((tab) => {
    tab.addEventListener('click', () =>{
        const target = document.querySelector(tab.dataset.target);
        // console.log(target);
        tabContents.forEach((tabContent) => {
            tabContent.classList.remove('active-tab');
        });

        target.classList.add('active-tab');

        tabs.forEach((tab) => {
            tab.classList.remove('active-tab');
        });

        tab.classList.add('active-tab');
    });
});


