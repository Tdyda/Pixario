import { useEffect, useRef, useState } from "react";
import styles from "./Navbar.module.css";
import { handleLogout } from "../Gallery/helpers/logout.js";
import { useLocation, useNavigate } from "react-router-dom";
import { useAuth } from "../../auth/useAuth.js";
import { useNotifications } from "../notifications/useProcessNotifications.js";

const Navbar = () => {
    const navigate = useNavigate();
    const location = useLocation();
    const { user } = useAuth();

    const notificationRef = useRef(null);

    const [isOpen, setIsOpen] = useState(false);
    const [visibleNotifications, setVisibleNotifications] = useState([]);

    const [isShareOpen, setIsShareOpen] = useState(false);
    const [copied, setCopied] = useState(false);

    const {
        notifications,
        hasUnread,
        markAllAsRead,
        clearNotifications,
    } = useNotifications(user?.id);

    const isGalleryDetailsPage =
        location.pathname.startsWith("/gallery/") &&
        location.pathname !== "/gallery";

    const currentUrl = window.location.href;

    useEffect(() => {
        const handleClickOutside = (event) => {
            if (
                notificationRef.current &&
                !notificationRef.current.contains(event.target)
            ) {
                setIsOpen(false);
                setVisibleNotifications([]);
                clearNotifications();
            }
        };

        if (isOpen) {
            document.addEventListener("mousedown", handleClickOutside);
        }

        return () => {
            document.removeEventListener("mousedown", handleClickOutside);
        };
    }, [isOpen, clearNotifications]);

    useEffect(() => {
        if (isOpen) {
            setVisibleNotifications(notifications);
        }
    }, [isOpen, notifications]);

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

    const handleShareClick = () => {
        setCopied(false);
        setIsShareOpen(true);
    };

    const handleCopyLink = async () => {
        try {
            await navigator.clipboard.writeText(currentUrl);
            setCopied(true);
        } catch {
            setCopied(false);
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
                {isGalleryDetailsPage && (
                    <button
                        className={styles.iconButton}
                        type="button"
                        onClick={handleShareClick}
                        aria-label="Udostępnij galerię"
                    >
                        <i className="bi bi-share" />
                    </button>
                )}

                <div className={styles.notificationWrapper} ref={notificationRef}>
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

            {isShareOpen && (
                <div className={styles.shareBackdrop} role="presentation">
                    <section
                        className={styles.shareModal}
                        role="dialog"
                        aria-modal="true"
                        aria-labelledby="share-gallery-title"
                    >
                        <header className={styles.shareHeader}>
                            <div>
                                <span className={styles.shareEyebrow}>Udostępnianie</span>
                                <h2 id="share-gallery-title">Link do galerii</h2>
                                <p>Skopiuj link i wyślij go osobie, której chcesz udostępnić galerię.</p>
                            </div>

                            <button
                                type="button"
                                className={styles.shareCloseButton}
                                onClick={() => setIsShareOpen(false)}
                                aria-label="Zamknij okno"
                            >
                                <i className="bi bi-x-lg" />
                            </button>
                        </header>

                        <div className={styles.shareBody}>
                            <div className={styles.shareInputWrapper}>
                                <input value={currentUrl} readOnly />

                                <button type="button" onClick={handleCopyLink}>
                                    <i className="bi bi-clipboard" />
                                    Kopiuj
                                </button>
                            </div>

                            {copied && (
                                <div className={styles.shareSuccess}>
                                    <i className="bi bi-check-circle" />
                                    Link został skopiowany do schowka.
                                </div>
                            )}
                        </div>
                    </section>
                </div>
            )}
        </header>
    );
};

export default Navbar;