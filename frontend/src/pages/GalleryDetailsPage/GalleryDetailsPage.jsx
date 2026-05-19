import React, {useCallback, useEffect, useState} from "react";
import {Link, useParams} from "react-router-dom";

import {getAuth} from "../../auth/GetAuth.js";

import CredentialsForm from "../../features/GalleryDetails/components/CredentialsForm/CredentialsForm.jsx";
import GalleryPhotoGrid from "../../features/GalleryDetails/components/GalleryPhotoGrid/GalleryPhotoGrid.jsx";
import UploadImagesModal from "../../features/GalleryDetails/components/UploadImagesModal/UploadImagesModal.jsx";
import LoadingSpinner from "../../features/GalleryDetails/components/LoadingSpinner.jsx";

import {fetchJson, uploadGalleryImages} from "../../features/GalleryDetails/helpers/galleryApi.js";
import {getImageNames, isAllowedFile} from "../../features/GalleryDetails/helpers/galleryFiles.js";

import styles from "./GalleryDetailsPage.module.css";
import Navbar from "../../features/Navbar/Navbar.jsx";

import {api} from "../../api/axios.js";
import UploadSuccessModal from "../../features/GalleryDetails/components/UploadSuccessModal/UploadSuccessModal.jsx";

const INITIAL_CREDENTIALS = {
    emailAddress: "",
    password: "",
};

