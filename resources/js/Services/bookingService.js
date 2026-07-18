import api from "./api";

export default {
    getAll(params = {}) {
        return api.get("/bookings", { params });
    },

    getById(id) {
        return api.get(`/bookings/${id}`);
    },

    create(data) {
        return api.post("/bookings", data);
    },

    update(id, data) {
        return api.put(`/bookings/${id}`, data);
    },

    delete(id) {
        return api.delete(`/bookings/${id}`);
    },

    cancel(id) {
        return api.patch(`/bookings/${id}/cancel`);
    },
};
