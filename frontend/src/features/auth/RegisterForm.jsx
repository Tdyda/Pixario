import { useState } from "react";
import styles from "./AuthForm.module.css";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faEye, faEyeSlash } from "@fortawesome/free-solid-svg-icons";
import {
    validateEmail,
    validatePassword,
    validateRegister,
    validateRetypePassword,
} from "./validateRegister.js";
import { useAuth } from "../../auth/useAuth.js";

const RegisterForm = () => {
    const [formData, setFormData] = useState({
        email: "",
        password: "",
        retypedPassword: "",
    });

    const [errors, setErrors] = useState({});
    const [showPassword, setShowPassword] = useState({
        password: false,
        retypedPassword: false,
    });
    const [activationEmailSent, setActivationEmailSent] = useState(false);
    const [isChecked, setIsChecked] = useState(false);

    const { register } = useAuth();

    const validators = {
        email: () => validateEmail(formData.email),
        password: () => validatePassword(formData.password),
        retypedPassword: () =>
            validateRetypePassword(formData.password, formData.retypedPassword),
    };

    const handleSubmit = async (e) => {
        e.preventDefault();

        const validationErrors = validateRegister(formData);

        if (Object.values(validationErrors).some(Boolean)) {
            setErrors(validationErrors);
            return;
        }

        try {
            const response = await register({
                email: formData.email,
                plainPassword: formData.password,
                retypedPassword: formData.retypedPassword,
            });

            if (response.status >= 200 && response.status < 300) {
                setActivationEmailSent(true);
            }
        } catch (err) {
            setErrors({
                general: err.response?.data?.error || "Nie udało się utworzyć konta.",
            });
        }
    };

    const handleChange = (e) => {
        const { name, value } = e.currentTarget;

        setFormData((prev) => ({
            ...prev,
            [name]: value,
        }));
    };

    const handleErrors = (fieldName) => {
        const result = validators[fieldName]();

        setErrors((prev) => {
            const updated = { ...prev, ...result };

            Object.keys(result).forEach((key) => {
                if (!result[key]) {
                    delete updated[key];
                }
            });

            return updated;
        });
    };

    const togglePassword = (field) => {
        setShowPassword((prev) => ({
            ...prev,
            [field]: !prev[field],
        }));
    };

    const isSubmitDisabled =
        !!errors.email ||
        !!errors.password ||
        !!errors.retypedPassword ||
        !isChecked;

    if (activationEmailSent) {
        return (
            <div className={styles.successState}>
                <div className={styles.successIcon}>
                    <i className="bi bi-envelope-check" />
                </div>

                <h2>Sprawdź swoją skrzynkę</h2>

                <p>
                    Wysłaliśmy link aktywacyjny na adres:
                    <br />
                    <strong>{formData.email}</strong>
                </p>

                <div className={styles.successHint}>
                    <i className="bi bi-info-circle" />

                    <span>
                    Jeśli wiadomość nie dotarła w ciągu kilku minut,
                    sprawdź folder <strong>spam</strong> lub <strong>oferty</strong>.
                </span>
                </div>
            </div>
        );
    }

    return (
        <div className={styles.form}>
            <form onSubmit={handleSubmit}>
                <div className={styles.field}>
                    <label className={styles.label} htmlFor="registerEmail">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        id="registerEmail"
                        className={`${styles.input} ${errors.email ? styles.inputInvalid : ""}`}
                        placeholder="Wprowadź email"
                        onChange={handleChange}
                        onBlur={() => handleErrors("email")}
                        value={formData.email}
                        autoComplete="username"
                    />

                    {errors.email && (
                        <div className={styles.error}>{errors.email}</div>
                    )}
                </div>

                <PasswordField
                    id="registerPassword"
                    label="Hasło"
                    name="password"
                    value={formData.password}
                    error={errors.password}
                    visible={showPassword.password}
                    onChange={handleChange}
                    onBlur={() => handleErrors("password")}
                    onToggle={() => togglePassword("password")}
                />

                <PasswordField
                    id="registerRetypedPassword"
                    label="Powtórz hasło"
                    name="retypedPassword"
                    value={formData.retypedPassword}
                    error={errors.retypedPassword}
                    visible={showPassword.retypedPassword}
                    onChange={handleChange}
                    onBlur={() => handleErrors("retypedPassword")}
                    onToggle={() => togglePassword("retypedPassword")}
                />

                {errors.general && (
                    <div className={styles.error}>{errors.general}</div>
                )}

                <label className={styles.checkboxRow}>
                    <input
                        type="checkbox"
                        checked={isChecked}
                        onChange={(e) => setIsChecked(e.currentTarget.checked)}
                    />

                    <span>
                        Akceptuję{" "}
                        <a href="/regulamin" className={styles.termsLink}>
                            regulamin
                        </a>{" "}
                        i politykę prywatności
                    </span>
                </label>

                <button
                    className={styles.submitButton}
                    type="submit"
                    disabled={isSubmitDisabled}
                >
                    Utwórz konto
                </button>
            </form>
        </div>
    );
};

function PasswordField({
                           id,
                           label,
                           name,
                           value,
                           error,
                           visible,
                           onChange,
                           onBlur,
                           onToggle,
                       }) {
    return (
        <div className={styles.field}>
            <label className={styles.label} htmlFor={id}>
                {label}
            </label>

            <div className={styles.passwordWrapper}>
                <input
                    type={visible ? "text" : "password"}
                    name={name}
                    id={id}
                    className={`${styles.input} ${error ? styles.inputInvalid : ""}`}
                    placeholder="Wprowadź hasło"
                    onChange={onChange}
                    onBlur={onBlur}
                    value={value}
                    autoComplete="new-password"
                />

                <FontAwesomeIcon
                    icon={visible ? faEyeSlash : faEye}
                    onClick={onToggle}
                    className={styles.icon}
                />
            </div>

            {error && <div className={styles.error}>{error}</div>}
        </div>
    );
}

export default RegisterForm;