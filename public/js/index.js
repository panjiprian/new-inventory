const csrf = document.head.querySelector("meta[name=csrf-token]").content;
const toast = () => {
    setTimeout(() => {
        const toastContainer = document.querySelector("#toast-container");
        if (toastContainer !== null) {
            toastContainer.classList.remove("hidden");
            toastContainer.classList.add("flex");
            setTimeout(() => {
                toastContainer.classList.remove("flex");
                toastContainer.classList.add("hidden");
                sessionStorage.removeItem("deleted");
            }, 1500);
        }
    }, 100);
};
const deleteModal = (btnDelete, endpoint, message) => {
    btnDelete.addEventListener("click", () => {
        Swal.fire({
            title: "Are you sure?",
            text: message,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "Cancel",
        }).then((result) => {
            if (result.isConfirmed) {
                // Menampilkan loading saat proses hapus berjalan
                Swal.fire({
                    title: "Deleting...",
                    text: "Please wait a moment",
                    icon: "info",
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch(`${endpoint}/${btnDelete.dataset.id}`, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": csrf,
                    },
                })
                    .then((response) => response.json())
                    .then((result) => {
                        if (result.message) {
                            Swal.fire({
                                title: "Deleted!",
                                text: "Your data has been deleted successfully.",
                                icon: "success",
                                timer: 5000,
                                showConfirmButton: false
                            });

                            // Reload halaman setelah delay agar smooth
                            setTimeout(() => {
                                location.reload();
                            }, 2000);
                        } else {
                            Swal.fire("Error", "Failed to delete data!", "error");
                        }
                    })
                    .catch((error) => {
                        console.error(error);
                        Swal.fire("Error", "Something went wrong!", "error");
                    });
            }
        });
    });
};

window.onload = toast();

// Delete action for various elements
document.querySelectorAll(".btn-delete-category").forEach((btnDelete) => {
    deleteModal(
        btnDelete,
        "/hapus-kategori",
        "Data that has been deleted cannot be restored"
    );
});

document.querySelectorAll(".btn-delete-product").forEach((btnDelete) => {
    deleteModal(
        btnDelete,
        "/hapus-barang",
        "Data that has been deleted cannot be restored"
    );
});

document.querySelectorAll(".btn-delete-supplier").forEach((btnDelete) => {
    deleteModal(
        btnDelete,
        "/hapus-supplier",
        "Data that has been deleted cannot be restored"
    );
});

document.querySelectorAll(".btn-delete-officer").forEach((btnDelete) => {
    deleteModal(
        btnDelete,
        "/hapus-petugas",
        "Data that has been deleted cannot be restored"
    );
});

document.querySelectorAll(".btn-delete-admin").forEach((btnDelete) => {
    deleteModal(
        btnDelete,
        "/hapus-admin",
        "Data that has been deleted cannot be restored"
    );
});

document.querySelectorAll(".btn-delete-product-income").forEach((btnDelete) => {
    deleteModal(
        btnDelete,
        "/hapus-barang-masuk",
        "Deleting incoming item data will also delete the amount of item data that was previously added"
    );
});

document.querySelectorAll(".btn-delete-product-outcome").forEach((btnDelete) => {
    deleteModal(
        btnDelete,
        "/hapus-barang-keluar",
        "Deleting outgoing goods data will also affect the amount of related goods data"
    );
});

document.addEventListener("DOMContentLoaded", function () {
    const btnSave = document.getElementById("btn-save");

    if (btnSave) {
        btnSave.addEventListener("click", function (event) {
            event.preventDefault();

            Swal.fire({
                title: "Saving...",
                text: "Please wait while the data is being saved.",
                icon: "info",
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });

            const form = btnSave.closest("form");
            const formData = new FormData(form);
            const actionUrl = form.getAttribute("action");

            fetch(actionUrl, {
                method: "POST",
                body: formData,
                headers: {
                    "X-CSRF-TOKEN": document.querySelector("meta[name='csrf-token']").content
                }
            })
            .then(response => {
                const contentType = response.headers.get("content-type");
                if (contentType && contentType.includes("application/json")) {
                    return response.json();
                } else {
                    return response.text().then(html => { throw new Error("Server returned HTML instead of JSON"); });
                }
            })
            .then(result => {
                if (result.success) {
                    Swal.fire({
                        title: "Success!",
                        text: result.message || "Data has been saved successfully.",
                        icon: "success",
                        timer: 5000,
                        showConfirmButton: false
                    });

                    setTimeout(() => {
                        window.location.href = result.redirect || "/kategori";
                    }, 5000);
                } else {
                    throw new Error(result.message || "Failed to save data.");
                }
            })
            .catch(error => {
                console.error(error);
                Swal.fire({
                    title: "Error!",
                    text: "Failed to save data. Please check your input.",
                    icon: "error",
                });
            });
        });
    }
});
