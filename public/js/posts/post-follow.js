window.addEventListener("load", () => {
    button = document.getElementById("follow-button-post");
    button.addEventListener("click", (e) => {
        e.stopPropagation();
        let element = e.target;
        let user_id_follow = element.getAttribute("data-id");
        element.disabled = true;
        element.classList.add("bg-gray-700");
        let spinner = document.getElementById(`follow-spinner`);
        let icon = document.getElementById(`follow-icon`);
        spinner.classList.remove("hidden");
        icon.classList.add("hidden");
        axios
            .post("../connect/follow", {
                user_id_follow: user_id_follow,
            })
            .then((res) => {
                if (res.data.state) {
                    element.classList.add("hidden");
                    document
                        .getElementById(`unfollow-button-post`)
                        .classList.remove("hidden");
                    if (res.data.isConnected) {
                        document
                            .getElementById(`connected`)
                            .classList.remove("hidden");
                    }
                }
            })
            .catch((error) => {});
    });
});
