import { useState } from "react";
import { Link, useNavigate } from "react-router-dom";
import styles from "./RecoverTokenPage.module.css";

function RecoverTokenPage() {
    const navigate = useNavigate();

    const [email, setEmail] = useState("");
    const [error, setError] = useState("");
    const [success, setSuccess] = useState(false);
    const [submitting, setSubmitting] = useState(false);

    const handleSubmit = async (event) => {
        event.preventDefault();

        if (!email.trim()) {
            setError("Podaj adres e-mail.");
            return;
        }

        setSubmitting(true);
        setError("");

        try {
            const response = await fetch(
                `${import.meta.env.VITE_API_BASE_URL}/recover-token`,
                {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    credentials: "include",
                    body: JSON.stringify({
                        email: email.trim(),
                    }),
                }
            );

            if (!response.ok) {
                let message = "Nie udało się wysłać linku do zmiany hasła.";

                try {
                    const data = await response.json();
                    message = data?.error || data?.message || message;
                } catch {
                    // ignore
                }

                if (response.status >= 500) {
                    navigate("/500", { replace: true });
                    return;
                }

                throw new Error(message);
            }

            setSuccess(true);
        } catch (error) {
            setError(error.message || "Nie udało się wysłać linku do zmiany hasła.");
        } finally {
            setSubmitting(false);
        }
    };

    if (success) {
        return (
            <main className={styles.page}>
                <section className={styles.card}>
                    <div className={styles.brand}>
                        <span>✦</span>
                        pixario
                    </div>

                    <div className={`${styles.icon} ${styles.success}`}>
                        <i className="bi bi-envelope-check" />
                    </div>

                    <span className={styles.eyebrow}>Link wysłany</span>

                    <h1>Sprawdź swoją skrzynkę</h1>

                    <p>
                        Wysłaliśmy link do zmiany hasła na adres:
                        <br />
                        <strong>{email}</strong>
                    </p>

                    <div className={styles.hint}>
                        <i className="bi bi-info-circle" />
                        Jeśli wiadomość nie dotarła w ciągu kilku minut, sprawdź folder spam
                        lub oferty.
                    </div>

                    <Link to="/auth" className={styles.primaryButton}>
                        Wróć do logowania
                    </Link>
                </section>
            </main>
        );
    }

    return (
        <main className={styles.page}>
            <section className={styles.card}>
                <div className={styles.brand}>
                    <span>✦</span>
                    pixario
                </div>

                <div className={styles.icon}>
                    <i className="bi bi-key" />
                </div>

                <span className={styles.eyebrow}>Odzyskiwanie hasła</span>

                <h1>Nie pamiętasz hasła?</h1>

                <p>
                    Podaj adres e-mail przypisany do konta. Wyślemy link do ustawienia
                    nowego hasła.
                </p>

                <form className={styles.form} onSubmit={handleSubmit}>
                    <div className={styles.field}>
                        <label htmlFor="recoverEmail">Adres e-mail</label>

                        <input
                            id="recoverEmail"
                            type="email"
                            value={email}
                            onChange={(event) => setEmail(event.target.value)}
                            disabled={submitting}
                            placeholder="name@example.com"
                            autoComplete="email"
                        />
                    </div>

                    {error && (
                        <div className={styles.error} role="alert">
                            <i className="bi bi-exclamation-triangle" />
                            {error}
                        </div>
                    )}

                    <button
                        type="submit"
                        className={styles.primaryButton}
                        disabled={submitting}
                    >
                        {submitting ? "Wysyłanie..." : "Wyślij link"}
                    </button>
                </form>

                <Link to="/auth" className={styles.secondaryLink}>
                    Wróć do logowania
                </Link>
            </section>
        </main>
    );
}

export default RecoverTokenPage;