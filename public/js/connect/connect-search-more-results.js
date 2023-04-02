window.addEventListener("load", () => {
    let actionMoreResults = document.getElementById("action-more-results");
    let usersListContainer = document.getElementById("user-list-container");

    //Event to button more results
    let spinnerMoreResults = document.getElementById(
        "container-spinner-more-results"
    );
    let containerActionMoreResults = document.getElementById(
        "container-action-more-results"
    );

    actionMoreResults.addEventListener("click", () => {
        //Mostrar spinner y ocultar action more results
        spinnerMoreResults.classList.remove("hidden");
        containerActionMoreResults.classList.add("hidden");
        //

        axios
            .post("search-more", {})
            .then((res) => {
                //Oculto spinner y muestro action more results
                spinnerMoreResults.classList.add("hidden");
                containerActionMoreResults.classList.remove("hidden");
                //Recupera data
                let data = res.data;
                if (data.users.length !== 0) {
                    //Inserta nuevos usuarios
                    insertNewUser(data.users, usersListContainer);
                    // add events listener to action de new users
                    addEventToFollowButtons();
                    addEventToUsersCard();
                } else {
                    actionMoreResults.innerText = "No more results found";
                }
            })
            .catch((error) => {});
    });
});

/*********************************
 * Inserta nuevos usuarios en el contenedor de usuarios
 */
const insertNewUser = (users, usersListContainer) => {
    //Inserta
    users.map((user) => {
        //Inserta nuevos usuarios en el container de lista de usuarios
        usersListContainer.insertAdjacentHTML(
            "beforeend",
            generateNewUserCard(user)
        );
    });

    //
};

/*********************************
 * Genera nueva card para el usuario
 */
const generateNewUserCard = (user) => {
    let hiddenLabelFollowing = user.followers.length === 0 ? "hidden" : "flex";

    let labelFollowing = `<div id='following-chip-${user.id}' class="${hiddenLabelFollowing}  justify-center items-center m-1  py-2 px-3 rounded-full bg-gray-100 text-black-300 ">
                            <div class="text-sm font-medium leading-none max-w-full flex-initial">Following</div>
                          </div>`;

    let buttonFollow =
        user.followers.length !== 0
            ? ""
            : `<button data-id='${user.id}' id="follow-button" type="submit"
                       class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                >
                    <svg id="follow-icon-${user.id}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                    </svg>
                    <svg id="follow-spinner-${user.id}" class="animate-spin -ml-1 mr-2 h-4 w-4 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Follow
                </button>`;

    let userCard = `  <div class="py-0 ">
                            <div class="" id="user-card" data-id="${user.id}">
                                <div class="relative">
                                    <div class=" divide-y divide-slate-400/20 rounded-lg bg-white text-[0.8125rem] leading-5 text-slate-900   ring-slate-700/10 hover:shadow">
                                        <!-- User information -->
                                        <div class="flex items-center p-3 pl-3">
                                            <!-- User profile image -->
                                            <div class="basis">
                                                <img class="w-10 h-10 rounded-full mx-auto object-cover"
                                                    src="${user.profile_image}" alt=""
                                                >
                                            </div>
                                            <!-- Date create post -->
                                            <div class="ml-4 flex-auto">
                                                <div class="font-medium  text-[1rem]">${user.name}</div>
                                            </div>
                                            <!-- Actions -->
                                            <div class="flex-auto flex gap-6 justify-end">
                                                <!--Button follow -->
                                                ${buttonFollow}
                                                <!-- Etiqueta following -->
                                                ${labelFollowing}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>`;

    return userCard;
};
