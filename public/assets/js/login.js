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
      // const homeIndexUrl = "{{ route('home.index') }}";

/* Login show */

loginBtn.addEventListener('click', () =>{
   login.classList.add('show-login')
})


/* Login hidden */
loginClose.addEventListener('click', () =>{
   // redirectToIndex()
   login.classList.remove('show-login');
   window.location.href = "/";
   
});


function navigateToPage() {
   window.location.href = "register.html";
}

/*=============== MIXITUP FILTER PRODUCTS ===============*/
let mixerProducts = mixitup('.products__content', {
   selectors: {
       target: '.products__card'
   },
   animation: {
       duration: 300
   }
});

/* Default filter products */ 
mixerProducts.filter('.delicacies')

/* Link active products */ 
const linkProducts = document.querySelectorAll('.products__item')

function activeProducts(){
   linkProducts.forEach(l=> l.classList.remove('active-product'))
   this.classList.add('active-product')
}

linkProducts.forEach(l=> l.addEventListener('click', activeProducts))

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