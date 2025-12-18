<!-- Add this line in your HTML head for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<button class="sidebar-toggle">☰</button>
<div class="sidebar-overlay"></div>
<div class="sidebar">
    <ul>
        <li>
            <a href="dashboard.php">
                <div class="menu-item">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </div>
            </a>
        </li>
        <li class="dropdown">
            <a href="#" onclick="toggleDropdown(event, 'users')">
                <div class="menu-item">
                    <i class="fas fa-users"></i>
                    <span>Users</span>
                </div>
                <span class="caret-icon">▼</span>
            </a>
            <ul id="users" class="submenu">
                <li><a href="view_users.php">View Users</a></li>
                <li><a href="add_users.php">Add Users</a></li>
            </ul>
        </li>
        <li>
            <a href="logout.php">
                <div class="menu-item">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Log Out</span>
                </div>
            </a>
        </li>
    </ul>
</div>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.querySelector(".sidebar");
    const toggle = document.querySelector(".sidebar-toggle");
    const overlay = document.querySelector(".sidebar-overlay");
    const submenus = document.querySelectorAll(".submenu");

    // Hide all submenus initially
    submenus.forEach(menu => menu.style.display = "none");

    // Toggle sidebar on mobile
    toggle.addEventListener("click", function () {
        sidebar.classList.toggle("active");
        overlay.classList.toggle("active");
    });

    // Close sidebar when clicking outside
    overlay.addEventListener("click", function () {
        sidebar.classList.remove("active");
        overlay.classList.remove("active");
    });

    // Auto-hide sidebar on resize for large screens
    window.addEventListener("resize", function () {
        if (window.innerWidth > 768) {
            sidebar.classList.remove("active");
            overlay.classList.remove("active");
        }
    });
});

// Function to toggle dropdown menus
function toggleDropdown(event, menuId) {
    event.preventDefault();
    
    let clickedDropdown = document.getElementById(menuId);
    let parentLi = clickedDropdown.closest(".dropdown");

    // Hide all other submenus except the clicked one
    document.querySelectorAll(".submenu").forEach(submenu => {
        if (submenu !== clickedDropdown) {
            submenu.style.display = "none";
            submenu.closest(".dropdown").classList.remove("active");
        }
    });

    // Toggle the clicked submenu
    let isOpen = clickedDropdown.style.display === "block";
    clickedDropdown.style.display = isOpen ? "none" : "block";
    parentLi.classList.toggle("active", !isOpen);
}
</script>
<style>
/* Sidebar Container */
.sidebar {
    width: 250px;
    background-color: #222831;
    color: #ffffff;
    height: 100vh;
    padding-top: 20px;
    position: fixed;
    left: 0;
    top: 0;
    overflow-y: auto;
    transition: transform 0.3s ease-in-out;
    z-index: 1000; /* Ensures sidebar is above other elements */
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2);
}

/* Sidebar Links */
.sidebar a {
    font-family: 'Poppins', sans-serif;
    text-decoration: none;
    color: #ffffff;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 20px;
    transition: background-color 0.3s ease-in-out;
}

/* Sidebar Hover Effect */
.sidebar a:hover {
    background-color: #393E46;
}

/* Sidebar List */
.sidebar ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

/* Sidebar List Items */
.sidebar li {
    cursor: pointer;
    position: relative;
}

/* Sidebar Menu Items */
.menu-item {
    display: flex;
    align-items: center;
    gap: 12px;
}

/* Sidebar Icons */
.menu-item i {
    width: 20px;
    text-align: center;
}

/* Submenu */
.submenu {
    display: none;
    padding-left: 20px;
    background-color: #31363F;
}

/* Submenu Links */
.submenu a {
    padding-left: 50px;
}

/* Dropdown Menu Caret (Arrow) */
.sidebar .dropdown a .caret-icon {
    margin-left: 10px;
    transition: transform 0.3s ease;
    font-size: 12px;
}

/* Rotate Arrow When Dropdown is Open */
.sidebar .dropdown.active a .caret-icon {
    transform: rotate(180deg);
}

/* Sidebar Toggle Button for Mobile */
.sidebar-toggle {
    display: none; /* Ensure it's visible */
    position: fixed;
    width: 35px;
    height: 35px;
    top: 0px;
    left: 0px;
    z-index: 1100; /* Higher than sidebar */
    background-color: #222831;
    color: white;
    border: none;
    padding: 5px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 20px;
    transition: background-color 0.3s ease-in-out;
}

.sidebar-toggle:hover {
    background-color: #393E46;
}


/* Sidebar Overlay for Mobile */
.sidebar-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 999; /* Below sidebar but above other content */
}

/* Mobile View - Hide Sidebar Initially */
@media (max-width: 1024px) {
    .sidebar {
        transform: translateX(-100%);
    }
    
    .sidebar.active {
        transform: translateX(0);
    }

    .sidebar-toggle {
        display: block;
    }

    .sidebar-overlay.active {
        display: block;
    }
}

@media (max-width: 768px) {
    .sidebar {
        width: 220px;
    }

    .sidebar-toggle {
        font-size: 18px;
        padding: 6px 12px;
    }

    .submenu a {
        font-size: 14px;
        padding-left: 30px;
    }
}

@media (max-width: 480px) {
    .sidebar {
        width: 200px;
    }

    .sidebar-toggle {
        font-size: 16px;
        padding: 6px 10px;
    }

    .submenu a {
        font-size: 13px;
        padding-left: 25px;
    }
}
</style>

