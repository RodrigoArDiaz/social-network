window.addEventListener("load", () => {
    //Follow
    addEventToFollowButtons();

    //View post
    addEventToUsersCard();
});

/*********************************
 * Add evento to all follow button
 */
const addEventToFollowButtons = () => {
    //Seleccionas todos los button follow
    $followButtons = document.querySelectorAll("#follow-button");

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
    let element = e.target;
    let user_id_follow = element.getAttribute("data-id");
    element.disabled = true;
    element.classList.add("bg-gray-700");
    let spinner = document.getElementById(`follow-spinner-${user_id_follow}`);
    let icon = document.getElementById(`follow-icon-${user_id_follow}`);
    spinner.classList.remove("hidden");
    icon.classList.add("hidden");
    axios
        .post("follow", {
            user_id_follow: e.target.getAttribute("data-id"),
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
    console.log(e.currentTarget);
    let userId = e.currentTarget.getAttribute("data-id");
    window.location = `../post/${userId}`;
};
