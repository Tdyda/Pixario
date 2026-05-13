import React, { useCallback, useEffect, useMemo, useState } from "react";
import { AuthContext } from "./AuthContext.js";
import { api } from "../api/axios.js";

const STORAGE_KEY = "app_auth";

function readStorageOnce() {
    try {
        const raw = localStorage.getItem(STORAGE_KEY);
        return raw ? JSON.parse(raw) : null;
    } catch {
        return null;
    }
}
function writeStorage(data) {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
}
function clearStorage() {
    localStorage.removeItem(STORAGE_KEY);
}

export default function AuthProvider({ children }) {
    const boot = readStorageOnce();
    const [user, setUser] = useState(boot?.user ?? null);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const init = async () => {
            try {
                const res = await api.get("/auth/me");
                const currentUser = res.data.user ?? res.data;
                setUser(currentUser);
                writeStorage({ user: currentUser });
            } catch (e) {
                setUser(null);
                clearStorage();
            } finally {
                setLoading(false);
            }
        };

        init();
    }, []);

    useEffect(() => {
        if (user) {
            writeStorage({ user });
        } else {
            clearStorage();
        }
    }, [user]);

    const login = useCallback(async ({ email, password, rememberMe }) => {
        setLoading(true);
        try {
            await api.post("/auth/sign-in", {
                email,
                password,
                rememberMe,
            });

            const meRes = await api.get("/auth/me");
            const loggedUser = meRes.data.user ?? meRes.data;

            setUser(loggedUser);
            writeStorage({ user: loggedUser });

            return loggedUser;
        } finally {
            setLoading(false);
        }
    }, []);

    const register = useCallback(async ({ email, plainPassword, retypedPassword }) => {
        setLoading(true);
        try {
            return await api.post("/auth/sign-up", {
                email,
                plainPassword,
                retypedPassword,
            });
        } finally {
            setLoading(false);
        }
    }, []);

    const logout = useCallback(async () => {
        setLoading(true);
        try {
            await api.post(
                "/auth/logout",
                null,
                { __skipAuth401: true }
            );
        } catch (e) {
            console.error(e);
        } finally {
            setUser(null);
            clearStorage();
            setLoading(false);
        }
    }, []);

    const value = useMemo(
        () => ({
            user,
            loading,
            isAuthenticated: Boolean(user),
            login,
            register,
            logout,
            setUser,
        }),
        [user, loading, login, register, logout]
    );

    return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>;
}
