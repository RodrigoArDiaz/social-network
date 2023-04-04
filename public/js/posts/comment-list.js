window.addEventListener("load", () => {
    //Add evento to inputsShowComment
    addEventToInputsShowComment();
});

/**
 *
 */
const addEventToInputsShowComment = () => {
    const inputsShowComment = document.querySelectorAll(".show-comments");
    inputsShowComment.forEach((inputShowComments) => {
        inputShowComments.addEventListener("click", handlerShowComment);
    });
};

/**
 *
 */
const handlerShowComment = (e) => {
    let input = e.currentTarget;
    let postId = input.getAttribute("data-id");
    let containerList = document.getElementById(`container-comments-${postId}`);
    let spinnerContainerComments = document.getElementById(
        `spinner-container-comments-${postId}`
    );

    containerList.classList.toggle("max-h-max");

    if (Boolean(containerList.classList.contains("max-h-max"))) {
        spinnerContainerComments.classList.remove("hidden");
        spinnerContainerComments.classList.add("flex");

        axios
            .post(`/post/comment/list`, { postId: postId })
            .then((resp) => {
                console.log(resp.data);
                spinnerContainerComments.classList.add("hidden");
                spinnerContainerComments.classList.remove("flex");
                if (resp.data.state) {
                    appendComments(resp.data, postId);
                    if (resp.data.amountOfComments > 0) {
                        document.getElementById(
                            `amount-comments-post-${postId}`
                        ).innerText = resp.data.amountOfComments;
                    }
                }
            })
            .catch((error) => {});
    }
};

/**
 *
 */
const appendComments = (data, postId) => {
    let container = document.getElementById(`container-comments-${postId}`);
    container.innerHTML = "";
    // console.log(data.comments.data);
    data.comments.map((comment) => {
        container.insertAdjacentHTML(
            "beforeend",
            createComment(
                comment.name,
                comment.profile_image,
                comment.pivot.created_at_formated,
                comment.pivot.content
            )
        );
    });
};
