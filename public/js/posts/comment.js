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
                        comment.pivot.content,
                        comment.pivot.id,
                        comment.commentBelongsToCurrentUser
                    )
                );
                //Se añade evento a botones de borrar comentario
                addEventToButtonDeleteComment();
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
const createComment = (
    name,
    profileImage,
    createdAt,
    content,
    commentId,
    commentBelongsToCurrentUser
) => {
    console.log(commentId);

    let buttonDeleteComment = "";
    if (Boolean(commentBelongsToCurrentUser)) {
        buttonDeleteComment = `<button class=" button-delete-comment border-opacity-0 shadow-none flex lowercase text-sm p-1 border rounded-full bg-transparent z-50
                                    right-3 top-2  active:text-gray-500
                                    items-center  dark:bg-gray-800  border-gray-300 dark:border-gray-500  font-semibold
                                    text-gray-700 dark:text-gray-300  tracking-widest  hover:bg-gray-50  hover:text-red-500
                                    dark:hover:bg-gray-700 active:outline-none active:ring-2 active:ring-indigo-500 active:ring-offset-2
                                    dark:active:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150"
                                    data-id="${commentId}"
                                    >
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"></path>
                                    </svg>
                               </button>`;
    }

    return `<div class="px-4 py-2" id="comment-container-${commentId}">
                <div  class="bg-gray-100 px-3 py-2 rounded-lg" >
                    <div class="flex">
                        <div class="hidden md:flex flex-none py-2 px-1 ">
                            <div class="flex h-full justify-start flex-col">
                                <img class="w-8 h-8 rounded-full mx-auto object-cover"
                                    src="${profileImage}" alt=""
                                >
                            </div>
                        </div>

                        <div class="grow">
                            <div class="flex flex-row justify-between">

                                <div class="flex">
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

                                <div>
                                    ${buttonDeleteComment}
                                </div>
                            </div>

                            <div class="">
                                <p class="text-base flex-auto p-2">
                                    ${content}
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>`;
};
