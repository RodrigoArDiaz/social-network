window.addEventListener("load", () => {
    //Add evento to inputsShowComment
    addEventToInputsShowComment();
    //Añade evento a botones Show more comments
    addEventToShowMoreComments();
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

    //Se muestra o se esconde  el contenedor de comentarios
    containerList.classList.toggle("max-h-max");

    if (Boolean(containerList.classList.contains("max-h-max"))) {
        //Se muestra el spinner
        spinnerContainerComments.classList.remove("hidden");
        spinnerContainerComments.classList.add("flex");

        axios
            .post(`/post/comment/list`, { postId: postId })
            .then((resp) => {
                //Se esconde el spinner
                spinnerContainerComments.classList.add("hidden");
                spinnerContainerComments.classList.remove("flex");

                if (resp.data.state) {
                    appendComments(resp.data, postId, true);
                    if (resp.data.amountOfComments > 0) {
                        //Se actualiza contador de comentarios
                        document.getElementById(
                            `amount-comments-post-${postId}`
                        ).innerText = resp.data.amountOfComments;
                        //Se muestra el boton de buscar mas comentarios
                        document
                            .getElementById(
                                `container-action-more-results-${postId}`
                            )
                            .classList.remove("hidden");
                    } else {
                        //Si el post no tiene comentarios
                        let container = document.getElementById(
                            `container-comments-${postId}`
                        );
                        container.insertAdjacentHTML(
                            "beforeend",
                            "<div class='text-center pb-4'> <p class='text-sm font-medium' >No comments</p></div>"
                        );
                    }
                }
            })
            .catch((error) => {});
    } else {
        //
        containerList.innerHTML = "";
        //Se esconde el boton de buscar mas comentarios
        let containerActionMoreComments = document.getElementById(
            `container-action-more-results-${postId}`
        );
        containerActionMoreComments.classList.add("hidden");
        //Se resetea la proxima pagina a cargar
        containerActionMoreComments.setAttribute("data-page", 2);
        //Se reseta nombre de action show more comments
        document.getElementById(`action-more-results-${postId}`).innerText =
            "Show more comments";
    }
};

/**
 *
 */
const addEventToShowMoreComments = () => {
    const actionShowMoreComments = document.querySelectorAll(
        ".show-more-comments"
    );
    actionShowMoreComments.forEach((action) => {
        action.addEventListener("click", handlerShowMoreComments);
    });
};

/**
 *
 */
const handlerShowMoreComments = (e) => {
    let element = e.currentTarget;
    let postId = element.getAttribute("data-id");
    let page = element.getAttribute("data-page"); //Pagina a cargar
    let spinnerContainerComments = document.getElementById(
        `spinner-container-comments-${postId}`
    );

    //Se muestra spinner
    spinnerContainerComments.classList.remove("hidden");
    spinnerContainerComments.classList.add("flex");
    //Peticion
    axios
        .post(`/post/comment/list-more`, { postId: postId, page: page })
        .then((resp) => {
            //Se oculta spinner
            spinnerContainerComments.classList.add("hidden");
            spinnerContainerComments.classList.remove("flex");
            if (resp.data.state) {
                //Se inserta comentario en la lista de comentario
                appendComments(resp.data, postId, false);
                //Se actualiza el numero de la proxima pagina a cargar
                element.setAttribute("data-page", resp.data.nextPage);
                //Si no hay mas comentarios para cargar
                if (resp.data.comments.length == 0) {
                    document.getElementById(
                        `action-more-results-${postId}`
                    ).innerText = "No more comments";
                }
            }
        })
        .catch((error) => {});
};

/**
 * Añade comentarios dentro de la lista de comentarios
 * @param {*} data: datos del comentario
 * @param {*} postId: id del post al cual pertenece el comentario
 * @param {*} resetContent: indica si se tiene  que eliminar o no los comentarios anterios que estaban en la lista de comentarios.
 */
const appendComments = (data, postId, resetContent) => {
    let container = document.getElementById(`container-comments-${postId}`);
    if (resetContent) container.innerHTML = "";
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
