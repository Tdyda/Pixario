import { useEffect, useState } from "react";
import { api } from "../../api/axios.js";

const VITE_MERCURE_DOMAIN = import.meta.env.VITE_MERCURE_DOMAIN;
export function useNotifications(userId) {
    const [notifications, setNotifications] = useState([]);

    useEffect(() => {
        if (!userId) return;

        api

            .get("/notifications")
            .then((response) => {
                setNotifications(response.data);
            })
            .catch(console.error);
    }, [userId]);

    useEffect(() => {
        if (!userId) return;

        const topic = encodeURIComponent(`/users/${userId}/notifications`);

        const eventSource = new EventSource(
            `${VITE_MERCURE_DOMAIN}/.well-known/mercure?topic=${topic}`,
            { withCredentials: true }
        );

        eventSource.onmessage = (event) => {
            const notification = JSON.parse(event.data);

            setNotifications((current) => [
                notification,
                ...current,
            ]);
        };

        eventSource.onerror = console.error;

        return () => {
            eventSource.close();
        };
    }, [userId]);

    const markAllAsRead = async ({ clearLocal = true } = {}) => {
        if (notifications.length === 0) return;

        await api.patch("/notifications");

        if (clearLocal) {
            setNotifications([]);
        }
    };

    const clearNotifications = () => {
        setNotifications([]);
    };

    return {
        notifications,
        unreadCount: notifications.length,
        hasUnread: notifications.length > 0,
        markAllAsRead,
        clearNotifications
    };
}
