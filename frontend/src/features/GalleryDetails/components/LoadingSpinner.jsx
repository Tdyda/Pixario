import React from "react";
import styles from "../../../pages/GalleryDetailsPage/GalleryDetailsPage.module.css";

function LoadingSpinner() {
    return (
        <div className={styles.stateCard}>
            <div className="spinner-border" role="status">
                <span className="visually-hidden">Loading...</span>
            </div>

            <h3>Ładujemy galerię</h3>
            <p>Sprawdzamy zdjęcia i dane dostępowe.</p>
        </div>
    );
}

export default LoadingSpinner;