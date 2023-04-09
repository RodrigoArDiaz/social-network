//Id del usuario autenticado
let userId = document
    .getElementById("user-list-container")
    .getAttribute("data-id");
//Current page
let page = 1;

window.addEventListener("load", () => {
    //Unfollow
    addEventToUnfollowButtons();

    //Follow
    addEventToFollowButtons();

    //View post
    addEventToUsersCard();
});

/*****************************
 * Evento para scroll infinito
 * */
window.addEventListener(
    "scroll",
    () => {
        searchMoreResults();
    },
    false
);

/*********************************
 * Add evento to all follow button
 */
const addEventToUnfollowButtons = () => {
    //Seleccionas todos los button follow
    $unFollowButtons = document.querySelectorAll("#unfollow-button-post");

    //A cada uno le añade un evento
    $unFollowButtons.forEach((button) => {
        //Remueve el evento si ya lo tiene
        button.removeEventListener("click", handleClickUnFollowButton);
        //Añade el evento
        button.addEventListener("click", handleClickUnFollowButton);
    });
};

const handleClickUnFollowButton = (e) => {
    e.stopPropagation();
    let element = e.target;
    let user_id_unfollow = element.getAttribute("data-id");
    element.disabled = true;
    element.classList.add("bg-gray-700");
    let spinner = document.getElementById(
        `unfollow-spinner-${user_id_unfollow}`
    );
    let icon = document.getElementById(`unfollow-icon-${user_id_unfollow}`);
    spinner.classList.remove("hidden");
    icon.classList.add("hidden");
    axios
        .post("../../connect/unfollow", {
            user_id_unfollow: user_id_unfollow,
        })
        .then((res) => {
            if (res.data.state) {
                element.classList.add("hidden");
            }
        })
        .catch((error) => {});
};

/*********************************
 * Add evento to all follow button
 */
const addEventToFollowButtons = () => {
    //Seleccionas todos los button follow
    $followButtons = document.querySelectorAll("#follow-button-post");

    //A cada uno le añade un evento
    $followButtons.forEach((button) => {
        //Remueve el evento si ya lo tiene
        button.removeEventListener("click", handleClickFollowButton);
        //Añade el evento
        button.addEventListener("click", handleClickFollowButton);
    });
};

const handleClickFollowButton = (e) => {
    e.stopPropagation();
    let element = e.currentTarget;
    let user_id_follow = element.getAttribute("data-id");
    element.disabled = true;
    element.classList.add("bg-gray-700");
    let spinner = document.getElementById(`follow-spinner-${user_id_follow}`);
    let icon = document.getElementById(`follow-icon-${user_id_follow}`);
    spinner.classList.remove("hidden");
    icon.classList.add("hidden");
    axios
        .post("../../connect/follow", {
            user_id_follow: user_id_follow,
        })
        .then((res) => {
            if (res.data.state) {
                element.classList.add("hidden");
                document
                    .getElementById(`following-chip-${user_id_follow}`)
                    .classList.remove("hidden");
            }
        })
        .catch((error) => {});
};

/*********************************
 * Add event click to all user's card
 */
const addEventToUsersCard = () => {
    $usersCards = document.querySelectorAll("#user-card");
    $usersCards.forEach((userCard) => {
        //Se borra el evento si ya lo tiene
        userCard.removeEventListener("click", handleClickUserCard);
        //Se añade el evento
        userCard.addEventListener("click", handleClickUserCard);
    });
};

const handleClickUserCard = (e) => {
    /**Evento de click lo provoca el hijo y se propaga al padre, si se usa e.target se obtiene el elemento hijo que provoco el evento
     * se debe usar e.currentTarget para obtener el elemento que tiene el listener.
     */
    let userId = e.currentTarget.getAttribute("data-id");
    window.location = `../${userId}`;
};

/*******************************************
 * Event to load results to infinite scroll
 */
const insertResults = (data) => {
    let container = document.getElementById("user-list-container");
    let userId = data.user.id;
    if (data.users.length != 0) {
        data.users.forEach((user) => {
            container.insertAdjacentHTML(
                "beforeend",
                createElement(user, userId, data.type, data.isUserPost)
            );
        });

        if (Boolean(data.isUserPost)) {
            addEventToButtonsActionAfterLoad(data.type);
        }
    }
};

/*******************************************************
 * Create users's cards
 */
const createElement = (user, userId, type, isUserPost) => {
    let action = "";
    if (Boolean(isUserPost)) {
        if (type == "connections" || type == "following") {
            action = `<div class="flex-auto flex gap-6 justify-end">
                            <button data-id='${user.id}' id="unfollow-button-post" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                <svg id="unfollow-icon-${user.id}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M22 10.5h-6m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                                </svg>
                                <svg id="unfollow-spinner-${user.id}" class="animate-spin -ml-1 mr-2 h-4 w-4 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Unfollow
                            </button>
                    </div>`;
        }
        if (type == "followers") {
            if (Boolean(user.following)) {
                action = `<div id='following-chip' class="flex justify-center items-center m-1  py-2 px-3 rounded-full bg-gray-100 text-black-300">
                        <div class="text-sm font-medium leading-none max-w-full flex-initial">Following</div>
                      </div>`;
            } else {
                action = `<div id='following-chip-${user.id}' class="hidden  justify-center items-center m-1  py-2 px-3 rounded-full bg-gray-100 text-black-300">
                            <div class="text-sm font-medium leading-none max-w-full flex-initial">Following</div>
                      </div>
                       <button data-id='${user.id}' id="follow-button-post" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            <svg id="follow-icon-${user.id}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                            </svg>
                            <svg id="follow-spinner-${user.id}" class="animate-spin -ml-1 mr-2 h-4 w-4 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Follow
                      <button>`;
            }
        }
    }
    return `<div class="py-0 ">
                <div class=" hover:cursor-pointer" id="user-card" data-id="${userId}">
                    <div class="relative">
                        <div class=" divide-y divide-slate-400/20 rounded-lg bg-white text-[0.8125rem] leading-5 text-slate-900   ring-slate-700/10 hover:shadow">
                            <div class="flex items-center p-3 pl-3">
                                <div class="basis">
                                    <img class="w-10 h-10 rounded-full mx-auto object-cover" src="${user.profile_image}" alt="profile image">
                                </div>
                                <div class="ml-4 flex-auto">
                                    <div class="font-medium  text-[1rem]">${user.name}</div>
                                </div>
                                ${action}
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;
};

/************************************
 * Add event to button of user's cards
 */
const addEventToButtonsActionAfterLoad = (type) => {
    //View post
    addEventToUsersCard();
    if (type == "connections" || type == "following") {
        //Unfollow
        addEventToUnfollowButtons();
    }
    if (type == "followers") {
        //Follow
        addEventToFollowButtons();
    }
};
