<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Primary meta tags -->
  <title><?php echo $pageTitle ?? 'JMA Trucks Solution'; ?></title>

  <!-- Favicon -->
  <link rel="shortcut icon" href="../favicon.svg" type="image/svg+xml">

  <!-- Google fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@400;600;700&family=Mulish&display=swap"
    rel="stylesheet">

  <!-- Material icon font -->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@40,600,0,0" />

  <!-- Custom CSS -->
  <link rel="stylesheet" href="../assets/css/style.css">

  <!-- Preload images -->
  <link rel="preload" as="image" href="../assets/images/hero-banner.png">
  <link rel="preload" as="image" href="../assets/images/hero-bg.jpg">
</head>

<body>
<!-- HEADER -->
<header class="header" id="header">
    <div class="container">
        <a href="../index.php" class="logo">
            <img src="../assets/images/jma.png" alt="JMA HOME" id="logo-img">
        </a>

        <nav class="navbar" data-navbar>
            <ul class="navbar-list">
                <li>
                    <a href="#" class="navbar-link"><i class="products-icon">🛒</i> Products</a>
                </li>
                <li>
                    <a href="../reviews-page/reviews.php" class="navbar-link"><i class="reviews-icon">⭐</i> Reviews</a>
                </li>
                <li>
                    <a href="#" class="navbar-link"><i class="bell-icon">🔔</i> Notifications</a>
                </li>
                <div class="search-container">
                    <input type="text" class="search-input" placeholder="Search Trucks...">
                    <button class="search-button"><i class="truck-icon">🚚</i> Search</button>
                </div>
                <li>
                    <a href="#" class="navbar-link"><i class="profile-icon">👤</i> Profile</a>
                    <ul class="dropdown-menu">
<<<<<<< Updated upstream
                        <li><a href="../myaccount/myaccount.php" class="dropdown-item button"><i>👤</i> My Account</a></li>
=======
                        <li><a href="../auction/myaccount/myaccount.php" class="dropdown-item button"><i>👤</i> My Account</a></li>
>>>>>>> Stashed changes
                        <li><a href="#" class="dropdown-item button"><i>📦</i>My Bids</a></li>
                        <li><a href="../auction/login/login.php" class="dropdown-item button"><i>🔓</i>Logout</a></li>
                    </ul>
                </li>
            </ul>
        </nav>

        <button class="nav-toggle-btn" aria-label="toggle menu">
            <span class="nav-toggle-icon icon"></span>
            <span class="nav-toggle-icon icon"></span>
            <span class="nav-toggle-icon icon"></span>
        </button>
    </div>
</header>


<!-- JavaScript code from index.php for dynamic header and menu -->
<script>
    document.querySelectorAll('.navbar-list li').forEach(item => {
        item.addEventListener('mouseover', () => {
            const dropdown = item.querySelector('.dropdown-menu');
            if (dropdown) dropdown.style.display = 'block';
        });

        item.addEventListener('mouseout', () => {
            const dropdown = item.querySelector('.dropdown-menu');
            if (dropdown) dropdown.style.display = 'none';
        });
    });

    // Get the header
    const header = document.getElementById("header");
    const logoImg = document.getElementById("logo-img");

    // Listen to the scroll event
    window.addEventListener("scroll", function() {
        if (window.scrollY > 50) { // If scrolled down more than 50px
            header.classList.add("shrink");
        } else {
            header.classList.remove("shrink");
        }
    });

    // JavaScript for the toggle button to show/hide the navbar
    const toggleButton = document.querySelector('.nav-toggle-btn');
    const navbar = document.querySelector('.navbar');

    toggleButton.addEventListener('click', () => {
        navbar.classList.toggle('active');
    });

    // Add an event listener to toggle the dropdown in mobile view
    const profileLink = document.querySelector('.navbar-link');
    const dropdownMenu = document.querySelector('.dropdown-list');

    profileLink.addEventListener('click', function (e) {
        e.preventDefault();
        dropdownMenu.classList.toggle('active');
    });
</script>

<!-- Include other content after the header -->