const GalleryDetailsPage = () => {
    const {dir} = useParams();

    const [galleryName, setGalleryName] = useState("");
    const [photoNames, setPhotoNames] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    const [isUploadModalOpen, setIsUploadModalOpen] = useState(false);
    const [selectedFiles, setSelectedFiles] = useState([]);
    const [fileError, setFileError] = useState("");
    const [uploading, setUploading] = useState(false);
    const [uploadSuccessOpen, setUploadSuccessOpen] = useState(false);

    const [authRequired, setAuthRequired] = useState(false);
    const [credentials, setCredentials] = useState(INITIAL_CREDENTIALS);
    const [credentialsError, setCredentialsError] = useState("");
    const [credentialsLoading, setCredentialsLoading] = useState(false);

    const auth = getAuth();
    const canUpload = !!auth?.user?.id && !authRequired;

    const updateGalleryData = useCallback((data) => {
        setGalleryName(data?.name || "");
        setPhotoNames(getImageNames(data));
    }, []);

    const fetchGalleryWithCredentials = useCallback(async () => {
        const response = await api.post("/gallery/get", {
            id: dir,
            emailAddress: credentials.emailAddress,
            password: credentials.password,
        });

        return response.data;
    }, [dir, credentials.emailAddress, credentials.password]);

    const fetchGalleryAsOwner = useCallback(async () => {
        setLoading(true);
        setError(null);
        setAuthRequired(false);

        if (!dir) {
            setError("Brak identyfikatora galerii.");
            setLoading(false);
            return;
        }

        const userId = getAuth()?.user?.id;

        if (!userId) {
            setAuthRequired(true);
            setLoading(false);
            return;
        }

        try {
            const data = await fetchJson(`/gallery/${dir}/${userId}`);
            updateGalleryData(data);
        } catch (error) {
            if (error.response?.status === 401 || error.status === 401) {
                setAuthRequired(true);
                setError(null);
            } else {
                console.error("Nie udało się pobrać galerii:", error);
                setError("Nie udało się pobrać szczegółów galerii.");
            }
        } finally {
            setLoading(false);
        }
    }, [dir, updateGalleryData]);

    useEffect(() => {
        fetchGalleryAsOwner();
    }, [fetchGalleryAsOwner]);

    const handleCredentialsChange = (event) => {
        const {name, value} = event.target;

        setCredentials((prev) => ({
            ...prev,
            [name]: value,
        }));
    };

    const handleCredentialsSubmit = async (event) => {
        event.preventDefault();

        const {emailAddress, password} = credentials;

        if (!emailAddress.trim() || !password.trim()) {
            setCredentialsError("Podaj adres e-mail i hasło.");
            return;
        }

        setCredentialsLoading(true);
        setCredentialsError("");
        setError(null);

        try {
            const data = await fetchGalleryWithCredentials();

            updateGalleryData(data);
            setAuthRequired(false);
        } catch (error) {
            console.error("Błąd przy /gallery/get:", error);

            if (error.status === 401) {
                setCredentialsError("Nieprawidłowe dane dostępowe do galerii.");
            } else {
                setCredentialsError("Wystąpił błąd. Spróbuj ponownie.");
            }
        } finally {
            setCredentialsLoading(false);
        }
    };

    const handleDownloadGallery = async () => {
        try {
            const response = await fetch(
                `${import.meta.env.VITE_API_BASE_URL}/gallery/${dir}/download`,
                {
                    method: "GET",
                    credentials: "include",
                }
            );

            if (!response.ok) {
                throw new Error("Nie udało się pobrać galerii.");
            }

            const blob = await response.blob();
            const url = URL.createObjectURL(blob);

            const link = document.createElement("a");
            link.href = url;
            link.download = `${galleryName || "gallery"}.zip`;

            document.body.appendChild(link);
            link.click();

            link.remove();
            URL.revokeObjectURL(url);
        } catch (error) {
            console.error(error);
            setError("Nie udało się pobrać galerii.");
        }
    };

    const openUploadModal = () => {
        setSelectedFiles([]);
        setFileError("");
        setIsUploadModalOpen(true);
    };

    const closeUploadModal = () => {
        if (uploading) return;

        setIsUploadModalOpen(false);
        setSelectedFiles([]);
        setFileError("");
    };

    const handleFileChange = (event) => {
        const files = Array.from(event.target.files || []);

        if (files.length === 0) {
            setSelectedFiles([]);
            setFileError("");
            return;
        }

        const validFiles = files.filter(isAllowedFile);
        const rejectedFiles = files.filter((file) => !isAllowedFile(file));

        setSelectedFiles(validFiles);

        setFileError(
            rejectedFiles.length > 0
                ? `Odrzucono niedozwolone pliki: ${rejectedFiles
                    .map((file) => file.name)
                    .join(", ")}`
                : ""
        );
    };

    const refreshGalleryAfterUpload = async () => {
        const userId = getAuth()?.user?.id;

        if (userId) {
            const response = await api.get(`/gallery/${dir}/${userId}`);
            updateGalleryData(response.data);
            return;
        }

        if (credentials.emailAddress && credentials.password) {
            const data = await fetchGalleryWithCredentials();
            updateGalleryData(data);
        }
    };

    const handleUpload = async () => {
        if (selectedFiles.length === 0) {
            setFileError("Wybierz przynajmniej jeden plik.");
            return;
        }

        if (!dir) {
            setFileError("Brak identyfikatora galerii.");
            return;
        }

        setUploading(true);
        setFileError("");

        try {
            await uploadGalleryImages(dir, selectedFiles);
            await refreshGalleryAfterUpload();

            closeUploadModal();
            setUploadSuccessOpen(true);
        } catch (error) {
            console.error("Upload error:", error);
            setFileError("Nie udało się wysłać zdjęć. Spróbuj ponownie.");
        } finally {
            setUploading(false);
        }
    };

    return (
        <div className={styles.shell}>

            <main className={styles.main}>
                {auth && (<Navbar/>)}
                {loading && <LoadingSpinner/>}

                {error && !loading && (
                    <div className={styles.stateCard} role="alert">
                        <i className="bi bi-exclamation-octagon"/>
                        <h3>Nie udało się pobrać galerii</h3>
                        <p>{error}</p>
                    </div>
                )}

                {!loading && !error && authRequired && !galleryName && (
                    <CredentialsForm
                        credentials={credentials}
                        error={credentialsError}
                        loading={credentialsLoading}
                        onChange={handleCredentialsChange}
                        onSubmit={handleCredentialsSubmit}
                    />
                )}

                {!loading && !error && !authRequired && (
                    <>
                        <header className={styles.topbar}>
                            <Link to="/gallery" className={styles.backLink}>
                                <i className="bi bi-arrow-left"/>
                                Wróć do galerii
                            </Link>

                            <div className={styles.galleryActions}>
                                {canUpload && (
                                    <button
                                        type="button"
                                        className={styles.primaryButton}
                                        onClick={openUploadModal}
                                    >
                                        <i className="bi bi-plus-lg"/>
                                        Dodaj zdjęcia
                                    </button>
                                )}

                                <button
                                    type="button"
                                    className={styles.secondaryButton}
                                    onClick={handleDownloadGallery}
                                    disabled={photoNames.length === 0}
                                >
                                    <i className="bi bi-download"/>
                                    Pobierz galerię
                                </button>
                            </div>

                        </header>

                        <section className={styles.hero}>
                            <div>
                                <span className={styles.eyebrow}>Prywatna galeria</span>

                                <h1>{galleryName || "Gallery"}</h1>

                                <p>
                                    Przeglądaj zdjęcia w galerii, otwieraj je w trybie pełnoekranowym
                                    i dodawaj nowe pliki do kolekcji.
                                </p>
                            </div>

                            <div className={styles.heroStats}>
                                <span>{photoNames.length}</span>
                                <small>zdjęć</small>
                            </div>
                        </section>

                        <section className={styles.sectionHeader}>
                            <div>
                                <h2>Zdjęcia w galerii</h2>
                                <p>ID galerii: {dir}</p>
                            </div>
                        </section>

                        <GalleryPhotoGrid photoNames={photoNames}/>
                    </>
                )}

                {isUploadModalOpen && (
                    <UploadImagesModal
                        uploading={uploading}
                        fileError={fileError}
                        selectedFiles={selectedFiles}
                        onFileChange={handleFileChange}
                        onClose={closeUploadModal}
                        onUpload={handleUpload}
                    />
                )}

                {uploadSuccessOpen && (
                    <UploadSuccessModal
                        onClose={() => setUploadSuccessOpen(false)}
                    />
                )}
            </main>
        </div>
    );
};

export default GalleryDetailsPage;