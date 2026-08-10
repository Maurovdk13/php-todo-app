const userSearch = document.querySelector("#userSearch");
const receiverId = document.querySelector("#receiverId");
const searchResults = document.querySelector("#searchResults");
const balanceAmount = document.querySelector("#balanceAmount");

if(userSearch) {
    userSearch.addEventListener("input", async function() {
        const query = userSearch.value.trim();
        receiverId.value = "";
        searchResults.innerHTML = "";

        if(query.length < 2) {
            return;
        }

        const response = await fetch("../api/search-users.php?q=" + encodeURIComponent(query));
        const users = await response.json();

        users.forEach(function(user) {
            const button = document.createElement("button");
            button.type = "button";
            button.className = "search-result";
            button.textContent = user.firstname + " " + user.lastname;

            button.addEventListener("click", function() {
                userSearch.value = user.firstname + " " + user.lastname;
                receiverId.value = user.id;
                searchResults.innerHTML = "";
            });

            searchResults.appendChild(button);
        });
    });
}

async function updateBalance() {
    if(!balanceAmount) {
        return;
    }

    const response = await fetch("../api/balance.php");
    const data = await response.json();

    if(data.balance) {
        balanceAmount.textContent = data.balance + " XD";
    }
}

setInterval(updateBalance, 10000);
