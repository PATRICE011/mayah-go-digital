

/*=============== SHOW MENU ===============*/
const navMenu = document.getElementById('nav-menu'),
      navToggle = document.getElementById('nav-toggle'),
      navClose = document.getElementById('nav-close')

/* Menu show */
navToggle.addEventListener('click', () =>{
   navMenu.classList.add('show-menu')
})

/* Menu hidden */
navClose.addEventListener('click', () =>{
   navMenu.classList.remove('show-menu')
})
/*=============== SHOW CART ===============*/
const cart = document.getElementById('cart'),
      cartShop = document.getElementById('cart-shop'),
      cartClose = document.getElementById('cart-close')

/*=============== CART SHOW ===============*/
/* Validate if constant exists */
if(cartShop){
    cartShop.addEventListener('click', () =>{
      console.log('Cart button clicked');
        cart.classList.add('show-cart')
    })
}

/*=============== CART HIDDEN ===============*/
/* Validate if constant exists */
if(cartClose){
    cartClose.addEventListener('click', () =>{
        cart.classList.remove('show-cart')
    })
}
/*=============== MIXITUP FILTER PRODUCTS ===============*/

document.addEventListener('DOMContentLoaded', function() {
   // Initialize MixItUp
   let mixerProducts = mixitup('.products__content', {
       selectors: {
           target: '.products__card'
       },
       animation: {
           duration: 300
       }
   });

   // Get filter links
   const linkProducts = document.querySelectorAll('.products__item');

   // Function to set active class and apply filter
   function activeProducts() {
       linkProducts.forEach(l => l.classList.remove('active-product'));
       this.classList.add('active-product');

       // Apply filter
       const filter = this.getAttribute('data-filter');
    //    Check if filter is 'all' and apply an empty filter to show all products
       mixerProducts.filter(filter === '.all' ? '' : filter);
   }

   // Add event listeners to filter links
   linkProducts.forEach(l => l.addEventListener('click', activeProducts));
});


/*=============== SEARCH ===============*/
const search = document.getElementById('search'),
      searchBtn = document.getElementById('search-btn'),
      searchClose = document.getElementById('search-close')

/* Search show */
searchBtn.addEventListener('click', () =>{
   search.classList.add('show-search')
})

/* Search hidden */
searchClose.addEventListener('click', () =>{
   search.classList.remove('show-search')
})


/*=============== LOGIN ===============*/
const login = document.getElementById('login'),
      loginBtn = document.getElementById('login-btn'),
      loginClose = document.getElementById('login-close')

/* Login show */
loginBtn.addEventListener('click', () =>{
   login.classList.add('show-login')
})

/* Login hidden */
loginClose.addEventListener('click', () =>{
   login.classList.remove('show-login')
})

function navigateToPage() {
   window.location.href = "register.html";
}


/*=============== SHOW SCROLL UP ===============*/ 
const scrollUp = () =>{
	const scrollUp = document.getElementById('scroll-up')
    // When the scroll is higher than 350 viewport height, add the show-scroll class to the a tag with the scrollup class
	this.scrollY >= 350 ? scrollUp.classList.add('show-scroll')
						: scrollUp.classList.remove('show-scroll')
}
window.addEventListener('scroll', scrollUp)

/*=============== SCROLL SECTIONS ACTIVE LINK ===============*/
const sections = document.querySelectorAll('section[id]')
    
const scrollActive = () =>{
  	const scrollDown = window.scrollY

	sections.forEach(current =>{
		const sectionHeight = current.offsetHeight,
			  sectionTop = current.offsetTop - 58,
			  sectionId = current.getAttribute('id'),
			  sectionsClass = document.querySelector('.nav__menu a[href*=' + sectionId + ']')

		if(scrollDown > sectionTop && scrollDown <= sectionTop + sectionHeight){
			sectionsClass.classList.add('active-link')
		}else{
			sectionsClass.classList.remove('active-link')
		}                                                    
	})
}
window.addEventListener('scroll', scrollActive)

/*=============== SCROLL REVEAL ANIMATIONS ===============*/
const sr = ScrollReveal({
   origin: 'top',
   distance: '60px',
   duration: 2500,
   delay: 300,
   // reset: true
})

