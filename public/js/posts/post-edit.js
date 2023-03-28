let postInput;
let imagePostEdit;
let imagePostEditContainer;
let imagePostEditShow;
let deleteImagePostEdit;
let inputNoImage; //Indica si el post no tiene imagen

window.addEventListener("load", () => {
    postInput = getElement("content_edit");
    imagePostEdit = getElement("image_edit");
    imagePostEditContainer = getElement("image-post-edit-container");
    imagePostEditShow = getElement("image-post-edit-show");
    deleteImagePostEdit = getElement("delete-image-post-edit");
    inputNoImage = getElement("no-image");

    //Resize height input
    postInput.style.height = postInput.scrollHeight + "px";
    if (postInput.value.length <= 300 && postInput.value.length > 0) {
        getElement("character_counter").classList.remove("border-red-600");
        getElement("character_counter").innerText = postInput.value.length;
    } else {
        getElement("character_counter").classList.add("border-red-600");
        getElement("character_counter").innerText = postInput.value.length;
    }

    /************************************************/
    postInput.addEventListener("input", () => {
        //Rezise according content
        postInput.style.height = postInput.scrollHeight + "px";
        //Update character counter accordint content
        console.log(postInput.value);
        let length = postInput.value.length;
        let counter = getElement("character_counter");
        let submitEdit = getElement("submit-edit-post");

        counter.innerText = length;
        if (length > 300 || length <= 0) {
            submitEdit.disabled = true;
            submitEdit.classList.add("bg-gray-500");
            submitEdit.classList.remove("bg-gray-800");
            counter.classList.add("border-red-600");
        } else {
            submitEdit.disabled = false;
            submitEdit.classList.remove("bg-gray-500");
            submitEdit.classList.add("bg-gray-800");
            counter.classList.remove("border-red-600");
        }
    });

    /**************************************************/
    imagePostEdit.addEventListener("change", () => {
        const file = imagePostEdit.files[0];
        console.log(file);
        if (file) imagePostEditShow.src = URL.createObjectURL(file);
        imagePostEditContainer.classList.add("flex");
        imagePostEditContainer.classList.remove("hidden");

        //Indica que el post tiene imagen
        inputNoImage.checked = false;
    });

    /************************************************/
    deleteImagePostEdit.addEventListener("click", () => {
        imagePostEditShow.src = "";
        imagePostEdit.value = "";
        imagePostEditContainer.classList.add("hidden");
        imagePostEditContainer.classList.remove("flex");
        //Indica que el post no tiene imagen
        inputNoImage.checked = true;
    });
});
