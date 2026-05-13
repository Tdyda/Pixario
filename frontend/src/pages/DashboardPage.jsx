import Dashboard from '../features/Dashboard/Dashboard.jsx'
import DashboardNavigation from "../features/Dashboard/DashboardNavigation.jsx";
import DashboardMain from "../features/Dashboard/DashboardMain.jsx";

const DashboardPage = () => {

    return (
        <div className="container-fluid">
            <div className="row">
                <DashboardNavigation />
                <DashboardMain />
            </div>
        </div>
    );
}

export default DashboardPage;