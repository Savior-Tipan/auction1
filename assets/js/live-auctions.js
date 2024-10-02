// assets/js/live-auctions.js

document.addEventListener('DOMContentLoaded', function() {
    fetch('fetch-trucks.php') // Replace with your server-side script to fetch trucks data
      .then(response => response.json())
      .then(data => {
        const auctionCardsContainer = document.getElementById('auction-cards');
        
        data.forEach(truck => {
          const card = document.createElement('div');
          card.classList.add('auction-card');
  
          card.innerHTML = `
            <img src="${truck.image_url}" alt="${truck.name}" class="truck-image">
            <div class="card-info">
              <h3 class="truck-name">${truck.name}</h3>
              <p class="auction-time">Auction Time: ${truck.auction_time}</p>
              <p class="starting-price">Starting Price: $${truck.starting_price}</p>
              <a href="truck-details.html?id=${truck.id}" class="btn btn-primary">View Details</a>
            </div>
          `;
  
          auctionCardsContainer.appendChild(card);
        });
      })
      .catch(error => console.error('Error fetching trucks:', error));
  });
  