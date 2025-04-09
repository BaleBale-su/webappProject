
document.getElementById("loginForm")?.addEventListener("submit", function (e) {
    e.preventDefault();
    const formData = new FormData(this);
    formData.append('ajax', 'login');

    fetch("index.php", { // ✅ ตรงกับไฟล์ที่มี PHP login
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            document.getElementById("loginMsg").innerText = data.message;
        }
    });
});

document.getElementById("logoutBtn")?.addEventListener("click", async () => {
    const formData = new FormData();
    formData.append("ajax", "logout");
    const response = await fetch("index.php", {
        method: "POST",
        body: formData,
    });
    const result = await response.json();
    if (result.success) {
        location.reload();
    }
});


// Initial setup - bind the login button if it exists
document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("loginBtn")?.addEventListener("click", function () {
        document.getElementById("loginModal").style.display = "block";
    });

    // If logout button exists on page load, bind it
    bindLogout();
});

// When the user clicks the button, open the modal
btn.onclick = function () {
    modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function () {
    modal.style.display = "none";
}

// When the user clicks anywhere outside the modal, close it
window.onclick = function (event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

function showBookingAlert(suiteName, price, area, bedrooms, guests) {
    const message = `
    Suite: ${suiteName}
    Price: ${price} per night
    Area: ${area}
    ${bedrooms}
    Capacity: ${guests}
    
    Thank you for your interest in booking the ${suiteName}. 
    Our reservations team will contact you shortly to confirm availability.
                `;
    alert(message);
}

function showGeneralBookingAlert() {
    alert("Thank you for your interest in booking at Elysian Private Hotel.\n\nPlease select one of our exclusive suites below or contact our concierge team at +1 (888) 123-4567 for personalized booking assistance.");
}

function checkAvailability(event) {
    event.preventDefault();

    const checkIn = document.getElementById('check-in').value;
    const checkOut = document.getElementById('check-out').value;

    if (!checkIn || !checkOut) {
        alert("Please select both check-in and check-out dates.");
        return false;
    }

    const checkInDate = new Date(checkIn);
    const checkOutDate = new Date(checkOut);

    if (checkInDate >= checkOutDate) {
        alert("Check-out date must be after check-in date.");
        return false;
    }

    const nights = Math.ceil((checkOutDate - checkInDate) / (1000 * 60 * 60 * 24));

    alert(`Thank you for checking availability.\n\n${nights} night${nights > 1 ? 's' : ''} selected from ${checkIn} to ${checkOut}.\n\nBoth Presidential Suite and Executive Suite are available for your selected dates. Please select a suite below to proceed with booking.`);

    return false;
}

function submitContactForm(event) {
    event.preventDefault();

    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const message = document.getElementById('message').value;

    if (!name || !email || !message) {
        alert("Please complete all fields.");
        return false;
    }

    alert(`Thank you for your message, ${name}.\n\nWe have received your inquiry and will respond to ${email} within 24 hours.`);

    event.target.reset();

    return false;
}

window.onload = function () {
    const today = new Date();
    const tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);

    const formatDate = (date) => {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    };

    document.getElementById('check-in').min = formatDate(today);
    document.getElementById('check-out').min = formatDate(tomorrow);
};