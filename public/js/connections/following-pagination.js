/*********************************************
 * Request to users following
 */
const searchMoreResults = () => {
    if (
        document.body.scrollHeight - window.innerHeight ===
        Math.ceil(window.scrollY)
    ) {
        page++;
        axios
            .get(`/post/${userId}/following/${page}`)
            .then((resp) => {
                if (resp.data.state) {
                    if (resp.data.users.length != 0) {
                        insertResults(resp.data);
                    }
                }
            })
            .catch((error) => {});
    }
};
