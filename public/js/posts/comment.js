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
            console.log(res.data);
            let data = res.data;
            if (data.state) {
                let comment = data.comment;
                //Limpio input de comentario
                let input = document.getElementById(
                    `content-comment-${postId}`
                );
                input.value = "";
                //Aqui se debe añadir el comentario
                let containerList = document.getElementById(
                    `container-comments-${postId}`
                );
                containerList.insertAdjacentHTML(
                    "afterbegin",
                    createComment(
                        comment.name,
                        comment.profile_image,
                        comment.pivot.created_at_formated,
                        comment.pivot.content
                    )
                );
                //Si el contenedor de comentarios esta cerrado, se muestra
                if (!Boolean(containerList.classList.contains("max-h-max"))) {
                    containerList.classList.add("max-h-max");
                }
                //Actualizo cantidad de comentarios
                document.getElementById(
                    `amount-comments-post-${postId}`
                ).innerText = res.data.amountOfComments;
            }
        })
        .catch((error) => console.log(error));
};

/**
 *
 * @param {*} name
 * @param {*} profileImage
 * @param {*} createdAt
 * @param {*} content
 * @returns
 */
const createComment = (name, profileImage, createdAt, content) => {
    // const createComment = (comment) => {
    return `<div class="px-4 py-2">
                <div  class="bg-gray-100 px-3 py-2 rounded-lg" >
                    <div class="flex">
                        <div class="hidden md:flex flex-none py-2 px-1 ">
                            <div class="flex h-full justify-start flex-col">
                                <img class="w-8 h-8 rounded-full mx-auto object-cover"
                                    src="${profileImage}" alt=""
                                >
                            </div>
                        </div>

                        <div>
                            <div class="flex flex-row">
                                <div class="flex md:hidden h-full justify-start flex-col">
                                    <img class="w-8 h-8 rounded-full mx-auto object-cover"
                                        src="${profileImage}" alt=""
                                    >
                                </div>
                                <div>
                                    <div class="font-medium inline-flex  px-2">${name} </div>
                                    <div  class="hidden md:inline-flex">-</div>
                                    <div class=" text-slate-700 px-2 inline-flex">
                                        ${createdAt}
                                    </div>
                                </div>
                            </div>
                            <p class="text-base flex-auto p-2">
                                ${content}
                            </p>
                        </div>

                    </div>
                </div>
            </div>`;
};
