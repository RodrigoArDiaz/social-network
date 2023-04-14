window.addEventListener("load", () => {
    //
    addEventToShowLikesListButton();
});

/**
 *
 */
const addEventToShowLikesListButton = () => {
    let buttonsShowLikesListButton =
        document.querySelectorAll(".show-likes-list");
    buttonsShowLikesListButton.forEach((button) => {
        //Se elimina event listener
        button.removeEventListener("click", handlerShowLikesListButton);
        //Se añade event listener
        button.addEventListener("click", handlerShowLikesListButton);
    });
};

/**
 *
 */
const handlerShowLikesListButton = (e) => {
    let element = e.currentTarget;
    let postId = element.getAttribute("data-id");
    //Se limpia el contenedor de la ventana modal
    let container = document.getElementById(`container-likes-list-users`);
    container.innerHTML = "";
    //Se muestra spinner
    let spinnerModal = document.getElementById("spinner-likes-list-users");
    spinnerModal.classList.remove("hidden");
    axios
        .get(`/post/${postId}/list-likes`)
        .then((res) => {
            console.log(res.data);
            //Se esconde spinner
            spinnerModal.classList.add("hidden");
            if (res.data.state) {
                if (res.data.users.length != 0) {
                    appendUsersToLikesList(res.data.users);
                } else {
                    container.insertAdjacentHTML(
                        "beforeend",
                        "<div class='text-center p-4'> <p class='text-base font-medium' >No likes</p></div>"
                    );
                }
            }
        })
        .catch((error) => console.log(error));
};

/**
 *
 */
const appendUsersToLikesList = (users) => {
    let container = document.getElementById(`container-likes-list-users`);
    container.innerHTML = "";
    users.map((user) => {
        container.insertAdjacentHTML("beforeend", createUserCardLike(user));
    });
};
/**
 *
 */
const createUserCardLike = (user) => {
    return ` <div class="flex flex-row justify-between">
                <div class="flex">
                    <div class="flex min-w-[35px] h-full justify-start flex-col">
                        <img class="w-6 h-6 md:w-8 md:h-8 rounded-full mx-auto object-cover"
                            src="${user.profile_image}" alt=""
                        >
                    </div>
                    <div class="flex items-center">
                        <div class="font-medium inline-flex  px-2">${user.name}</div>
                    </div>
                </div>
                <div>
                    <a href="${user.route_posts}" class="flex justify-center font-medium text-sm md:text-base align-middle text-indigo-500">
                        View posts
                    </a>
                </div>
            </div>`;
};
