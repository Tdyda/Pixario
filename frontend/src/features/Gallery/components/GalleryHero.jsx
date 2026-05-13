import React from "react";
import styles from "../../../pages/GalleryPage/GalleryPage.module.css";

function GalleryHero({ onCreateGallery }) {
    return (
        <section className={styles.heroCard}>
            <div>
                <span className={styles.eyebrow}>Twoje studio retuszu AI</span>

                <h1>Galerie zdjęć gotowe do automatycznego retuszu portretów.</h1>

                <p>
                    Twórz prywatne galerie, udostępniaj je hasłem i przetwarzaj zdjęcia
                    w tle bez ręcznej edycji.
                </p>
            </div>

            <button
                type="button"
                className={`${styles.button} ${styles.primaryButton} ${styles.heroButton}`}
                onClick={onCreateGallery}
            >
                <i className="bi bi-plus-lg" />
                Nowa galeria
            </button>
        </section>
    );
}

export default GalleryHero;