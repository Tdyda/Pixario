import React, { useEffect, useMemo, useState } from "react";
import { ACCEPTED_FILE_TYPES } from "../../helpers/galleryFiles.js";
import styles from "./UploadImagesModal.module.css";

function UploadImagesModal({
                               uploading,
                               fileError,
                               selectedFiles,
                               onFileChange,
                               onClose,
                               onUpload,
                           }) {
    const [isDragging, setIsDragging] = useState(false);

    const selectedCount = selectedFiles?.length || 0;
    const firstFile = selectedFiles?.[0];

    const previewUrl = useMemo(() => {
        if (!firstFile || selectedCount !== 1) {
            return null;
        }

        return URL.createObjectURL(firstFile);
    }, [firstFile, selectedCount]);

    useEffect(() => {
        return () => {
            if (previewUrl) {
                URL.revokeObjectURL(previewUrl);
            }
        };
    }, [previewUrl]);

    const handleDragOver = (event) => {
        event.preventDefault();
        setIsDragging(true);
    };

    const handleDragLeave = () => {
        setIsDragging(false);
    };

    const handleDrop = (event) => {
        event.preventDefault();
        setIsDragging(false);

        const files = event.dataTransfer.files;

        onFileChange({
            target: {
                files,
            },
        });
    };

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
                    <label
                        className={`${styles.uploadBox} ${
                            isDragging ? styles.uploadBoxDragging : ""
                        } ${selectedCount > 0 ? styles.uploadBoxSelected : ""}`}
                        onDragOver={handleDragOver}
                        onDragLeave={handleDragLeave}
                        onDrop={handleDrop}
                    >
                        {selectedCount === 0 && (
                            <>
                                <i className="bi bi-cloud-arrow-up" />
                                <strong>Kliknij lub przeciągnij zdjęcia tutaj</strong>
                                <small>Dozwolone formaty: jpg, jpeg, png</small>
                            </>
                        )}

                        {selectedCount === 1 && previewUrl && (
                            <div className={styles.singlePreview}>
                                <img src={previewUrl} alt="Podgląd wybranego zdjęcia" />

                                <div>
                                    <strong>Dodano 1 zdjęcie</strong>
                                    <small>{firstFile.name}</small>
                                </div>
                            </div>
                        )}

                        {selectedCount > 1 && (
                            <div className={styles.multiPreview}>
                                <div className={styles.multiIcon}>
                                    <i className="bi bi-images" />
                                </div>

                                <strong>Dodano {selectedCount} zdjęć</strong>
                                <small>Pliki są gotowe do wysłania.</small>
                            </div>
                        )}

                        <input
                            type="file"
                            multiple
                            accept={ACCEPTED_FILE_TYPES}
                            onChange={onFileChange}
                            disabled={uploading}
                        />
                    </label>

                    {selectedCount > 0 && (
                        <p className={styles.selectionHint}>
                            Możesz kliknąć obszar ponownie, aby wybrać inne pliki.
                        </p>
                    )}

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
                        disabled={uploading || selectedCount === 0}
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