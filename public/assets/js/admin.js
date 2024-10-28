/*=============== SHOW HIDDEN MENU ===============*/
const showMenu = (toggleId, navbarId) =>{
    const toggle = document.getElementById(toggleId),
    navbar = document.getElementById(navbarId)

    if(toggle && navbar){
        toggle.addEventListener('click', ()=>{
            /* Show menu */
            navbar.classList.toggle('show-menu')
            /* Rotate toggle icon */
            toggle.classList.toggle('rotate-icon')
        })
    }
}
showMenu('nav-toggle','nav')

/*=============== LINK ACTIVE ===============*/
const linkColor = document.querySelectorAll('.nav__link')

function colorLink(){
    linkColor.forEach(l => l.classList.remove('active-link'))
    this.classList.add('active-link')
}

linkColor.forEach(l => l.addEventListener('click', colorLink))

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

/*=============== VIEW ORDERS MANAGEMENT - STATUS BUTTON ===============*/
function updateStatus(status) {
    // Update the dropdown button text
    document.getElementById("statusButton").textContent = status;
    
    // Optionally, update the badge text and color
    const statusBadge = document.getElementById("statusBadge");
    statusBadge.textContent = status;

    // Update badge color based on status
    if (status === 'Completed') {
        statusBadge.classList.remove('bg-warning');
        statusBadge.classList.add('bg-success');
    } else if (status === 'Ready For Pickup') {
        statusBadge.classList.remove('bg-success');
        statusBadge.classList.add('bg-warning');
    }

    // Prevent default link action
    return false;
}