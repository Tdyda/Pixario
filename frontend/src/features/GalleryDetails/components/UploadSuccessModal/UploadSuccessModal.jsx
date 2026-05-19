import React from "react";
import styles from "./UploadSuccessModal.module.css";

function UploadSuccessModal({ onClose }) {
    return (
        <div className={styles.backdrop} role="presentation">
            <section
                className={styles.modal}
                role="dialog"
                aria-modal="true"
                aria-labelledby="upload-success-title"
            >
                <div className={styles.icon}>
                    <i className="bi bi-cloud-check" />
                </div>

                <span className={styles.eyebrow}>Zdjęcia wysłane</span>

                <h2 id="upload-success-title">
                    Zdjęcia trafiły do przetworzenia
                </h2>

                <p>
                    Za jakiś czas pojawią się w galerii. Poinformujemy Cię mailem,
                    gdy przetwarzanie się zakończy.
                </p>

                <div className={styles.hint}>
                    <i className="bi bi-info-circle" />
                    Sprawdź także folder spam. Powiadomienie otrzymasz również w aplikacji.
                </div>

                <button
                    type="button"
                    className={styles.primaryButton}
                    onClick={onClose}
                >
                    Rozumiem
                </button>
            </section>
        </div>
    );
}

export default UploadSuccessModal;