window.addEventListener("load", () => {
    button = document.getElementById("unfollow-button-post");
    button.addEventListener("click", (e) => {
        e.stopPropagation();
        let element = e.target;
        let user_id_unfollow = element.getAttribute("data-id");
        element.disabled = true;
        element.classList.add("bg-gray-700");
        let spinner = document.getElementById(`unfollow-spinner`);
        let icon = document.getElementById(`unfollow-icon`);
        spinner.classList.remove("hidden");
        icon.classList.add("hidden");
        axios
            .post("../connect/unfollow", {
                user_id_unfollow: user_id_unfollow,
            })
            .then((res) => {
                if (res.data.state) {
                    element.classList.add("hidden");
                    document
                        .getElementById(`follow-button-post`)
                        .classList.remove("hidden");

                    document
                        .getElementById(`connected`)
                        .classList.add("hidden");
                }
            })
            .catch((error) => {});
    });
});
