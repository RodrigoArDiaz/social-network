window.addEventListener("load", () => {
    let containerCountNotification = document.getElementById(
        "container-count-notification"
    );

    axios
        .post("/user/auth")
        .then((resp) => {
            if (Boolean(resp.data.state)) {
                let userId = resp.data.userId;
                Echo.join(`notification.${userId}`).listen(
                    "NotificationSent",
                    (e) => {
                        containerCountNotification.classList.remove("hidden");
                        if (parseInt(e.countUnreadNotification) < 100)
                            containerCountNotification.innerText =
                                e.countUnreadNotification;
                        else containerCountNotification.innerText = "+99";
                    }
                );
            }
        })
        .catch((error) => console.log(error));
});
