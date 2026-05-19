import React, { useCallback, useEffect, useMemo, useState } from "react";
import { api } from "../../../api/axios.js";
import { getAuth } from "../../../auth/GetAuth.js";
import styles from "../../../pages/GalleryPage/GalleryPage.module.css";

function EditGalleryModal({ gallery, onClose, onImagesDeleted }) {
    const [images, setImages] = useState([]);
    const [selectedImageIds, setSelectedImageIds] = useState([]);
    const [loading, setLoading] = useState(true);
    const [deleting, setDeleting] = useState(false);
    const [error, setError] = useState("");

    const selectedCount = selectedImageIds.length;

    const galleryUrl = gallery.id;
    const galleryId = gallery.id;

    const fetchGalleryImages = useCallback(async () => {
        const userId = getAuth()?.user?.id;

        if (!userId) {
            setError("Brak ID użytkownika.");
            setLoading(false);
            setLoading(false);
            return;
        }

        setLoading(true);
        setError("");

        try {
            const response = await api.get(`/gallery/${galleryUrl}/${userId}`);
            const imageList = Array.isArray(response.data?.images)
                ? response.data.images
                : [];

            setImages(imageList);
            setSelectedImageIds([]);
        } catch (error) {
            console.error("Nie udało się pobrać zdjęć galerii:", error);
            setError("Nie udało się pobrać zdjęć galerii.");
        } finally {
            setLoading(false);
        }
    }, [galleryUrl]);

    useEffect(() => {
        fetchGalleryImages();
    }, [fetchGalleryImages]);

    const allImageIds = useMemo(() => {
        return images
            .map((image) => Number(image.id))
            .filter((id) => id !== undefined && id !== null);
    }, [images]);

    const areAllSelected =
        allImageIds.length > 0 && selectedImageIds.length === allImageIds.length;

    const toggleImage = (imageId) => {
        setSelectedImageIds((prev) => {
            if (prev.includes(imageId)) {
                return prev.filter((id) => id !== imageId);
            }

            return [...prev, imageId];
        });
    };

    const toggleAllImages = () => {
        setSelectedImageIds(areAllSelected ? [] : allImageIds);
    };

    const handleDeleteImages = async () => {
        if (selectedImageIds.length === 0) {
            setError("Zaznacz przynajmniej jedno zdjęcie.");
            return;
        }

        setDeleting(true);
        setError("");

        try {
            await api.delete(`/gallery/${galleryId}`, {
                data: {
                    imageIds: selectedImageIds,
                },
            });

            setSelectedImageIds([]);

            await fetchGalleryImages();
            await onImagesDeleted?.();
        } catch (error) {
            console.error("Nie udało się usunąć zdjęć:", error);
            setError("Nie udało się usunąć zaznaczonych zdjęć. Spróbuj ponownie.");
        } finally {
            setDeleting(false);
        }
    };

    return (
        <div className={styles.modalBackdrop} role="presentation">
            <section
                className={`${styles.modal} ${styles.editGalleryModal}`}
                role="dialog"
                aria-modal="true"
                aria-labelledby="edit-gallery-title"
            >
                <header className={styles.modalHeader}>
                    <div>
                        <span className={styles.eyebrow}>Edycja galerii</span>

                        <h2 id="edit-gallery-title">
                            Edytuj zdjęcia w galerii
                        </h2>

                        <p>
                            Galeria <strong>{gallery.name}</strong>. Zaznacz zdjęcia,
                            które chcesz usunąć.
                        </p>
                    </div>

                    <button
                        type="button"
                        className={styles.iconButton}
                        onClick={onClose}
                        disabled={deleting}
                        aria-label="Zamknij modal"
                    >
                        <i className="bi bi-x-lg" />
                    </button>
                </header>

                <div className={styles.modalBody}>
                    {loading && (
                        <div className={styles.editGalleryState}>
                            <div className="spinner-border" role="status">
                                <span className="visually-hidden">Ładowanie...</span>
                            </div>
                            <p>Ładujemy zdjęcia...</p>
                        </div>
                    )}

                    {!loading && images.length > 0 && (
                        <>
                            <div className={styles.editGalleryToolbar}>
                                <label className={styles.selectAllRow}>
                                    <input
                                        type="checkbox"
                                        checked={areAllSelected}
                                        onChange={toggleAllImages}
                                        disabled={deleting}
                                    />
                                    <span>
                                        Zaznacz wszystkie ({images.length})
                                    </span>
                                </label>

                                <span className={styles.selectedCounter}>
                                    Zaznaczono: {selectedCount}
                                </span>
                            </div>

                            <div className={styles.imageList}>
                                {images.map((image) => {
                                    const imageId = Number(image.id);
                                    const isSelected = selectedImageIds.includes(imageId);

                                    return (
                                        <label
                                            key={imageId}
                                            className={`${styles.imageListItem} ${
                                                isSelected ? styles.imageListItemSelected : ""
                                            }`}
                                        >
                                            <input
                                                type="checkbox"
                                                checked={isSelected}
                                                onChange={() => toggleImage(imageId)}
                                                disabled={deleting}
                                            />

                                            <img
                                                src={image.name}
                                                alt=""
                                            />

                                            <div>
                                                <strong>
                                                    {image.fileName}
                                                </strong>
                                                {/*<span>{image.name}</span>*/}
                                            </div>
                                        </label>
                                    );
                                })}
                            </div>
                        </>
                    )}

                    {!loading && images.length === 0 && (
                        <div className={styles.editGalleryState}>
                            <i className="bi bi-images" />
                            <h3>Brak zdjęć</h3>
                            <p>Ta galeria nie ma jeszcze żadnych zdjęć.</p>
                        </div>
                    )}

                    {error && (
                        <div className={styles.warningAlert} role="alert">
                            <i className="bi bi-exclamation-triangle" />
                            {error}
                        </div>
                    )}
                </div>

                <footer className={styles.modalFooter}>
                    <button
                        type="button"
                        className={`${styles.button} ${styles.ghostButton}`}
                        onClick={onClose}
                        disabled={deleting}
                    >
                        Zamknij
                    </button>

                    <button
                        type="button"
                        className={`${styles.button} ${styles.dangerButton}`}
                        onClick={handleDeleteImages}
                        disabled={deleting || selectedImageIds.length === 0}
                    >
                        {deleting ? (
                            <>
                                <span className="spinner-border spinner-border-sm" />
                                Usuwanie...
                            </>
                        ) : (
                            <>
                                <i className="bi bi-trash" />
                                Usuń zdjęcia
                            </>
                        )}
                    </button>
                </footer>
            </section>
        </div>
    );
}

export default EditGalleryModal;