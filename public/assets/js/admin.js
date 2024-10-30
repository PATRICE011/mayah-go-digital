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

// function updateStatus(newStatus) {
//     // Get the status badge element
//     const statusBadge = document.getElementById('statusBadge');
    
//     // Update the badge text and color based on the new status
//     statusBadge.textContent = newStatus;
//     statusBadge.className = ''; // Reset any existing classes
//     statusBadge.classList.add('badge', 'bg-danger'); // Change 'bg-warning' if you want a different color

//     // Optional: Send an AJAX request to update the status in the backend
//     // If you want to save this status change, send an AJAX POST request here
// }

/*=============== VIEW ORDERS MANAGEMENT - STATUS BUTTON ===============*/
function updateStatus(newStatus) {
    // Get elements for the status button and badge
    const statusButton = document.getElementById("statusButton");
    const statusBadge = document.getElementById("statusBadge");

    // Update the button text to reflect the new status
    statusButton.textContent = newStatus;

    // Update the badge text and reset classes to adjust color based on status
    statusBadge.textContent = newStatus;
    statusBadge.className = 'badge'; // Reset existing classes

    // Update the badge color based on the new status
    if (newStatus === 'Completed') {
        statusBadge.classList.add('bg-success');
    } else if (newStatus === 'Ready For Pickup') {
        statusBadge.classList.add('bg-warning');
    } else if (newStatus === 'Refunded') {
        statusBadge.classList.add('bg-danger');
    } else if (newStatus === 'Rejected') {
        statusBadge.classList.add('bg-danger');
    } else {
        statusBadge.classList.add('bg-secondary'); // Default for other statuses
    }

    // Optional: AJAX call to update the backend status (if necessary)
    // Example: You could implement an AJAX POST request here if needed
}