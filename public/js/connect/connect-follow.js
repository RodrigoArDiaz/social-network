window.addEventListener("load", () => {
    $followButtons = document.querySelectorAll("#follow-button");
    $followButtons.forEach((button) => {
        button.addEventListener("click", (e) => {
            let element = e.target;
            let user_id_follow = element.getAttribute("data-id");
            element.disabled = true;
            element.classList.add("bg-gray-700");
            let spinner = document.getElementById(
                `follow-spinner-${user_id_follow}`
            );
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
        });
    });
});
