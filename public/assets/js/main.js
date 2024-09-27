/*=============== SHOW MENU ===============*/
const navMenu = document.getElementById('nav-menu'),
      navToggle = document.getElementById('nav-toggle'),
      navClose = document.getElementById('nav-close')

/*===== MENU SHOW =====*/
/* Validate if constant exists */
if(navToggle){
    navToggle.addEventListener('click', () =>{
        navMenu.classList.add('show-menu')
    })
}

/*===== MENU HIDDEN =====*/
/* Validate if constant exists */
if(navClose){
    navClose.addEventListener('click', () =>{
        navMenu.classList.remove('show-menu')
    })
}

document.addEventListener('DOMContentLoaded', function () {
    const userMenu = document.querySelector('.nav__item.dropdown');
    const dropdownContent = userMenu.querySelector('.dropdown-content');
    
    userMenu.addEventListener('click', function (e) {
        e.preventDefault(); // Prevent default behavior if necessary
        dropdownContent.classList.toggle('show-dropdown');
    });
});

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

// Search show
searchBtn.addEventListener('click', () =>{
   search.classList.add('show-search')
})

// Search hidden
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

/*=============== CHANGE BACKGROUND HEADER ===============*/
function scrollHeader(){
    const header = document.getElementById('header')
    // When the scroll is greater than 80 viewport height, add the scroll-header class to the header tag
    if(this.scrollY >= 80) header.classList.add('scroll-header'); else header.classList.remove('scroll-header')
}
window.addEventListener('scroll', scrollHeader)

/*=============== QUESTIONS ACCORDION ===============*/
const accordionItems = document.querySelectorAll('.questions__item')

accordionItems.forEach((item) =>{
    const accordionHeader = item.querySelector('.questions__header')

    accordionHeader.addEventListener('click', () =>{
        const openItem = document.querySelector('.accordion-open')

        toggleItem(item)

        if(openItem && openItem!== item){
            toggleItem(openItem)
        }
    })
})

const toggleItem = (item) =>{
    const accordionContent = item.querySelector('.questions__content')

    if(item.classList.contains('accordion-open')){
        accordionContent.removeAttribute('style')
        item.classList.remove('accordion-open')
    }else{
        accordionContent.style.height = accordionContent.scrollHeight + 'px'
        item.classList.add('accordion-open')
    }

}

/*=============== SCROLL SECTIONS ACTIVE LINK ===============*/
const sections = document.querySelectorAll('section[id]')

function scrollActive(){
    const scrollY = window.pageYOffset

    sections.forEach(current =>{
        const sectionHeight = current.offsetHeight,
              sectionTop = current.offsetTop - 58,
              sectionId = current.getAttribute('id')

        if(scrollY > sectionTop && scrollY <= sectionTop + sectionHeight){
            document.querySelector('.nav__menu a[href*=' + sectionId + ']').classList.add('active-link')
        }else{
            document.querySelector('.nav__menu a[href*=' + sectionId + ']').classList.remove('active-link')
        }
    })
}
window.addEventListener('scroll', scrollActive)

/*=============== SCROLL REVEAL ANIMATION ===============*/
const sr = ScrollReveal({
    origin: 'top',
    distance: '60px',
    duration: 2500,
    delay: 400,
    // reset: true
})

sr.reveal(`.home__data`)
sr.reveal(`.home__img`, {delay: 500})
sr.reveal(`.home__social`, {delay: 600})
sr.reveal(`.about__img, .contact__box`,{origin: 'left'})
sr.reveal(`.about__data, .contact__form`,{origin: 'right'})
sr.reveal(`.steps__card, .product__card, .questions__group, .footer`,{interval: 100})

// Scroll to products
window.addEventListener('load', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const scrollToProducts = urlParams.get('scrollToProducts');
 
    if (scrollToProducts === 'true') {
        setTimeout(function() {
            var element = document.getElementById('products');
            if (element) {
                element.scrollIntoView({ behavior: 'smooth' });
            }
        }, 100);
    }
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
        saveQuantityToDatabase(id, quantity);
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
            saveQuantityToDatabase(id, quantity);
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

function saveQuantityToDatabase(id, quantity) {
    fetch('/update-quantity', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // For Laravel
        },
        body: JSON.stringify({ id: id, quantity: quantity })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            toastr.success('Quantity updated successfully');
        } else {
            toastr.error('Failed to update quantity');
        }
    })
    .catch(error => toastr.error('Error: ' + error.message));
}

/*=============== SIDE CART MANAGEMENT ON PAGE LOAD ===============*/
window.addEventListener('load', function() {
    const keepCartOpen = localStorage.getItem('keepCartOpen');
    
    if (keepCartOpen === 'true') {
        // Remove the flag from localStorage
        localStorage.removeItem('keepCartOpen');
        
        // Open the side cart
        document.getElementById('cart').classList.add('show-cart');
    }
});

/*=============== SIDE CART ===============*/
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