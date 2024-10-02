<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- 
    - primary meta tags
  -->
  <title>JMA Trucks Solution</title>
  

  <!-- 0
    - favicon
  -->
  <link rel="shortcut icon" href="./favicon.svg" type="image/svg+xml">

  <!-- 
    - google font link
  -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@400;600;700&family=Mulish&display=swap"
    rel="stylesheet">

  <!-- 
    - material icon font
  -->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@40,600,0,0" />

  <!-- 
    - custom css link
  -->
  <link rel="stylesheet" href="./assets/css/style.css">

  <!-- 
    - preload images
  -->
  <link rel="preload" as="image" href="./assets/images/hero-banner.png">
  <link rel="preload" as="image" href="./assets/images/hero-bg.jpg">

</head>

<!-- 
    - #HEADER
-->

<header class="header" id="header">
    <div class="container">

        <a href="#" class="logo">
            <img src="./assets/images/jma.png" alt="JMA HOME" id="logo-img">
        </a>

        <nav class="navbar" data-navbar>
            <ul class="navbar-list">

                <li>
                    <a href="#" class="navbar-link"><i class="products-icon">🛒</i> Products</a>
                </li>

                <li>
                    <a href="reviews-page/reviews.php" class="navbar-link"><i class="reviews-icon">⭐</i> Reviews</a>
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
                        <li><a href="../auction/myaccount/myaccount.php" class="dropdown-item button"><i>👤</i> My Account</a></li>
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

<body>

  <main>
    <article>

      <!-- 
        - #HERO
      -->

      <section class="hero has-bg-image" aria-label="home" style="background-image: url('./assets/images/hero-bg.png')">
        <div class="container">

          <div class="hero-content">

            <p class="section-subtitle :dark">JMA Truck Solutions</p>

            <h1 class="h1 section-title">RELIABLE TRUCK AUCTIONS</h1>

            <p class="section-text">
              "Driven by quality, powered by performance"
            </p>

            <a href="live-auctions.php" class="btn">
              <span class="span">View Live Auctions</span>

              <span class="material-symbols-rounded">arrow_forward</span>
            </a>

          </div>

          <figure class="hero-banner" style="--width: 1228; --height: 789;">
            <img src="./assets/images/hero-banner.png" width="1228" height="789" alt="red motor vehicle"
              class="move-anim">
          </figure>

        </div>
      </section>





      <!-- 
        - #SERVICE
      -->

      <section class="section service has-bg-image" aria-labelledby="service-label"
        style="background-image: url('./assets/images/service-bg.jpg')">
        <div class="container">

          <p class="section-subtitle :light" id="service-label">truck Auction Services</p>

          <h2 class="h2 section-title">We Ensure Every Truck is Auction-Ready</h2>

          <ul class="service-list">

            <li>
              <div class="service-card">

                <figure class="card-icon">
                  <img src="./assets/images/services-1.png" width="110" height="110" loading="lazy" alt="Engine Repair">
                </figure>

                <h3 class="h3 card-title">Engine Inspection</h3>

                <p class="card-text">
                  Get a thorough inspection of the truck's engine before you bid. Ensure the engine is in top condition.
                </p>

              </div>
            </li>

            <li>
              <div class="service-card">

                <figure class="card-icon">
                  <img src="./assets/images/services-2.png" width="110" height="110" loading="lazy" alt="Brake Repair">
                </figure>

                <h3 class="h3 card-title">Brake System Check</h3>

                <p class="card-text">
                  Check the truck's brake system to ensure safety and reliability before making your purchase.
                </p>

              </div>
            </li>

            <li>
              <div class="service-card">

                <figure class="card-icon">
                  <img src="./assets/images/services-3.png" width="110" height="110" loading="lazy" alt="Tire Repair">
                </figure>

                <h3 class="h3 card-title">Tire Condition Review</h3>

                <p class="card-text">
                  Review the condition of the truck's tires, so you're fully aware of their longevity and performance.
                </p>

              </div>
            </li>

            <li>
              <div class="service-card">

                <figure class="card-icon">
                  <img src="./assets/images/services-4.png" width="110" height="110" loading="lazy"
                    alt="Battery Repair">
                </figure>

                <h3 class="h3 card-title">Battery Test</h3>

                <p class="card-text">
                  Test the battery life and performance to avoid any surprises after purchase.
                </p>

              </div>
            </li>

            <li class="service-banner">
              <img src="./assets/images/services-5.png" width="646" height="380" loading="lazy" alt="Red Car"
                class="move-anim">
            </li>

            <li>
              <div class="service-card">

                <figure class="card-icon">
                  <img src="./assets/images/services-6.png" width="110" height="110" loading="lazy"
                    alt="Steering Repair">
                </figure>

                <h3 class="h3 card-title">Steering Assessment</h3>

                <p class="card-text">
                  Assess the steering mechanism to ensure smooth operation during your test drive.
                </p>

              </div>
            </li>

          </ul>

        </div>
      </section>





      <!-- 
        - #ABOUT
      -->

      <section class="section about has-before" aria-labelledby="about-label">
        <div class="container">

          <figure class="about-banner">
            <img src="./assets/images/about-banner.png" width="540" height="540" loading="lazy"
              alt="vehicle repire equipments" class="w-100">
          </figure>

          <div class="about-content">

            <p class="section-subtitle :dark">About Us</p>

            <h2 class="h2 section-title">We’re Commited to Meet the quality</h2>

            <p class="section-text">
            JMA Trucks Solution has been at the forefront of implementing advanced business management practices. 
            This commitment has solidified our position as one of the largest companies dealing with dump trucks, mixer trucks and heavy equipment worldwide.
            </p>

            <p class="section-text"><br>
            We look forward to continuing this wonderful and mutually beneficial relationship in the future.
            </p>

            <ul class="about-list">

              <li class="about-item">
                <p>
                  <strong class="display-1 strong">1K+</strong> Happy Clients
                </p>
              </li>

              <li class="about-item">
                <p>
                  <strong class="display-1 strong">1K+</strong> Trucks Sold
                </p>
              </li>

              <li class="about-item">
                <p>
                  <strong class="display-1 strong">10+</strong> Years in Market
                </p>
              </li>

              <li class="about-item">
                <p>
                  <strong class="display-1 strong">99%</strong> Projects Completed
                </p>
              </li>

            </ul>

          </div>

        </div>
      </section>





      <!-- 
        - #WORK
      -->

      <section class="section work" aria-labelledby="work-label">
        <div class="container">

          <p class="section-subtitle :light" id="work-label">Top Dealer</p>

          <h2 class="h2 section-title">Leading Company worldwide</h2>

          <ul class="has-scrollbar">

            <li class="scrollbar-item">
              <div class="work-card">

                <figure class="card-banner img-holder" style="--width: 350; --height: 406;">
                  <img src="./assets/images/dump.png" width="350" height="406" loading="lazy" alt="Dump Trucks"
                    class="img-cover">
                </figure>

                <div class="card-content">
                  <p class="card-subtitle">Dealing</p>

                  <h3 class="h3 card-title">Dump Trucks</h3>
                  
                    <span class="material-symbols-rounded"></span>
                  
                </div>

              </div>
            </li>

            <li class="scrollbar-item">
              <div class="work-card">

                <figure class="card-banner img-holder" style="--width: 350; --height: 406;">
                  <img src="./assets/images/mixer.png" width="350" height="406" loading="lazy" alt="Mixer Trucks"
                    class="img-cover">
                </figure>

                <div class="card-content">
                  <p class="card-subtitle">Dealing</p>

                  <h3 class="h3 card-title">Mixer Trucks</h3>
                 
                    <span class="material-symbols-rounded"></span>
                  
                </div>

              </div>
            </li>

            <li class="scrollbar-item">
              <div class="work-card">

                <figure class="card-banner img-holder" style="--width: 350; --height: 406;">
                  <img src="./assets/images/heavy-equipment.png" width="350" height="406" loading="lazy" alt="Heavy Equipment"
                    class="img-cover">
                </figure>

                <div class="card-content">
                  <p class="card-subtitle">Dealing</p>

                  <h3 class="h3 card-title">Heavy Equipment</h3>
                  
                    <span class="material-symbols-rounded"></span>
                  
                </div>

              </div>
            </li>

          </ul>

        </div>
      </section>

    </article>
  </main>




  <!-- 
    - #FOOTER
  -->

  <footer class="footer">

    <div class="footer-top section">
      <div class="container">

        <div class="footer-brand">

          <a href="#" class="logo">
            <img src="./assets/images/jma.png" width="128" height="63" alt="JMA HOME">
          </a>

          <p class="footer-text">
          "Driven by quality, powered by performance."
          </p>

          <ul class="social-list">

    <li>
        <!-- Facebook link -->
        <a href="https://www.facebook.com/jmatrucks" class="social-link" target="_blank" rel="noopener noreferrer">
            <img src="./assets/images/facebook.svg" alt="Facebook">
        </a>
    </li>

    <li>
        <!-- Mailto link for email -->
        <a href="mailto:jmatrucks@gmail.com" class="social-link">
            <img src="./assets/images/instagram.svg" alt="Gmail">
        </a>
    </li>

