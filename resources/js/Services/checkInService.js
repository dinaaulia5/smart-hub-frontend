import api from "./api";

export default {
    create(data) {
        return api.post("/check-ins", data);
    },
};
