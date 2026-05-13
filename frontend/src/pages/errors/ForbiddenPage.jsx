import ErrorPage from "../../features/errors/ErrorPage.jsx";

function ForbiddenPage() {
    return <ErrorPage code={403} />;
}

export default ForbiddenPage;