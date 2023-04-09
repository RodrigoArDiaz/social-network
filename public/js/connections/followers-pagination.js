/*********************************************
 * Request to users follower
 */
const searchMoreResults = () => {
    if (
        document.body.scrollHeight - window.innerHeight ===
        Math.ceil(window.scrollY)
    ) {
        page++;
        axios
            .get(`/post/${userId}/followers/${page}`)
            .then((resp) => {
                console.log(resp.data);
                if (resp.data.state) {
                    if (resp.data.users.length != 0) {
                        insertResults(resp.data);
                    }
                }
            })
            .catch((error) => {});
    }
};
