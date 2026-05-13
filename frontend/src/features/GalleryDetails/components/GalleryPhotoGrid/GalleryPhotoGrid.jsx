import React, { useMemo, useState } from "react";
import Lightbox from "yet-another-react-lightbox";
import Thumbnails from "yet-another-react-lightbox/plugins/thumbnails";
import Zoom from "yet-another-react-lightbox/plugins/zoom";

import "yet-another-react-lightbox/styles.css";
import "yet-another-react-lightbox/plugins/thumbnails.css";

import styles from "./GalleryPhotoGrid.module.css";

function GalleryPhotoGrid({ photoNames }) {
    const [lightboxIndex, setLightboxIndex] = useState(-1);

    const slides = useMemo(
        () =>
            photoNames.map((url, index) => ({
                src: url,
                alt: `photo-${index}`,
            })),
        [photoNames]
    );

    if (photoNames.length === 0) {
        return (
            <div className={styles.emptyState}>
                <div className={styles.emptyIcon}>
                    <i className="bi bi-images" />
                </div>

                <h3>Brak zdjęć w tej galerii</h3>

                <p>
                    Dodaj pierwsze zdjęcia, aby rozpocząć przeglądanie galerii.
                </p>
            </div>
        );
    }

    return (
        <>
            <div className={styles.grid}>
                {photoNames.map((url, index) => (
                    <button
                        type="button"
                        className={styles.photoCard}
                        key={`${url}-${index}`}
                        onClick={() => setLightboxIndex(index)}
                        aria-label={`Otwórz zdjęcie ${index + 1}`}
                    >
                        <img src={url} alt={`photo-${index}`} />

                        <span className={styles.photoOverlay}>
                            <i className="bi bi-arrows-fullscreen" />
                        </span>
                    </button>
                ))}
            </div>

            <Lightbox
                open={lightboxIndex >= 0}
                close={() => setLightboxIndex(-1)}
                index={lightboxIndex}
                slides={slides}
                plugins={[Thumbnails, Zoom]}
            />
        </>
    );
}

export default GalleryPhotoGrid;