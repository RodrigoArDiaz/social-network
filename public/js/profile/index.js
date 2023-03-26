/**
 * Consts
 */
let imageProfileInfo = document.getElementById("image-profile-info");
let imageProfileUpload = document.getElementById("image-profile-upload");
let btnUpdateImageProfile = document.getElementById("btn-update-image-profile");
let imageFileInput = document.getElementById("input-file-profile");
let feedbackContainer = document.getElementById(
    "feedback-update-image-profile-container"
);
let feedbackContent = document.querySelector("#feedback-content");
let saveIcon = document.getElementById("save-icon");
let saveSpinner = document.getElementById("save-spinner");
/**
 *
 */
imageFileInput.addEventListener("change", () => {
    const file = imageFileInput.files[0];
    if (file) imageProfileUpload.src = URL.createObjectURL(file);
});

/**
 *
 * @param {*} image
 */
const changeProfileImage = (image) => {
    axios
        .post(`user/update_profile_image`, { image_url: image })
        .then((res) => {
            imageProfileInfo.src = image;
            imageProfileUpload.src = image;
            feedbackHandler(res.data.message, "success");
        })
        .catch((error) =>
            feedbackHandler(error.response.data.message, "error")
        );
};

/**
 *
 */
btnUpdateImageProfile.addEventListener("click", () => {
    saveIcon.classList.add("hidden");
    saveIcon.classList.remove("block");
    saveSpinner.classList.add("block");
    saveSpinner.classList.remove("hidden");
    btnUpdateImageProfile.disabled = true;

    var formData = new FormData();
    formData.append("image", imageFileInput.files[0]);

    axios
        .post(`user/update_profile_image`, formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        })
        .then((res) => {
            imageProfileInfo.src = res.data.url_image;
            imageProfileUpload.src = res.data.url_image;
            feedbackHandler(res.data.message, "success");
            saveIcon.classList.remove("hidden");
            saveIcon.classList.add("block");
            saveSpinner.classList.remove("block");
            saveSpinner.classList.add("hidden");
            btnUpdateImageProfile.disabled = false;
        })
        .catch((error) => {
            feedbackHandler(error.response.data.message, "error");
            saveIcon.classList.remove("hidden");
            saveIcon.classList.add("block");
            saveSpinner.classList.remove("block");
            saveSpinner.classList.add("hidden");
            btnUpdateImageProfile.disabled = false;
        });
});

/**
 *
 * @param {*} message
 * @param {*} type
 */
const feedbackHandler = (message, type) => {
    let colorPrimary = "text-green-500";
    let colorSecondary = "text-red-500";
    if (type == "error") {
        colorPrimary = "text-red-500";
        colorSecondary = "text-green-500";
    }

    let newElement = document.createElement("p");
    newElement.innerText = message;
    feedbackContent.innerHTML = "";
    feedbackContent.classList.remove(colorSecondary);
    feedbackContent.classList.add(colorPrimary);
    feedbackContent.appendChild(newElement);
    feedbackContainer.classList.add("opacity-100");

    setTimeout(() => {
        feedbackContainer.classList.remove("opacity-100");
    }, 5000);
};
