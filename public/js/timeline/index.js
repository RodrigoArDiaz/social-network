let page = 1;

/*****************************
 * Evento para scroll infinito
 * */
window.addEventListener(
    "scroll",
    () => {
        searchMoreResults();
    },
    false
);

/**
 *
 */
const searchMoreResults = () => {
    if (
        document.body.scrollHeight - window.innerHeight ===
        Math.ceil(window.scrollY)
    ) {
        page++;
        axios
            .get(`/timeline/${page}`)
            .then((resp) => {
                console.log(resp.data);
                if (resp.data.state) {
                    if (resp.data.posts.length != 0) {
                        insertResults(resp.data);
                    }
                }
            })
            .catch((error) => {});
    }
};

/*******************************************
 *
 */
const insertResults = (data) => {
    let container = document.getElementById("container-posts-timeline");
    // let userId = data.user.id;
    if (data.posts.length != 0) {
        data.posts.forEach((post) => {
            container.insertAdjacentHTML("beforeend", createElement(post));
        });
        //Se añade evento a boton mostrar likes usuario
        addEventToShowLikesListButton();
        //Se añade evento a boton de dar like
        addEventToButtonLikePost();
        //Se añade evento a boton de mostrar comentarios del post
        addEventToInputsShowComment();
        //Se añade evento a boton de mostrar mas comentarios del post
        addEventToShowMoreComments();
        //Se añade evento a boton de comentar
        addEventToCommentForms();
    }
};

/**
 *
 */
