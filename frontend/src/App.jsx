import React, { useEffect } from "react";
import { Route, Routes } from "react-router-dom";

import Dashboard from "./pages/DashboardPage.jsx";
import AuthPage from "./pages/AuthPage/AuthPage.jsx";
import GalleryPage from "./pages/GalleryPage/GalleryPage.jsx";
import GalleryDetailsPage from "./pages/GalleryDetailsPage/GalleryDetailsPage.jsx";

import UnauthorizedPage from "./pages/errors/UnauthorizedPage.jsx";
import ForbiddenPage from "./pages/errors/ForbiddenPage.jsx";
import NotFoundPage from "./pages/errors/NotFoundPage.jsx";
import ServerErrorPage from "./pages/errors/ServerErrorPage.jsx";

import ProtectedRoute from "./auth/ProtectedRoute.jsx";
import { useAuth } from "./auth/useAuth.js";
import { setAuthHelpers } from "./api/authBridge.js";
import WelcomePage from "./pages/WelcomePage/WelcomePage.jsx";
import {useNotifications} from "./features/notifications/useProcessNotifications.js";
import ActivateAccountPage from "./pages/ActivateAccountPage/ActivateAccountPage.jsx";

export default function App() {
    const { user, logout } = useAuth();

    useNotifications(user?.id);

    useEffect(() => {
        setAuthHelpers({
            logout,
        });
    }, [logout]);

    return (
        <Routes>
            {/* Logowanie / rejestracja */}
            <Route path="/auth/:tab" element={<AuthPage />} />
            <Route path="/activate-account" element={<ActivateAccountPage />} />

            <Route path="/" element={<WelcomePage />} />

            {/* Strony błędów */}
            <Route path="/401" element={<UnauthorizedPage />} />
            <Route path="/403" element={<ForbiddenPage />} />
            <Route path="/500" element={<ServerErrorPage />} />

            {/* Strony wymagające zalogowania */}
            <Route element={<ProtectedRoute />}>
                <Route path="/dashboard" element={<Dashboard />} />
                <Route path="/gallery" element={<GalleryPage />} />
            </Route>

            {/* Strona szczegółów galerii — dostępna również dla niezalogowanych */}
            <Route path="/gallery/:dir" element={<GalleryDetailsPage />} />

            {/* 404 musi być na końcu */}
            <Route path="*" element={<NotFoundPage />} />
        </Routes>
    );
}