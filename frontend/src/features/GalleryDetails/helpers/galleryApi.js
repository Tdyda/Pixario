import {api} from "../../../api/axios.js";

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL;

export async function fetchJson(path, options = {}) {
    try {
        const response = await api.get(path, options);

        return response.data;
    } catch (error) {
        const err = new Error(
            error.response?.data?.error?.message ||
            `HTTP error ${error.response?.status}`
        );

        err.status = error.response?.status;

        throw err;
    }
}

export async function uploadGalleryImages(galleryId, files) {
    try {
        const formData = new FormData();

        files.forEach((file) => {
            formData.append("images[]", file);
        });

        formData.append("galleryId", galleryId);

        const response = await api.post("/upload/images", formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        });

        return response.data;
    } catch (error) {
        const err = new Error(
            error.response?.data?.error?.message ||
            `Upload error ${error.response?.status}`
        );

        err.status = error.response?.status;
        err.data = error.response?.data;

        throw err;
    }
}