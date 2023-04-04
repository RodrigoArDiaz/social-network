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

    containerList.classList.add("max-h-max");
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
};

/**
 *
 */
const createComment = (comment) => {
    return `<div class="px-4 py-2">
                <div  class="bg-gray-100 px-3 py-2 rounded-lg" >
                    <div class="flex">
                        <div class="hidden md:flex flex-none py-2 px-1 ">
                            <div class="flex h-full justify-start flex-col">
                                <img class="w-8 h-8 rounded-full mx-auto object-cover"
                                    src="${comment.profile_image}" alt=""
                                >
                            </div>
                        </div>

                        <div>
                            <div class="flex flex-row">
                                <div class="flex md:hidden h-full justify-start flex-col">
                                    <img class="w-8 h-8 rounded-full mx-auto object-cover"
                                        src="${comment.profile_image}" alt=""
                                    >
                                </div>
                                <div>
                                    <div class="font-medium inline-flex  px-2">${comment.name} </div>
                                    <div  class="hidden md:inline-flex">-</div>
                                    <div class=" text-slate-700 px-2 inline-flex">
                                        ${comment.pivot.created_at_formated}
                                    </div>
                                </div>
                            </div>
                            <p class="text-base flex-auto p-2">
                                ${comment.pivot.content}
                            </p>
                        </div>

                    </div>
                </div>
            </div>`;

    //     return `<div class="px-4 py-2">
    //     <div  class="bg-gray-100 px-3 py-2 rounded-lg" >
    //         <div class="flex">
    //             <div class="hidden md:flex flex-none py-2 px-1 ">
    //                 <div class="flex h-full justify-start flex-col">
    //                     <img class="w-8 h-8 rounded-full mx-auto object-cover"
    //                         src="${comment.profile_image}" alt=""
    //                     >
    //                 </div>
    //             </div>

    //             <div>
    //                 <div class="flex flex-row">
    //                     <div class="flex md:hidden h-full justify-start flex-col">
    //                         <img class="w-8 h-8 rounded-full mx-auto object-cover"
    //                             src="${comment.profile_image}" alt=""
    //                         >
    //                     </div>
    //                     <div>
    //                         <div class="font-medium inline-flex  px-2">${comment.name} </div>
    //                         <div  class="hidden md:inline-flex">-</div>
    //                         <div class=" text-slate-700 px-2 inline-flex">
    //                             ${comment.created_at}
    //                         </div>
    //                     </div>
    //                 </div>
    //                 <p class="text-base flex-auto p-2">
    //                     ${comment.content}
    //                 </p>
    //             </div>

    //         </div>
    //     </div>
    // </div>`;
};

/**
 *
 */
const appendComments = (data, postId) => {
    let container = document.getElementById(`container-comments-${postId}`);
    container.innerHTML = "";
    // console.log(data.comments.data);
    data.comments.map((comment) => {
        container.insertAdjacentHTML("beforeend", createComment(comment));
    });
};
