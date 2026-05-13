import { api } from "../../../api/axios.js";

function getRandomPreviewUrl(images) {
    if (!Array.isArray(images) || images.length === 0) {
        return null;
    }

    const randomIndex = Math.floor(Math.random() * images.length);
    return images[randomIndex]?.name || null;
}

export async function fetchGalleryPreview(gallery, userId) {
    try {
        const response = await api.get(`/gallery/${gallery.id}/${userId}`);
        const images = response.data?.images;

        return {
            ...gallery,
            imagesCount: Array.isArray(images) ? images.length : gallery.imagesCount,
            previewUrl: getRandomPreviewUrl(images),
        };
    } catch (error) {
        console.error("Błąd pobierania preview dla galerii", gallery.id, error);

        return {
            ...gallery,
            previewUrl: null,
        };
    }
}