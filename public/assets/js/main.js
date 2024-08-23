

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

/*===== CART SHOW =====*/
/* Validate if constant exists */
if(cartShop){
    cartShop.addEventListener('click', () =>{
      console.log('Cart button clicked');
        cart.classList.add('show-cart')
    })
}

/*===== CART HIDDEN =====*/
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
       // Check if filter is 'all' and apply an empty filter to show all products
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



