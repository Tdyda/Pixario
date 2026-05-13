import { Link, useNavigate } from "react-router-dom";
import { api } from "../../api/axios";

const DashboardNavigation = () => {
    const navigate = useNavigate();

    const handleLogout = async () => {
        try {
            await api.post("/auth/logout");

            localStorage.removeItem("app_auth");

            navigate("/login");
        } catch (error) {
            console.error("Błąd podczas wylogowania:", error);
        }
    };

    return (
        <nav className="col-md-2 sidebar bg-light d-flex flex-column p-4" style={{ minHeight: "100vh" }}>
            <h4>Dashboard</h4>

            <ul className="list-unstyled mt-4 flex-grow-1">
                <li className="mb-3">
                    <i className="bi bi-calendar-event me-2"></i>
                    Calendar
                </li>

                <li className="mb-3">
                    <i className="bi bi-people me-2"></i>
                    Clients
                </li>

                <li className="mb-3">
                    <Link
                        to="/gallery"
                        className="text-decoration-none text-reset"
                    >
                        <i className="bi bi-images me-2"></i>
                        Gallery
                    </Link>
                </li>
            </ul>

            <button
                className="btn btn-outline-danger w-100 mt-auto"
                onClick={handleLogout}
            >
                <i className="bi bi-box-arrow-right me-2"></i>
                Logout
            </button>
        </nav>
    );
};

export default DashboardNavigation;
