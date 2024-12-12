function filterCards() {
  const searchInput = document.getElementById('searchInput');
  const filter = searchInput.value.toUpperCase();
  const cards = document.getElementsByClassName('card');

  for (let i = 0; i < cards.length; i++) {
      const department = cards[i].getAttribute('data-department').toUpperCase();
      if (department.indexOf(filter) > -1) {
          cards[i].style.display = "";
      } else {
          cards[i].style.display = "none";
      }
  }
}

// Add event listener for the search input
document.getElementById('searchInput').addEventListener('keyup', filterCards);