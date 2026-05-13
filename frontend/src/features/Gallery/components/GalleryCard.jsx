import React, {useEffect, useRef, useState} from "react";
import { formatGalleryDate } from "../helpers/galleryDate.js";
import styles from "../../../pages/GalleryPage/GalleryPage.module.css";

function GalleryCard({ gallery, onClick, onDelete, onEdit }) {
    const [isMenuOpen, setIsMenuOpen] = useState(false);
    const menuRef = useRef(null);

    useEffect(() => {
        const handleClickOutside = (event) => {
            if (
                menuRef.current &&
                !menuRef.current.contains(event.target)
            ) {
                setIsMenuOpen(false);
            }
        };

        if (isMenuOpen) {
            document.addEventListener("mousedown", handleClickOutside);
        }

        return () => {
            document.removeEventListener("mousedown", handleClickOutside);
        };
    }, [isMenuOpen]);

    const createdAt = gallery.created_at?.date || gallery.createdAt;
    const photosCount = gallery.imagesCount ?? gallery.images?.length ?? 0;

    const toggleMenu = (event) => {
        event.stopPropagation();
        setIsMenuOpen((prev) => !prev);
    };

    const handleEdit = (event) => {
        event.stopPropagation();
        setIsMenuOpen(false);
        onEdit?.(gallery);
    };

    const handleDelete = (event) => {
        event.stopPropagation();
        setIsMenuOpen(false);
        onDelete?.(gallery);
    };

    return (
        <article className="col-12 col-sm-6 col-xl-4 col-xxl-3">
            <button
                type="button"
                className={styles.galleryCard}
                onClick={onClick}
                aria-label={`Otwórz galerię ${gallery.name}`}
            >
                <div className={styles.galleryCover}>
                    {gallery.previewUrl ? (
                        <img src={gallery.previewUrl} alt="" />
                    ) : (
                        <div className={styles.galleryPlaceholder}>
                            <i className="bi bi-images" />
                            <span>Brak zdjęć</span>
                        </div>
                    )}
                </div>

                <div className={styles.galleryCardBody}>
                    <div className="d-flex align-items-start justify-content-between gap-3">
                        <div>
                            <h3>{gallery.name}</h3>
                            <p>
                                {photosCount} zdjęć · utworzono{" "}
                                {formatGalleryDate(createdAt.date)}
                            </p>
                        </div>

                        <div className={styles.cardMenuWrapper} ref={menuRef}>
                            <button
                                type="button"
                                className={styles.cardMenuButton}
                                onClick={toggleMenu}
                                aria-label="Otwórz menu galerii"
                            >
                                <i className="bi bi-three-dots" />
                            </button>

                            {isMenuOpen && (
                                <div className={styles.contextMenu}>
                                    <button type="button" onClick={handleEdit}>
                                        <i className="bi bi-pencil" />
                                        Edytuj galerię
                                    </button>

                                    <button
                                        type="button"
                                        className={styles.dangerMenuItem}
                                        onClick={handleDelete}
                                    >
                                        <i className="bi bi-trash" />
                                        Usuń galerię
                                    </button>
                                </div>
                            )}
                        </div>
                    </div>

                    <div className={styles.cardFooter}>
                        <span>
                            <i className="bi bi-magic" /> AI retouch ready
                        </span>
                        <i className="bi bi-arrow-right" />
                    </div>
                </div>
            </button>
        </article>
    );
}

export default GalleryCard;