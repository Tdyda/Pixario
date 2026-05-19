import { Navigate } from "react-router-dom";
import { useAuth } from "./useAuth.js";

function HomeRedirect() {
    const { isAuthenticated, loading } = useAuth();

    if (loading) {
        return null;
    }

    if (isAuthenticated) {
        return <Navigate to="/gallery" replace />;
    }

    return <Navigate to="/welcome" replace />;
}

export default HomeRedirect;