const createElement = (post) => {
    let image = "";
    let numberOfLikes = "";
    let numberOfComments = "";
    let userLikeToPost = "";
    let postEdited = "";

    if (post.image != null) {
        image = `<div class="flex items-center p-4">
                <div id="image-post-create-container" class="py-0 w-full justify-center bg-gray-100 rounded-lg relative">
                    <div class="basis">
                        <img
                            id="image-post-create-show"
                            class="w-full h-auto mx-auto object-cover rounded-lg"
                            src="${post.image}"
                            alt="Post's image" >
                    </div>
                </div>
            </div>`;
    }

    if (post.likes_count != 0) {
        numberOfLikes = post.likes_count;
    }

    if (post.comments_count > 0) {
        numberOfComments = post.comments_count;
    }

    if (Boolean(post.userLikeToPost)) {
        userLikeToPost = "text-red-500";
    }

    if (Boolean(post.edited)) {
        postEdited = ` -  <i class="text-green-500">Edit</i> : ${post.updated_at}`;
    }

    return `<div class="py-0 ">
                <div class="">
                    <div class="relative">
                        <div class=" divide-y divide-slate-400/20 rounded-lg bg-white text-[0.8125rem] leading-5 text-slate-900   ring-slate-700/10">

                            <div class="flex items-center p-4 pl-4">

                                <div class="basis min-w-[40px]">
                                    <img class="w-8 h-8 rounded-full mx-auto object-cover"
                                        src="${post.user.profile_image}" alt=""
                                    >
                                </div>

                                <div class="ml-4 flex-auto">
                                    <div class="font-semibold ">
                                        <a href="${post.user_posts_redirect}">
                                            ${post.user.name}
                                        </a>
                                    </div>
                                    <div class="mt-1 text-slate-700">

                                        <i>Post</i> : ${post.created_at}
                                        ${postEdited}
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center p-4 border-t-0">
                                <div class="basis">
                                    <p class="text-base">
                                        ${post.content}
                                    </p>
                                </div>
                            </div>

                            ${image}

                            <div class="flex items-center gap-4 p-4 py-2 pl-4">
                                <div class="hidden md:flex basis">
                                    <img class="w-8 h-8 rounded-full mx-auto object-cover" src="${post.auth_user_profile_image}" alt="user's  profile image">
                                </div>
                                <div class="basis flex-auto">
                                    <form method="POST"
                                        id="form-comment-post-${post.id}" class="forms-comment">
                                        <textarea id="content-comment-${post.id}" name="content_comment" rows="1" class="block p-2.5 w-full text-base resize-none overflow-hidden text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-400 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Comment"></textarea>
                                        <input type ='text' name="post_id" id="post_id" value="${post.id}" hidden/>
                                    </form>
                                </div>
                                <div class="basis flex">
                                    <div >
                                        <button
                                            class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                                            id="submit-comment-form-${post.id}" type="submit"  form="form-comment-post-${post.id}" >
                                            <svg id="comment-button-spinner-${post.id}" class="animate-spin h-5 w-5 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <svg id="comment-button-${post.id}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5s h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="relative  overflow-hidden   rounded-b-lg bg-white text-[0.8125rem] leading-5 text-slate-900   ring-slate-700/10">
                                <div class="flex justify-evenly gap-4 border-none p-2">
                                    <div class="flex items-center">

                                        <button class="${userLikeToPost} border-opacity-0 shadow-none flex
                                                        p-2 border rounded-full bg-transparent  right-3 top-2  active:text-gray-500
                                                        items-center  bg-white dark:bg-gray-800  border-gray-300 dark:border-gray-500  font-semibold
                                                        text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest  hover:bg-gray-50
                                                        dark:hover:bg-gray-700 active:outline-none active:ring-2 active:ring-indigo-500 active:ring-offset-2
                                                        dark:active:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150 z-10"
                                            id="button-like-post" data="${post.id}">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                            </svg>
                                        </button>

                                        <button class="show-likes-list border-opacity-0 shadow-none flex bg-transparent
                                            p-2 border rounded-full right-3 top-2  active:text-gray-500
                                            items-center dark:bg-gray-800  border-gray-300 dark:border-gray-500  font-semibold
                                            text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest  hover:bg-gray-50
                                            dark:hover:bg-gray-700 active:outline-none active:ring-2 active:ring-indigo-500 active:ring-offset-2
                                            dark:active:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150 z-10"
                                            x-data=""
                                            x-on:click.prevent="$dispatch('open-modal', 'list-likes')"
                                            data-id="${post.id}">
                                            <p class="show-likes-user text-base text-gray-700 font-medium w-6 h-6" id="amount-likes-post-${post.id}">
                                                ${numberOfLikes}
                                            </p>
                                        </button>
                                    </div>

                                    <div class="flex items-center">
                                        <button class="show-comments border-opacity-0 shadow-none flex lowercase text-sm
                                                        p-2 border rounded-full bg-transparent  right-3 top-2  active:text-gray-500
                                                        items-center  bg-white dark:bg-gray-800  border-gray-300 dark:border-gray-500  font-semibold
                                                         text-gray-700 dark:text-gray-300  tracking-widest  hover:bg-gray-50
                                                        dark:hover:bg-gray-700 active:outline-none active:ring-2 active:ring-indigo-500 active:ring-offset-2
                                                        dark:active:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150 z-10"
                                            data-id="${post.id}">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
                                            </svg>

                                        </button>
                                        <p class="text-base text-gray-700 font-medium px-4" id="amount-comments-post-${post.id}">
                                            ${numberOfComments}
                                        </p>
                                    </div>
                                </div>

                                <div id="container-comments-${post.id}"  class="bg-white overflow-hidden transition-all duration-500 max-h-0 border-none border-transparent  peer-checked:border peer-checked:border-gray-900">
                                    <!-- Aqui van los comentarios -->
                                </div>

                                <div id="spinner-container-comments-${post.id}" class="hidden justify-center pb-4" >
                                    <svg class="animate-spin  h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>

                                <div class="show-more-comments pb-4 hidden" id="container-action-more-results-${post.id}" data-id="${post.id}" data-page='2'>
                                    <div class="text-center">
                                        <a href="javascript:void(0)" id="action-more-results-${post.id}" class="flex justify-center font-medium text-sm align-middle text-indigo-500">
                                            Show more comments
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;
};
