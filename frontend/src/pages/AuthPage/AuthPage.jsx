import AuthForm from "../../features/auth/AuthForm.jsx";
import styles from "./AuthPage.module.css";
import {useParams} from "react-router-dom";

const AuthPage = () => {
    const { tab } = useParams();

    return (
        <main className={styles.page}>
            <AuthForm selectedTab={tab === "register" ? "register" : "login"}/>
        </main>
    );
};

export default AuthPage;