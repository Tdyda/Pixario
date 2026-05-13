import React from "react";
import { ACCEPTED_FILE_TYPES } from "../../helpers/galleryFiles.js";
import styles from "./UploadImagesModal.module.css";

function UploadImagesModal({
                               uploading,
                               fileError,
                               onFileChange,
                               onClose,
                               onUpload,
                           }) {
    return (
        <div className={styles.backdrop} role="presentation">
            <section
                className={styles.modal}
                role="dialog"
                aria-modal="true"
                aria-labelledby="upload-images-title"
            >
                <header className={styles.header}>
                    <div>
                        <span>Upload zdjęć</span>
                        <h2 id="upload-images-title">Dodaj zdjęcia do galerii</h2>
                        <p>Wybierz zdjęcia w formacie JPG, JPEG lub PNG.</p>
                    </div>

                    <button
                        type="button"
                        className={styles.iconButton}
                        onClick={onClose}
                        disabled={uploading}
                        aria-label="Zamknij modal"
                    >
                        <i className="bi bi-x-lg" />
                    </button>
                </header>

                <div className={styles.body}>
                    <label className={styles.uploadBox}>
                        <i className="bi bi-cloud-arrow-up" />
                        <strong>Kliknij, aby wybrać pliki</strong>
                        <small>Dozwolone formaty: jpg, jpeg, png</small>

                        <input
                            type="file"
                            multiple
                            accept={ACCEPTED_FILE_TYPES}
                            onChange={onFileChange}
                            disabled={uploading}
                        />
                    </label>

                    {fileError && (
                        <div className={styles.warning} role="alert">
                            <i className="bi bi-exclamation-triangle" />
                            {fileError}
                        </div>
                    )}
                </div>

                <footer className={styles.footer}>
                    <button
                        type="button"
                        className={styles.ghostButton}
                        onClick={onClose}
                        disabled={uploading}
                    >
                        Anuluj
                    </button>

                    <button
                        type="button"
                        className={styles.primaryButton}
                        onClick={onUpload}
                        disabled={uploading}
                    >
                        {uploading ? (
                            <>
                                <span className="spinner-border spinner-border-sm" aria-hidden="true" />
                                Wysyłanie...
                            </>
                        ) : (
                            <>
                                <i className="bi bi-upload" />
                                Wyślij zdjęcia
                            </>
                        )}
                    </button>
                </footer>
            </section>
        </div>
    );
}

export default UploadImagesModal;