import { Link, useNavigate } from "react-router-dom";
import styles from "./ErrorPage.module.css";

const ERROR_CONFIG = {
    401: {
        icon: "bi-lock",
        title: "Nieautoryzowany",
        description: "Musisz się zalogować, aby uzyskać dostęp do tej strony.",
        accent: "purple",
    },
    403: {
        icon: "bi-lock-fill",
        title: "Zabroniony",
        description: "Nie masz uprawnień do dostępu do tego zasobu.",
        accent: "red",
    },
    404: {
        icon: "bi-rocket-takeoff",
        title: "Nie znaleziono",
        description: "Ups! Strona, której szukasz, nie istnieje.",
        accent: "purple",
    },
    500: {
        icon: "bi-bug",
        title: "Coś poszło nie tak",
        description: "Wystąpił błąd po naszej stronie. Spróbuj ponownie później.",
        accent: "red",
    },
};

function ErrorPage({ code }) {
    const navigate = useNavigate();
    const config = ERROR_CONFIG[code] || ERROR_CONFIG[500];

    return (
        <main className={styles.page}>
            <div className={styles.header}>
                <Link to="/" className={styles.logo}>
                    <span>✣</span>
                    pixario
                </Link>

                <button
                    type="button"
                    className={styles.logoutButton}
                    onClick={() => navigate("/auth/login")}
                    title="Przejdź do logowania"
                >
                    <i className="bi bi-box-arrow-right" />
                </button>
            </div>

            <section className={styles.content}>
                <div className={`${styles.iconCircle} ${styles[config.accent]}`}>
                    <i className={`bi ${config.icon}`} />
                </div>

                <h1>{code}</h1>
                <h2>{config.title}</h2>

                <p>{config.description}</p>

                <div className={styles.actions}>
                    {code === 401 && (
                        <Link to="/auth/login" className={styles.primaryButton}>
                            Przejdź do logowania
                        </Link>
                    )}

                    {code === 500 && (
                        <button
                            type="button"
                            className={styles.primaryButton}
                            onClick={() => window.location.reload()}
                        >
                            Spróbuj ponownie
                        </button>
                    )}

                    <Link to="/" className={styles.secondaryLink}>
                        Wróć na stronę główną
                    </Link>
                </div>
            </section>
        </main>
    );
}

export default ErrorPage;