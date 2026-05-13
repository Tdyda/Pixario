export function formatGalleryDate(dateString) {
    if (!dateString) return "Brak daty";

    const date = new Date(dateString.replace(" ", "T"));

    if (Number.isNaN(date.getTime())) {
        return "Brak daty";
    }

    return new Intl.DateTimeFormat("pl-PL", {
        day: "2-digit",
        month: "short",
        year: "numeric",
    }).format(date);
}