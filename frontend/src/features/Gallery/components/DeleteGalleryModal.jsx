import React from "react";
import styles from "../../../pages/GalleryPage/GalleryPage.module.css";

function DeleteGalleryModal({ gallery, deleting, error, onClose, onConfirm }) {
    return (
        <div className={styles.modalBackdrop} role="presentation">
            <section
                className={styles.modal}
                role="dialog"
                aria-modal="true"
                aria-labelledby="delete-gallery-title"
            >
                <header className={styles.modalHeader}>
                    <div>
                        <span className={styles.dangerEyebrow}>Usuwanie galerii</span>

                        <h2 id="delete-gallery-title">
                            Czy na pewno usunąć galerię?
                        </h2>

                        <p>
                            Galeria <strong>{gallery.name}</strong> zostanie usunięta.
                            Tej operacji nie będzie można cofnąć.
                        </p>
                    </div>
                </header>

                <div className={styles.modalBody}>
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
                        Nie
                    </button>

                    <button
                        type="button"
                        className={`${styles.button} ${styles.dangerButton}`}
                        onClick={onConfirm}
                        disabled={deleting}
                    >
                        {deleting ? (
                            <>
                                <span className="spinner-border spinner-border-sm" />
                                Usuwanie...
                            </>
                        ) : (
                            <>
                                <i className="bi bi-trash" />
                                Tak, usuń
                            </>
                        )}
                    </button>
                </footer>
            </section>
        </div>
    );
}

export default DeleteGalleryModal;