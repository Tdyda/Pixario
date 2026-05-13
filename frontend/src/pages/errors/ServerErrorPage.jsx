import ErrorPage from "../../features/errors/ErrorPage.jsx";

function ServerErrorPage() {
    return <ErrorPage code={500} />;
}

export default ServerErrorPage;