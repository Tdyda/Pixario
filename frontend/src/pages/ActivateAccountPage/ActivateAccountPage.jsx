import { useEffect, useState } from "react";
import { Link, useNavigate, useSearchParams } from "react-router-dom";
import { api } from "../../api/axios.js";
import styles from "./ActivateAccountPage.module.css";

const STATUS = {
    LOADING: "loading",
    SUCCESS: "success",
    ALREADY_ACTIVE: "already_active",
    ERROR: "error",
};

function ActivateAccountPage() {
    const [searchParams] = useSearchParams();
    const navigate = useNavigate();

    const [status, setStatus] = useState(STATUS.LOADING);

    useEffect(() => {
        const activateAccount = async () => {
            const token = searchParams.get("token");

            if (!token) {
                setStatus(STATUS.ERROR);
                return;
            }

            try {
                const response = await fetch(
                    `${import.meta.env.VITE_API_BASE_URL}/activate-account`,
                    {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                        },
                        credentials: "include",
                        body: JSON.stringify({
                            activationToken: token,
                        }),
                    }
                );

                if (!response.ok) {
                    let errorMessage = "";

                    try {
                        const errorData = await response.json();
                        errorMessage =
                            errorData?.error ||
                            errorData?.message ||
                            "";
                    } catch {
                        // ignore json parse errors
                    }

                    const error = new Error(errorMessage || "Activation failed");

                    error.status = response.status;

                    throw error;
                }

                setStatus(STATUS.SUCCESS);
            }catch (error) {
                const responseStatus = error.status;
                const message = error.message;

                if (responseStatus >= 500) {
                    navigate("/500", { replace: true });
                    return;
                }

                if (message === "Account already activated") {
                    setStatus(STATUS.ALREADY_ACTIVE);
                    return;
                }

                setStatus(STATUS.ERROR);
            }
        };

        activateAccount();
    }, [searchParams, navigate]);

    const content = getContent(status);

    return (
        <main className={styles.page}>
            <section className={styles.card}>
                <div className={styles.brand}>
                    <span>✦</span>
                    pixario
                </div>

                <div className={`${styles.icon} ${styles[content.variant]}`}>
                    <i className={`bi ${content.icon}`} />
                </div>

                <span className={styles.eyebrow}>{content.eyebrow}</span>

                <h1>{content.title}</h1>

                <p>{content.description}</p>

                {status === STATUS.LOADING ? (
                    <div className={styles.spinner}>
                        <span className="spinner-border spinner-border-sm" />
                        Aktywujemy konto...
                    </div>
                ) : (
                    <div className={styles.actions}>
                        <Link to="/auth/login" className={styles.primaryButton}>
                            Przejdź do logowania
                        </Link>

                        <Link to="/" className={styles.secondaryLink}>
                            Wróć na stronę główną
                        </Link>
                    </div>
                )}
            </section>
        </main>
    );
}

function getContent(status) {
    switch (status) {
        case STATUS.SUCCESS:
            return {
                variant: "success",
                icon: "bi-check-lg",
                eyebrow: "Konto aktywowane",
                title: "Gotowe! Twoje konto jest aktywne",
                description:
                    "Możesz już zalogować się do Pixario i rozpocząć tworzenie prywatnych galerii zdjęć.",
            };

        case STATUS.ALREADY_ACTIVE:
            return {
                variant: "info",
                icon: "bi-info-lg",
                eyebrow: "Konto już aktywne",
                title: "To konto zostało już aktywowane",
                description:
                    "Nie musisz robić nic więcej. Przejdź do logowania i korzystaj z Pixario.",
            };

        case STATUS.ERROR:
            return {
                variant: "error",
                icon: "bi-x-lg",
                eyebrow: "Błąd aktywacji",
                title: "Nie udało się aktywować konta",
                description:
                    "Link aktywacyjny może być nieprawidłowy lub wygasł. Spróbuj ponownie albo zarejestruj konto jeszcze raz.",
            };

        case STATUS.LOADING:
        default:
            return {
                variant: "loading",
                icon: "bi-envelope-check",
                eyebrow: "Aktywacja konta",
                title: "Chwileczkę, aktywujemy konto",
                description:
                    "Sprawdzamy link aktywacyjny i kończymy konfigurację Twojego konta Pixario.",
            };
    }
}

export default ActivateAccountPage;