import {useState} from "react";
import styles from "./AuthForm.module.css";
import LoginForm from "./LoginForm.jsx";
import RegisterForm from "./RegisterForm.jsx";

const AuthForm = ({selectedTab}) => {
    const [activeTab, setActiveTab] = useState(selectedTab);

    return (
        <section className={styles.authCard}>
            <div className={styles.brandPanel}>
                <div className={styles.logo}>
                    <span className={styles.logoIcon}>✣</span>
                    pixario
                </div>

                <div className={styles.brandIcon}>
                    <i className="bi bi-stars"/>
                </div>

                <h2>AI-Powered<br/>Photo Perfection</h2>
            </div>

            <div className={styles.formPanel}>
                <div className={styles.tabs}>
                    <button
                        type="button"
                        className={activeTab === "login" ? styles.activeTab : ""}
                        onClick={() => setActiveTab("login")}
                    >
                        Zaloguj się
                    </button>

                    <button
                        type="button"
                        className={activeTab === "register" ? styles.activeTab : ""}
                        onClick={() => setActiveTab("register")}
                    >
                        Utwórz konto
                    </button>
                </div>

                {activeTab === "login" ? <LoginForm/> : <RegisterForm/>}
            </div>
        </section>
    );
};

export default AuthForm;