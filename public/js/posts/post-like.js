window.addEventListener("load", () => {
    //Like
    addEventToButtonLikePost();
});

/**
 *
 */
const addEventToButtonLikePost = () => {
    buttonsLikePost = document.querySelectorAll("#button-like-post");
    buttonsLikePost.forEach((button) => {
        button.addEventListener("click", handlerClickButtonLike);
    });
};

const handlerClickButtonLike = (e) => {
    let element = e.currentTarget;
    console.log(element.nextElementSibling);
    let postId = element.getAttribute("data");
    axios
        .get(`${postId}/toggle-like`)
        .then((res) => {
            console.log(res.data);
            if (res.data.like) {
                element.classList.add("text-red-500");
                element.classList.add("focus:text-red-500");
                element.nextElementSibling.innerText = res.data.number_like;
            } else {
                element.classList.remove("text-red-500");
                element.classList.remove("focus:text-red-500");
                element.nextElementSibling.innerText = res.data.number_like;
            }
        })
        .catch((error) => {});
};
