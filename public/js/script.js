document.addEventListener('DOMContentLoaded', function () {
    // Abrir Modal
    const editButtons = document.querySelectorAll('.edit-btn');
    const editModal = document.getElementById('editModal');
    const closeModal = document.querySelector('.close-modal');

    editButtons.forEach(button => {
        button.addEventListener('click', function () {
            const userId = this.getAttribute('data-id');
            const userName = this.getAttribute('data-name');
            const userEmail = this.getAttribute('data-email');

            document.getElementById('editId').value = userId;
            document.getElementById('editName').value = userName;
            document.getElementById('editEmail').value = userEmail;

            editModal.style.display = 'block';
        });
    });

    // Fechar Modal
    closeModal.addEventListener('click', function () {
        editModal.style.display = 'none';
    });

    window.addEventListener('click', function (event) {
        if (event.target === editModal) {
            editModal.style.display = 'none';
        }
    });

    // Validação do Formulário de Criação
    document.getElementById('createForm')?.addEventListener('submit', function (e) {
        const name = document.getElementById('createName').value.trim();
        const email = document.getElementById('createEmail').value.trim();

        if (!name || !email) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Atenção',
                text: 'Por favor, preencha todos os campos!',
                confirmButtonColor: '#3a5ae8'
            });
        }
    });

    //Modal delete
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();

            Swal.fire({
                title: 'Tem certeza?',
                text: "Você não poderá reverter esta ação!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3a5ae8',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, excluir!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = e.target.closest('form');
                    const tempInput = document.createElement('input');
                    tempInput.type = 'hidden';
                    tempInput.name = 'delete';
                    tempInput.value = '1';
                    form.appendChild(tempInput);

                    form.submit();
                }
            });
        });
    });
});