</ul>


        </div>

        <ul class="footer-list">

          <li>
            <p class="h3">Opening Hours</p>
          </li>

          <li>
            <p class="p">Office Timings</p>

            <span class="span">9:00 AM – 6:00 PM</span>
          </li>

          <li>
            <p class="p">Regular Holiday</p>

            <span class="span">Sunday</span>
          </li>

        </ul>

        <ul class="footer-list">

          <li>
            <p class="h3">Contact Info</p>
          </li>

          <li>
            <a href="tel:+01234567890" class="footer-link">
              <span class="material-symbols-rounded">call</span>

              <span class="span">+63 9 05334 6136</span>
            </a>
          </li>
          
          <li>
            <a href="tel:+01234567890" class="footer-link">
              <span class="material-symbols-rounded">call</span>

              <span class="span">+63 9 09736 8246</span>
            </a>
          </li>


          <li>
            <a href="mailto:jmatrucks@gmail.com" class="footer-link">
              <span class="material-symbols-rounded">mail</span>

              <span class="span">jmatrucks@gmail.com</span>
            </a>
          </li>

          <li>
            <address class="footer-link address">
              <span class="material-symbols-rounded">location_on</span>

              <span class="span">Purok 1, Barangay Colo, Dinalupihan, Bataan, Philippines, 2110</span>
            </address>
          </li>

        </ul>

      </div>

      <img src="./assets/images/footer-shape-3.png" width="637" height="173" loading="lazy" alt="Shape"
        class="shape shape-3 move-anim">

    </div>

    <div class="footer-bottom">
      <div class="container">

        <p class="copyright">Copyright 2024, JMA Trucking Services, All Rights Reserved.</p>

        <img src="./assets/images/footer-shape-2.png" width="778" height="335" loading="lazy" alt="Shape"
          class="shape shape-2">

        <img src="./assets/images/foot-truck.png" width="805" height="652" loading="lazy" alt="Red Car"
          class="shape shape-1 move-anim">

      </div>
    </div>

  </footer>

  <!-- 
    - custom js link
  -->

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

  <script src="./assets/js/script.js"></script>

</body>

</html>
