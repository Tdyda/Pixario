import {api} from "../../../api/axios.js";
import {getAuth} from "../../../auth/GetAuth.js"

export const handleLogout = async (navigate) => {
    try {
        const auth = getAuth();

        await api.post("/auth/logout", {
            refreshToken: auth?.user?.refreshToken,
        });

        localStorage.removeItem("app_auth");

        navigate("/auth/login", { replace: true });
    } catch (error) {
        console.error("Logout error:", error);

        localStorage.removeItem("app_auth");

        navigate("/auth/login", { replace: true });
    }
};