function filterCards() {
    const input = document.getElementById('searchInput').value.toUpperCase(); // Get the user input and convert to uppercase
    const cards = document.querySelectorAll('.card'); // Select all cards
  
    cards.forEach((card) => {
      const department = card.getAttribute('data-department').toUpperCase(); // Get department tag
      if (department.includes(input) || input === "") {
        // Show card if it matches input or if input is empty
        card.style.display = "";
      } else {
        // Hide card if it doesn't match
        card.style.display = "none";
      }
    });
  }
  