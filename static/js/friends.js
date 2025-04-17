const allUsers = [
    "Alice", "Bob", "Charlie", "David", "Eva", "Fiona", "George", "Helen", "Ivan", "Julia"
  ];

  let friends = ["Alice", "Charlie", "Eva", "Bob", "Fiona", "George", "Helen"];

  const friendsGrid = document.getElementById('friendsGrid');
  const searchInput = document.getElementById('searchInput');
  const searchResults = document.getElementById('searchResults');

  function getProfilePic(name) {
    return `../static/assets/profile-pic.jpg`;
  }

  function renderFriends() {
    friendsGrid.innerHTML = '';
    friends.forEach(name => {
      const card = document.createElement('div');
      card.className = 'friend-card';
      card.innerHTML = `
        <img src="${getProfilePic(name)}" alt="${name}">
        <div class="friend-name">${name}</div>
        <button class="unfriend" onclick="unfriend('${name}')">Unfriend</button>
      `;
      friendsGrid.appendChild(card);
    });
  }

  function unfriend(name) {
    friends = friends.filter(friend => friend !== name);
    renderFriends();
    renderSearchResults();
  }

  function addFriend(name) {
    if (!friends.includes(name)) {
      friends.push(name);
      renderFriends();
      renderSearchResults();
    }
  }

  function renderSearchResults() {
    const term = searchInput.value.toLowerCase();
    searchResults.innerHTML = '';

    allUsers
      .filter(name => name.toLowerCase().includes(term) && !friends.includes(name))
      .forEach(name => {
        const card = document.createElement('div');
        card.className = 'friend-card';
        card.innerHTML = `
          <img src="${getProfilePic(name)}" alt="${name}">
          <div class="friend-name">${name}</div>
          <button onclick="addFriend('${name}')">Add Friend</button>
        `;
        searchResults.appendChild(card);
      });
  }

  searchInput.addEventListener('input', renderSearchResults);

  renderFriends();