sr.reveal('.home__data')
sr.reveal('.home__circle, .home__img', {delay: 600, scale: 0.5})
sr.reveal('.home__chips-1, .home__chips-2, .home__chips-3', {delay: 1000, interval: 600})
sr.reveal('.home__leaf', {delay: 1200})
sr.reveal('.home__tomato-1, .home__tomato-2', {delay: 1400, interval: 100})

sr.reveal('.about__data', {delay: 800})
sr.reveal('.about__img', {delay: 1200})

sr.reveal('.products', {delay: 800})

// search product 
window.addEventListener('load', function() {
   setTimeout(function() {
       var element = document.getElementById('products');
       if (element) {
           console.log("Element found:", element);
           element.scrollIntoView({ behavior: 'smooth' });
       } else {
           console.log("Element not found for ID: products");
       }
   }, 100);
});


// Add to Cart function using AJAX
document.querySelectorAll('.add-to-cart-btn').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        
        const productId = this.getAttribute('data-id');
        
        // Set a flag in localStorage to keep the cart open
        localStorage.setItem('keepCartOpen', 'true');
        
        // AJAX request to add product to cart
        fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ product_id: productId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the cart contents dynamically
                document.querySelector('.cart__container').innerHTML = data.cart_html;
                document.querySelector('.cart__prices-total').textContent = `$${data.total}`;
                document.querySelector('.cart__prices-item').textContent = `${data.items} items`;

                // Keep the cart open
                document.getElementById('cart').classList.add('show-cart');
            } else {
                alert('Failed to add product to cart');
            }
        })
        .catch(error => {
            console.error('Error adding product to cart:', error);
        });
    });
});

document.querySelectorAll('.increase').forEach(button => {
    button.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        const quantityElement = document.getElementById(`quantity-${id}`);
        let quantity = parseInt(quantityElement.textContent);
        quantityElement.textContent = ++quantity;
        document.getElementById(`input-quantity-${id}`).value = quantity;

        updateTotalPrice();
    });
});

document.querySelectorAll('.decrease').forEach(button => {
    button.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        const quantityElement = document.getElementById(`quantity-${id}`);
        let quantity = parseInt(quantityElement.textContent);
        if (quantity > 1) {
            quantityElement.textContent = --quantity;
            document.getElementById(`input-quantity-${id}`).value = quantity;

            updateTotalPrice();
        }
    });
});

function updateTotalPrice() {
    let total = 0;
    document.querySelectorAll('.cart__card').forEach(card => {
        const id = card.querySelector('.decrease').getAttribute('data-id');
        const price = parseFloat(document.getElementById(`price-${id}`).textContent.replace('$', ''));
        const quantity = parseInt(document.getElementById(`quantity-${id}`).textContent);
        total += price * quantity;
    });
    document.querySelector('.cart__prices-total').textContent = `$${total.toFixed(2)}`;
}

// Side cart management on page load
window.addEventListener('load', function() {
    const keepCartOpen = localStorage.getItem('keepCartOpen');
    
    if (keepCartOpen === 'true') {
        // Remove the flag from localStorage
        localStorage.removeItem('keepCartOpen');
        
        // Open the side cart
        document.getElementById('cart').classList.add('show-cart');
    }
});

// Side cart

document.querySelectorAll('.cart__amount-trash').forEach(button => {
    button.addEventListener('click', function() {
        // Set a flag in localStorage to keep the cart open
        localStorage.setItem('keepCartOpen', 'true');
        
        // Submit the form to delete the item
        const itemId = this.getAttribute('data-id');
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/cart/${itemId}`;
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        
        form.appendChild(csrfInput);
        form.appendChild(methodInput);
        
        document.body.appendChild(form);
        form.submit();
    });
});

window.addEventListener('load', function() {
    const keepCartOpen = localStorage.getItem('keepCartOpen');
    
    if (keepCartOpen === 'true') {
        // Remove the flag from localStorage
        localStorage.removeItem('keepCartOpen');
        
        // Open the side cart
        document.getElementById('cart').classList.add('show-cart'); // Add 'show-cart' class to open the cart
    }
});