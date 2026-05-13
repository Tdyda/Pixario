import React from "react";
import FormInput from "../../../components/forms/FormInput.jsx";
import styles from "../../../pages/GalleryPage/GalleryPage.module.css";

function CreateGalleryModal({ form, error, creating, onChange, onClose, onSubmit }) {
    return (
        <div className={styles.modalBackdrop} role="presentation">
            <section
                className={styles.modal}
                role="dialog"
                aria-modal="true"
                aria-labelledby="create-gallery-title"
            >
                <header className={styles.modalHeader}>
                    <div>
                        <span className={styles.eyebrow}>Nowa galeria</span>

                        <h2 id="create-gallery-title">
                            Utwórz zabezpieczoną galerię
                        </h2>

                        <p>
                            Po utworzeniu wejdziesz do środka i dodasz zdjęcia do retuszu AI.
                        </p>
                    </div>

                    <button
                        type="button"
                        className={styles.iconButton}
                        onClick={onClose}
                        disabled={creating}
                        aria-label="Zamknij modal"
                    >
                        <i className="bi bi-x-lg" />
                    </button>
                </header>

                <div className={styles.modalBody}>
                    <FormInput
                        label="Nazwa galerii"
                        type="text"
                        name="name"
                        value={form.name}
                        disabled={creating}
                        onChange={onChange}
                    />

                    <FormInput
                        label="Email właściciela"
                        type="email"
                        name="emailAddress"
                        value={form.emailAddress}
                        disabled={creating}
                        onChange={onChange}
                    />

                    <FormInput
                        label="Hasło dla gości"
                        type="password"
                        name="password"
                        value={form.password}
                        disabled={creating}
                        onChange={onChange}
                    />

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
                        disabled={creating}
                    >
                        Anuluj
                    </button>

                    <button
                        type="button"
                        className={`${styles.button} ${styles.primaryButton}`}
                        onClick={onSubmit}
                        disabled={creating}
                    >
                        {creating ? (
                            <>
                                <span className="spinner-border spinner-border-sm" aria-hidden="true" />
                                Tworzenie...
                            </>
                        ) : (
                            <>
                                <i className="bi bi-plus-lg" />
                                Utwórz galerię
                            </>
                        )}
                    </button>
                </footer>
            </section>
        </div>
    );
}

export default CreateGalleryModal;