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


