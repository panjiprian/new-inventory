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
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sure",
            cancelButtonText: "Cancel",
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`${endpoint}/${btnDelete.dataset.id}`, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": csrf,
                    },
                })
                    .then((response) => {
                        return response.json();
                    })
                    .then((result) => {
                        if (result.message) {
                            location.reload();
                        }
                    });
            }
        });
    });
};

window.onload = toast();

// delete data with ajax
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
