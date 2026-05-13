import axios from "axios";
import { getAuthHelpers } from "./authBridge.js";
import qs from "qs";

export const api = axios.create({
    baseURL: import.meta.env.VITE_API_BASE_URL,
    withCredentials: true,
    timeout: 15000,
    paramsSerializer: {
        serialize: (params) =>
            qs.stringify(params, {
                encode: true,
                encodeValuesOnly: false,
                arrayFormat: "repeat",
            }),
    },
});

function getAuth() {
    return getAuthHelpers();
}

const REFRESH_URL = "/auth/refresh";

let isRefreshing = false;
let refreshSubscribers = [];

function onRefreshed() {
    refreshSubscribers.forEach((cb) => cb());
    refreshSubscribers = [];
}

function subscribeTokenRefresh(cb) {
    refreshSubscribers.push(cb);
}

api.interceptors.request.use(
    (config) => config,
    (error) => Promise.reject(error)
);

api.interceptors.response.use(
    (response) => response,
    async (error) => {
        const { response, config } = error;

        if (!response || !config) {
            return Promise.reject(error);
        }

        const status = response.status;
        const url = config.url || "";

        if (status !== 401) {
            return Promise.reject(error);
        }

        const isLogin  = url.includes("/auth/sign-in");
        const isLogout = url.includes("/auth/logout");
        const isRefresh = url.includes(REFRESH_URL);

        const skipAuth = config.__skipAuth401 === true;

        if (isLogin || skipAuth) {
            return Promise.reject(error);
        }

        if (isLogout || isRefresh) {
            const { logout } = getAuth();
            logout?.();
            return Promise.reject(error);
        }

        if (config._retry) {
            const { logout } = getAuth();
            logout?.();
            return Promise.reject(error);
        }
        config._retry = true;

        try {
            if (!isRefreshing) {
                isRefreshing = true;

                await api.post(
                    REFRESH_URL,
                    null,
                    {
                        __skipAuth401: true,
                    }
                );

                isRefreshing = false;
                onRefreshed();
            }

            return new Promise((resolve, reject) => {
                subscribeTokenRefresh(() => {
                    api(config).then(resolve).catch(reject);
                });
            });
        } catch (refreshError) {
            isRefreshing = false;
            refreshSubscribers = [];

            const { logout } = getAuth();
            logout?.();

            return Promise.reject(refreshError);
        }
    }
);
