import { api } from "./axios.js";

export const authApi = {
    me: () => api.get("/auth/me"),
    changePassword: (payload) => api.post("/auth/change-password", payload),

    login: ({ email, password, rememberMe }) =>
        api.post("/auth/sign-in", { email, password, rememberMe }),

    logout: () => api.post("/auth/logout"),
};