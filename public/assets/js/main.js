// FILTER 
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
document.querySelectorAll('.brand-filter').forEach(filter => {
  filter.addEventListener('change', function () {
      const selectedCategories = Array.from(document.querySelectorAll('.brand-filter:checked')).map(input => input.value);

      console.log('Selected Categories:', selectedCategories); // Debug: Log selected categories

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
          console.log('Response:', data); // Debug: Log server response
          document.querySelector('.products__container').innerHTML = data.html;
          document.querySelector('.total__products span').innerText = data.count;
      })
      .catch(error => console.error('Error:', error));
  });
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


