import React from "react";
import styles from "./CredentialsForm.module.css";

function CredentialsForm({
                             credentials,
                             error,
                             loading,
                             onChange,
                             onSubmit,
                         }) {
    return (
        <main className={styles.page}>
            <section className={styles.card}>
                <div className={styles.icon}>
                    <i className="bi bi-lock" />
                </div>

                <span className={styles.eyebrow}>Galeria prywatna</span>

                <h1>Ta galeria jest zabezpieczona hasłem</h1>

                <p>
                    Podaj dane dostępowe, aby zobaczyć zdjęcia udostępnione w tej galerii.
                </p>

                <form onSubmit={onSubmit} className={styles.form}>
                    <div className={styles.field}>
                        <label htmlFor="galleryEmail">Adres e-mail</label>
                        <input
                            id="galleryEmail"
                            type="email"
                            name="emailAddress"
                            value={credentials.emailAddress}
                            onChange={onChange}
                            disabled={loading}
                            placeholder="name@example.com"
                        />
                    </div>

                    <div className={styles.field}>
                        <label htmlFor="galleryPassword">Hasło</label>
                        <input
                            id="galleryPassword"
                            type="password"
                            name="password"
                            value={credentials.password}
                            onChange={onChange}
                            disabled={loading}
                            placeholder="Wprowadź hasło"
                        />
                    </div>

                    {error && (
                        <div className={styles.warning} role="alert">
                            <i className="bi bi-exclamation-triangle" />
                            {error}
                        </div>
                    )}

                    <button type="submit" disabled={loading}>
                        {loading ? "Sprawdzanie..." : "Otwórz galerię"}
                    </button>
                </form>
            </section>
        </main>
    );
}

export default CredentialsForm;