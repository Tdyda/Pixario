import { useState } from "react";
import { Link, useNavigate, useSearchParams } from "react-router-dom";
import styles from "./RecoverPasswordPage.module.css";

function RecoverPasswordPage() {
    const [searchParams] = useSearchParams();
    const navigate = useNavigate();

    const [newPassword, setNewPassword] = useState("");
    const [retypedPassword, setRetypedPassword] = useState("");
    const [error, setError] = useState("");
    const [success, setSuccess] = useState(false);
    const [submitting, setSubmitting] = useState(false);

    const token = searchParams.get("token");

    const getRecoverPasswordErrorMessage = (status, message) => {
        switch (status) {
            case 404:
                return "Link do zmiany hasła jest nieprawidłowy.";

            case 410:
                return "Link do zmiany hasła wygasł. Wygeneruj nowy link.";

            case 409:
                return "Ten link został już wykorzystany. Wygeneruj nowy link, jeśli nadal chcesz zmienić hasło.";

            default:
                return message || "Nie udało się zmienić hasła.";
        }
    };

    const handleSubmit = async (event) => {
        event.preventDefault();

        if (!token) {
            setError("Brak tokenu odzyskiwania hasła.");
            return;
        }

        if (!newPassword.trim() || !retypedPassword.trim()) {
            setError("Uzupełnij oba pola hasła.");
            return;
        }

        if (newPassword.length < 8) {
            setError("Hasło musi mieć co najmniej 8 znaków.");
            return;
        }

        if (newPassword !== retypedPassword) {
            setError("Hasła nie są takie same.");
            return;
        }

        setSubmitting(true);
        setError("");

        try {
            const response = await fetch(
                `${import.meta.env.VITE_API_BASE_URL}/recover-password`,
                {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    credentials: "include",
                    body: JSON.stringify({
                        recoverToken: token,
                        newPassword,
                    }),
                }
            );

            if (response.status >= 500) {
                navigate("/500", { replace: true });
                return;
            }

            if (!response.ok) {
                let message = "Nie udało się zmienić hasła.";

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

                throw new Error(getRecoverPasswordErrorMessage(response.status, message));
            }

            setSuccess(true);
        } catch (error) {
            setError(error.message || "Nie udało się zmienić hasła.");
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
                        <i className="bi bi-check-lg" />
                    </div>

                    <span className={styles.eyebrow}>Hasło zmienione</span>

                    <h1>Gotowe! Twoje hasło zostało zmienione</h1>

                    <p>
                        Możesz teraz zalogować się do Pixario używając nowego hasła.
                    </p>

                    <Link to="/auth/login" className={styles.primaryButton}>
                        Przejdź do logowania
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
                    <i className="bi bi-shield-lock" />
                </div>

                <span className={styles.eyebrow}>Reset hasła</span>

                <h1>Ustaw nowe hasło</h1>

                <p>
                    Wprowadź nowe hasło do swojego konta Pixario.
                </p>

                <form className={styles.form} onSubmit={handleSubmit}>
                    <div className={styles.field}>
                        <label htmlFor="newPassword">Nowe hasło</label>

                        <input
                            id="newPassword"
                            type="password"
                            value={newPassword}
                            onChange={(event) => setNewPassword(event.target.value)}
                            disabled={submitting}
                            placeholder="Wprowadź nowe hasło"
                            autoComplete="new-password"
                        />
                    </div>

                    <div className={styles.field}>
                        <label htmlFor="retypedPassword">Powtórz hasło</label>

                        <input
                            id="retypedPassword"
                            type="password"
                            value={retypedPassword}
                            onChange={(event) => setRetypedPassword(event.target.value)}
                            disabled={submitting}
                            placeholder="Powtórz nowe hasło"
                            autoComplete="new-password"
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
                        {submitting ? "Zapisywanie..." : "Zmień hasło"}
                    </button>
                </form>
            </section>
        </main>
    );
}

export default RecoverPasswordPage;