export const ALLOWED_EXTENSIONS = ["jpg", "jpeg", "png"];
export const ACCEPTED_FILE_TYPES = ".jpg,.jpeg,.png,image/jpeg,image/png";

export function getImageNames(data) {
    const images = Array.isArray(data?.images) ? data.images : [];
    return images.map((image) => image.name);
}

export function isAllowedFile(file) {
    const extension = file.name.toLowerCase().split(".").pop();
    return ALLOWED_EXTENSIONS.includes(extension);
}