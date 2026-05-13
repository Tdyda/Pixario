// src/auth/ProtectedRoute.jsx
import React from "react";
import { Navigate, Outlet, useLocation } from "react-router-dom";
import { useAuth } from "./useAuth.js";

/**
 * <ProtectedRoute roles={['SK','admin']} />  // wystarczy JEDNA z tych ról
 * <ProtectedRoute roles={['SK','admin']} mode="all" /> // wymagaj obu ról
 */
export default function ProtectedRoute({ roles, mode = "any" }) {
    const { isAuthenticated, user, loading } = useAuth();
    const location = useLocation();

    if (loading) {
        return (
            <div className="container py-5 text-center">
                <div className="d-inline-flex align-items-center gap-2">
                    <span className="spinner-border spinner-border-sm" />
                    <span>Sprawdzanie uprawnień…</span>
                </div>
            </div>
        );
    }

    if (!isAuthenticated) {
        return <Navigate to="/401" replace state={{ from: location }} />;
    }

    if (!roles || roles.length === 0) {
        return <Outlet />;
    }

    const userRoles = (Array.isArray(user?.roles) ? user.roles : []).map((role) =>
        String(role).toLowerCase()
    );

    const requiredRoles = roles.map((role) => String(role).toLowerCase());

    const hasRequiredRoles =
        mode === "all"
            ? requiredRoles.every((role) => userRoles.includes(role))
            : requiredRoles.some((role) => userRoles.includes(role));

    if (!hasRequiredRoles) {
        return <Navigate to="/403" replace />;
    }

    return <Outlet />;
}
