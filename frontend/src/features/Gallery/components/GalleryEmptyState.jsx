import React from "react";
import styles from "../../../pages/GalleryPage/GalleryPage.module.css";

function GalleryEmptyState({ onCreateGallery }) {
    return (
        <div className={styles.emptyState}>
            <div className={styles.emptyIcon}>
                <i className="bi bi-images" />
            </div>

            <h3>Nie masz jeszcze galerii</h3>

            <p>
                Utwórz pierwszą galerię, ustaw hasło dla gości i dodaj zdjęcia,
                które Pixario przetworzy w tle.
            </p>

            <button
                type="button"
                className={`${styles.button} ${styles.primaryButton}`}
                onClick={onCreateGallery}
            >
                <i className="bi bi-plus-lg" />
                Utwórz pierwszą galerię
            </button>
        </div>
    );
}

export default GalleryEmptyState;