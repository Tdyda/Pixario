import React, {useCallback, useEffect, useMemo, useState} from "react";
import {useNavigate} from "react-router-dom";

import {api} from "../../api/axios.js";
import {getAuth} from "../../auth/GetAuth.js";

import GalleryCard from "../../features/Gallery/components/GalleryCard.jsx";
import CreateGalleryModal from "../../features/Gallery/components/CreateGalleryModal.jsx";
import GalleryEmptyState from "../../features/Gallery/components/GalleryEmptyState.jsx";
import GalleryStateCard from "../../features/Gallery/components/GalleryStateCard.jsx";
import GalleryHero from "../../features/Gallery/components/GalleryHero.jsx";
import EditGalleryModal from "../../features/Gallery/components/EditGalleryModal.jsx";

import {fetchGalleryPreview} from "../../features/Gallery/helpers/galleryPreview.js";
import styles from "./GalleryPage.module.css";
import Navbar from "../../features/Navbar/Navbar.jsx";
import DeleteGalleryModal from "../../features/Gallery/components/DeleteGalleryModal.jsx";

const INITIAL_CREATE_FORM = {
    name: "",
    emailAddress: "",
    password: "",
};

function GalleryPage() {
    const navigate = useNavigate();

    const [galleries, setGalleries] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    const [isCreateModalOpen, setIsCreateModalOpen] = useState(false);
    const [createForm, setCreateForm] = useState(INITIAL_CREATE_FORM);
    const [createError, setCreateError] = useState("");
    const [creating, setCreating] = useState(false);

    const [galleryToDelete, setGalleryToDelete] = useState(null);
    const [deleting, setDeleting] = useState(false);
    const [deleteError, setDeleteError] = useState("");

    const [galleryToEdit, setGalleryToEdit] = useState(null);

    const userEmail = useMemo(() => getAuth()?.user?.email || "", []);

    const fetchGalleries = useCallback(async () => {
        const auth = getAuth();
        const userId = auth?.user?.id;
        const emailAddress = auth?.user?.email;

        if (!userId || !emailAddress) {
            setError("Brak danych użytkownika w localStorage (app_auth).");
            setLoading(false);
            return;
        }

        setLoading(true);
        setError(null);

        try {
            const response = await api.get(`/gallery/${userId}`);
            const galleriesList = Array.isArray(response.data) ? response.data : [];

            const galleriesWithPreviews = await Promise.all(
                galleriesList.map((gallery) => fetchGalleryPreview(gallery, userId))
            );

            setGalleries(galleriesWithPreviews);
        } catch (error) {
            console.error("Nie udało się pobrać galerii:", error);
            setError("Nie udało się pobrać galerii.");
        } finally {
            setLoading(false);
        }
    }, []);

    useEffect(() => {
        fetchGalleries();
    }, [fetchGalleries]);

    const openCreateModal = () => {
        const auth = getAuth();

        setCreateForm({
            ...INITIAL_CREATE_FORM,
            emailAddress: auth?.user?.email || "",
        });

        setCreateError("");
        setIsCreateModalOpen(true);
    };

    const closeCreateModal = () => {
        if (creating) return;

        setIsCreateModalOpen(false);
        setCreateError("");
    };

    const handleCreateInputChange = (event) => {
        const {name, value} = event.target;

        setCreateForm((prev) => ({
            ...prev,
            [name]: value,
        }));
    };

    const handleCreateGallery = async () => {
        const auth = getAuth();
        const ownerId = auth?.user?.id;

        const {name, emailAddress, password} = createForm;

        if (!name.trim() || !emailAddress.trim() || !password.trim()) {
            setCreateError("Wszystkie pola są wymagane.");
            return;
        }

        if (!ownerId) {
            setCreateError("Brak ID użytkownika.");
            return;
        }

        setCreating(true);
        setCreateError("");

        try {
            await api.post("/gallery", {
                name: name.trim(),
                emailAddress: emailAddress.trim(),
                password,
                ownerId,
            });

            setIsCreateModalOpen(false);
            setCreateForm(INITIAL_CREATE_FORM);

            await fetchGalleries();
        } catch (error) {
            console.error("Nie udało się utworzyć galerii:", error);
            setCreateError("Nie udało się utworzyć galerii. Spróbuj ponownie.");
        } finally {
            setCreating(false);
        }
    };

    const handleOpenDeleteModal = (gallery) => {
        setGalleryToDelete(gallery);
        setDeleteError("");
    };

    const handleCloseDeleteModal = () => {
        if (deleting) return;

        setGalleryToDelete(null);
        setDeleteError("");
    };

    const handleDeleteGallery = async () => {
        if (!galleryToDelete?.id) {
            setDeleteError("Brak ID galerii.");
            return;
        }

        setDeleting(true);
        setDeleteError("");

        try {
            await api.delete("/gallery", {
                data: {
                    galleryId: galleryToDelete.id,
                },
            });

            setGalleries((prev) =>
                prev.filter((gallery) => gallery.id !== galleryToDelete.id)
            );

            setGalleryToDelete(null);
        } catch (error) {
            console.error("Nie udało się usunąć galerii:", error);
            setDeleteError("Nie udało się usunąć galerii. Spróbuj ponownie.");
        } finally {
            setDeleting(false);
        }
    };

    const handleEditGallery = (gallery) => {
        setGalleryToEdit(gallery);
    };

    const handleCloseEditModal = () => {
        setGalleryToEdit(null);
    };

    const handleImagesDeleted = async () => {
        await fetchGalleries();
    };

    return (
        <div className={styles.shell}>
            <main className={styles.main}>
                <Navbar />

                <GalleryHero onCreateGallery={openCreateModal}/>

                <section className={styles.sectionHeader}>
                    <div>
                        <h2>Moje galerie</h2>
                        <p>{userEmail || "Zalogowany użytkownik"}</p>
                    </div>

                    <div className={styles.stats}>
                        <span>{galleries.length}</span>
                        <small>galerii</small>
                    </div>
                </section>

                {loading && (
                    <GalleryStateCard
                        type="loading"
                        title="Ładujemy galerie"
                        description="Sprawdzamy Twoje prywatne przestrzenie zdjęć."
                    />
                )}

                {error && !loading && (
                    <GalleryStateCard
                        type="error"
                        title="Nie udało się pobrać galerii"
                        description={error}
                        actionLabel="Spróbuj ponownie"
                        onAction={fetchGalleries}
                    />
                )}

                {!loading && !error && galleries.length === 0 && (
                    <GalleryEmptyState onCreateGallery={openCreateModal}/>
                )}

                {!loading && !error && galleries.length > 0 && (
                    <div className="row g-4">
                        {galleries.map((gallery) => (
                            <GalleryCard
                                key={gallery.id}
                                gallery={gallery}
                                onClick={() => navigate(`/gallery/${gallery.id}`)}
                                onDelete={handleOpenDeleteModal}
                                onEdit={handleEditGallery}
                            />
                        ))}
                    </div>
                )}

                {isCreateModalOpen && (
                    <CreateGalleryModal
                        form={createForm}
                        error={createError}
                        creating={creating}
                        onChange={handleCreateInputChange}
                        onClose={closeCreateModal}
                        onSubmit={handleCreateGallery}
                    />
                )}

                {galleryToDelete && (
                    <DeleteGalleryModal
                        gallery={galleryToDelete}
                        deleting={deleting}
                        error={deleteError}
                        onClose={handleCloseDeleteModal}
                        onConfirm={handleDeleteGallery}
                    />
                )}

                {galleryToEdit && (
                    <EditGalleryModal
                        gallery={galleryToEdit}
                        onClose={handleCloseEditModal}
                        onImagesDeleted={handleImagesDeleted}
                    />
                )}
            </main>
        </div>
    );
}

export default GalleryPage;