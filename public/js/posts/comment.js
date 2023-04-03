window.addEventListener("load", () => {
    //Add event to comment forms
    addEventToCommentForms();
});

/**
 *
 */
const addEventToCommentForms = () => {
    let commentForms = document.querySelectorAll(".forms-comment");
    commentForms.forEach((form) => {
        form.addEventListener("submit", handlerSubmitCommentForm);
    });
};

/**
 *
 */
const handlerSubmitCommentForm = (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    let postId = formData.get("post_id");
    let spinner = document.getElementById(`comment-button-${postId}`);
    spinner.classList.remove("hidden");
    axios
        .post("/post/comment", formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        })
        .then((res) => {
            spinner.classList.add("hidden");
            let data = res.data;
            if (data.state) {
                let input = document.getElementById(
                    `content-comment-${postId}`
                );
                input.value = "";
                //Aqui se debe añadir el comentario
            }
        })
        .catch((error) => console.log(error));
};
