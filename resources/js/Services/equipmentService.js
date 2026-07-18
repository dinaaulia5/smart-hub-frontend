import api from "./api";

export default {
    getAll(params = {}) {
        return api.get("equipments", { params });
    },

    getById(id) {
        return api.get(`equipments/${id}`);
    },

    create(data) {
        return api.post("equipments", data);
    },

    update(id, data) {
        return api.put(`equipments/${id}`, data);
    },

    delete(id) {
        return api.delete(`equipments/${id}`);
    },
};
