import React from "react";
import styles from "../../../pages/GalleryPage/GalleryPage.module.css";

function GalleryStateCard({ type, title, description, actionLabel, onAction }) {
    const isLoading = type === "loading";
    const isError = type === "error";

    return (
        <div className={`${styles.stateCard} ${isError ? styles.stateError : ""}`}>
            {isLoading ? (
                <div className="spinner-border" role="status">
                    <span className="visually-hidden">Ładowanie...</span>
                </div>
            ) : (
                <i className="bi bi-exclamation-octagon" />
            )}

            <h3>{title}</h3>
            <p>{description}</p>

            {actionLabel && (
                <button
                    className={`${styles.button} ${styles.primaryButton}`}
                    type="button"
                    onClick={onAction}
                >
                    {actionLabel}
                </button>
            )}
        </div>
    );
}

export default GalleryStateCard;