import { useState } from "react";
import styles from "./Navbar.module.css";
import { handleLogout } from "../Gallery/helpers/logout.js";
import { useNavigate } from "react-router-dom";
import { useAuth } from "../../auth/useAuth.js";
import { useNotifications } from "../notifications/useProcessNotifications.js";

const Navbar = () => {
    const navigate = useNavigate();
    const { user } = useAuth();

    const [isOpen, setIsOpen] = useState(false);
    const [visibleNotifications, setVisibleNotifications] = useState([]);

    const {
        notifications,
        hasUnread,
        markAllAsRead,
        clearNotifications,
    } = useNotifications(user?.id);

    const handleNotificationsClick = async () => {
        const nextOpen = !isOpen;

        setIsOpen(nextOpen);

        if (nextOpen) {
            setVisibleNotifications(notifications);

            if (hasUnread) {
                await markAllAsRead({ clearLocal: false });
            }
        } else {
            setVisibleNotifications([]);
            clearNotifications();
        }
    };

    const notificationCount = visibleNotifications.length;

    return (
        <header className={styles.topbar}>
            <a className={styles.brand} href="/gallery" aria-label="Pixario">
                <span>✦</span>
                pixario
            </a>

            <div className={styles.topbarActions}>
                <div className={styles.notificationWrapper}>
                    <button
                        className={`${styles.iconButton} ${isOpen ? styles.iconButtonActive : ""}`}
                        type="button"
                        aria-label="Powiadomienia"
                        onClick={handleNotificationsClick}
                    >
                        <i className="bi bi-bell" />

                        {hasUnread && (
                            <span className={styles.notificationDot} />
                        )}
                    </button>

                    {isOpen && (
                        <div className={styles.notificationDropdown}>
                            <div className={styles.notificationHeader}>
                                <div>
                                    <span className={styles.eyebrow}>Pixario AI</span>
                                    <h3>Powiadomienia</h3>
                                </div>

                                <span className={styles.notificationCount}>
                                    {notificationCount}
                                </span>
                            </div>

                            <div className={styles.notificationList}>
                                {visibleNotifications.length === 0 ? (
                                    <div className={styles.emptyNotifications}>
                                        <div className={styles.emptyIcon}>
                                            <i className="bi bi-bell" />
                                        </div>

                                        <strong>Brak nowych powiadomień</strong>

                                        <p>
                                            Damy znać, gdy przetwarzanie zdjęć zostanie zakończone.
                                        </p>
                                    </div>
                                ) : (
                                    visibleNotifications.map((notification, index) => (
                                        <div
                                            key={
                                                notification.id ??
                                                `${notification.type}-${notification.galleryId}-${index}`
                                            }
                                            className={styles.notificationItem}
                                        >
                                            <div className={styles.notificationIcon}>
                                                <i className="bi bi-magic" />
                                            </div>

                                            <div className={styles.notificationContent}>
                                                <strong>
                                                    {notification.title ?? "Przetwarzanie zdjęć"}
                                                </strong>

                                                <p>
                                                    {notification.message ??
                                                        notification.content ??
                                                        notification.text ??
                                                        "Zdjęcia w galerii zostały przetworzone."}
                                                </p>

                                                {notification.galleryName && (
                                                    <span className={styles.notificationMeta}>
                                                        Galeria: {notification.galleryName}
                                                    </span>
                                                )}
                                            </div>
                                        </div>
                                    ))
                                )}
                            </div>
                        </div>
                    )}
                </div>

                <button
                    className={styles.iconButton}
                    type="button"
                    onClick={() => handleLogout(navigate)}
                    aria-label="Wyloguj"
                >
                    <i className="bi bi-box-arrow-right" />
                </button>
            </div>
        </header>
    );
};

export default Navbar;