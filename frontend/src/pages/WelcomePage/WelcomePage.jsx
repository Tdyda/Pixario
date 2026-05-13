import { Link } from "react-router-dom";
import styles from "./WelcomePage.module.css";

function WelcomePage() {
    return (
        <main className={styles.page}>
            <section className={styles.hero}>
                <div className={styles.content}>
                    <Link to="/" className={styles.logo}>
                        <span>✣</span>
                        pixario
                    </Link>

                    <h1>AI-Powered Photo Perfection</h1>

                    <p>
                        Upload. Enhance. Share.
                        <br />
                        Beautiful portraits, effortlessly.
                    </p>

                    <Link to="/auth/register" className={styles.primaryButton}>
                        Zacznij teraz
                    </Link>

                    <p className={styles.loginText}>
                        Masz już konto?{" "}
                        <Link to="/auth/login">Zaloguj się</Link>
                    </p>
                </div>

                <div className={styles.photos}>
                    <img
                        className={`${styles.photo} ${styles.photoOne}`}
                        src="https://images.unsplash.com/photo-1494790108377-be9c29b29330"
                        alt="Portret kobiety"
                    />

                    <img
                        className={`${styles.photo} ${styles.photoTwo}`}
                        src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e"
                        alt="Portret mężczyzny"
                    />

                    <img
                        className={`${styles.photo} ${styles.photoThree}`}
                        src="https://images.unsplash.com/photo-1534528741775-53994a69daeb"
                        alt="Portret studyjny"
                    />
                </div>

                <div className={styles.starOne}>✦</div>
                <div className={styles.starTwo}>✦</div>
                <div className={styles.starThree}>✦</div>
                <div className={styles.starFour}>✦</div>

                <div className={styles.wave} />
            </section>
        </main>
    );
}

export default WelcomePage;