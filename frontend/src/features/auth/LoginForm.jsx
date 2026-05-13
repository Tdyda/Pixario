import { useState } from "react";
import styles from "./AuthForm.module.css";
import { validateLogin } from "./validateLogin.js";
import { useAuth } from "../../auth/useAuth.js";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faEye, faEyeSlash } from "@fortawesome/free-solid-svg-icons";
import { useNavigate, useLocation } from "react-router-dom";

const LoginForm = () => {
    const [formData, setFormData] = useState({
        email: "",
        password: "",
        rememberMe: false,
    });

    const [errors, setErrors] = useState({});
    const [showPassword, setShowPassword] = useState(false);

    const navigate = useNavigate();
    const location = useLocation();
    const from = location.state?.from?.pathname || "/gallery";

    const { login } = useAuth();

    const handleSubmit = async (e) => {
        e.preventDefault();

        const validationErrors = validateLogin(formData);

        if (Object.keys(validationErrors).length > 0) {
            setErrors(validationErrors);
            return;
        }

        try {
            await login({
                email: formData.email,
                password: formData.password,
                rememberMe: formData.rememberMe,
            });

            navigate(from, { replace: true });
        } catch (err) {
            const apiError = err.response?.data?.error;

            const message = apiError?.message || err.message;
            const status = apiError?.status || err.response?.status;

            setErrors({ general: message });

            if (status === 401) {
                navigate("/auth/login", { replace: true });
            }
        }
    };

    const handleChange = (e) => {
        const { name, value, checked, type } = e.target;

        setFormData((prev) => ({
            ...prev,
            [name]: type === "checkbox" ? checked : value,
        }));
    };

    return (
        <div className={styles.form}>
            <form onSubmit={handleSubmit}>
                <div className={styles.field}>
                    <label className={styles.label} htmlFor="loginEmail">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        id="loginEmail"
                        className={`${styles.input} ${errors.email ? styles.inputInvalid : ""}`}
                        placeholder="Wprowadź email"
                        onChange={handleChange}
                        value={formData.email}
                        autoComplete="username"
                    />

                    {errors.email && (
                        <div className={styles.error}>{errors.email}</div>
                    )}
                </div>

                <div className={styles.field}>
                    <label className={styles.label} htmlFor="loginPassword">
                        Hasło
                    </label>

                    <div className={styles.passwordWrapper}>
                        <input
                            type={showPassword ? "text" : "password"}
                            name="password"
                            id="loginPassword"
                            className={`${styles.input} ${errors.password ? styles.inputInvalid : ""}`}
                            placeholder="Wprowadź hasło"
                            onChange={handleChange}
                            value={formData.password}
                            autoComplete="current-password"
                        />

                        <FontAwesomeIcon
                            icon={showPassword ? faEyeSlash : faEye}
                            onClick={() => setShowPassword((prev) => !prev)}
                            className={styles.icon}
                        />
                    </div>

                    {errors.password && (
                        <div className={styles.error}>{errors.password}</div>
                    )}
                </div>

                <div className={styles.checkboxRow}>
                    <label className={styles.checkboxLabel}>
                        <input
                            type="checkbox"
                            name="rememberMe"
                            checked={formData.rememberMe}
                            onChange={handleChange}
                        />

                        Zapamiętaj mnie
                    </label>
                </div>

                <div className={styles.formRow}>
                    <button type="button" className={styles.forgotLink}>
                        Nie pamiętasz hasła?
                    </button>
                </div>

                {errors.general && (
                    <div className={styles.error}>{errors.general}</div>
                )}

                <button className={styles.submitButton} type="submit">
                    Zaloguj się
                </button>

                <p className={styles.footerText}>
                    Kontynuując, zgadzasz się z naszym Regulaminem i Polityką prywatności
                </p>
            </form>
        </div>
    );
};

export default LoginForm;