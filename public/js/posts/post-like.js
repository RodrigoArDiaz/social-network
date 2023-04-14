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
        //Se elimina event listener
        button.removeEventListener("click", handlerClickButtonLike);
        //Se añade event listener
        button.addEventListener("click", handlerClickButtonLike);
    });
};

const handlerClickButtonLike = (e) => {
    let element = e.currentTarget;
    console.log(element.nextElementSibling);
    let postId = element.getAttribute("data");
    let containerAmountLikes = document.getElementById(
        `amount-likes-post-${postId}`
    );
    axios
        .get(`/post/${postId}/toggle-like`)
        .then((res) => {
            console.log(res.data);
            if (res.data.like) {
                element.classList.add("text-red-500");
                element.classList.add("focus:text-red-500");
                containerAmountLikes.innerText = res.data.number_like;
            } else {
                element.classList.remove("text-red-500");
                element.classList.remove("focus:text-red-500");
                containerAmountLikes.innerText = res.data.number_like;
            }
        })
        .catch((error) => {});
};
