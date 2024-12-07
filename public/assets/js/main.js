
document.addEventListener('DOMContentLoaded', function () {
  const profileForm = document.getElementById('profile-update-form');
  if (profileForm) {
      profileForm.addEventListener('submit', function(event) {
          event.preventDefault();

          let formData = new FormData(this);
          const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
          const updateProfileUrl = "{{ url('/user/update-profile') }}"; 

          fetch(updateProfileUrl, {
              method: 'POST',
              body: formData,
              headers: {
                  'X-CSRF-TOKEN': csrfToken,
                  'Accept': 'application/json'
              }
          })
          .then(response => {
              if (!response.ok) {
                  return response.text().then(text => {
                      throw new Error(text);
                  });
              }
              return response.json();
          })
          .then(data => {
              if (data.success) {
                  document.getElementById('otp-modal').style.display = "block";
              } else {
                  toastr.error("There was an error updating your profile.");
              }
          })
          .catch(error => {
              console.error('Error:', error);
              toastr.error("There was an error updating your profile.");
          });
      });
  }

  // Handle OTP Modal Close
  const closeModal = document.querySelector('.close');
  closeModal.addEventListener('click', () => {
      document.getElementById('otp-modal').style.display = "none";
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


