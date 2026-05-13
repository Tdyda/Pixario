import ErrorPage from "../../features/errors/ErrorPage.jsx";

function UnauthorizedPage() {
    return <ErrorPage code={401} />;
}

export default UnauthorizedPage;