//Variables
let contentPostInput;
let imagePostCreate;
let imagePostCreateContainer;
let imagePostCreateShow;
let deleteImagePost;
let postInput;

/*********************************** */
window.addEventListener("load", () => {
    postInput = getElement("content");
    imagePostCreate = getElement("image");
    imagePostCreateContainer = getElement("image-post-create-container");
    imagePostCreateShow = getElement("image-post-create-show");
    deleteImagePost = getElement("delete-image-post");

    /**************************************************/
    postInput.addEventListener("input", () => {
        //Rezise according content
        auto_grow(postInput);
        //Update character counter accordint content
        console.log(postInput.value);
        let length = postInput.value.length;
        let counter = getElement("character_counter");
        let submitCreate = getElement("submit-create-form");

        counter.innerText = length;
        if (length > 300 || length <= 0) {
            submitCreate.disabled = true;
            submitCreate.classList.add("bg-gray-500");
            submitCreate.classList.remove("bg-gray-800");
            counter.classList.add("border-red-600");
        } else {
            submitCreate.disabled = false;
            submitCreate.classList.remove("bg-gray-500");
            submitCreate.classList.add("bg-gray-800");
            counter.classList.remove("border-red-600");
        }
    });

    /**************************************************/
    imagePostCreate.addEventListener("change", () => {
        const file = imagePostCreate.files[0];
        if (file) imagePostCreateShow.src = URL.createObjectURL(file);
        imagePostCreateContainer.classList.add("flex");
        imagePostCreateContainer.classList.remove("hidden");
    });

    /************************************************/
    deleteImagePost.addEventListener("click", () => {
        imagePostCreateShow.src = "";
        imagePostCreate.value = "";
        imagePostCreateContainer.classList.add("hidden");
        imagePostCreateContainer.classList.remove("flex");
    });
});

//Resize the height of element according to its scrollHeight
const auto_grow = (element) => {
    element.style.height = element.scrollHeight + "px";
};

/***************************************
 * Helpers
 */
//Search a element by id y return it
const getElement = (id) => {
    return document.getElementById(id);
};
