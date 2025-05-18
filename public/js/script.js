document.addEventListener('DOMContentLoaded', function (){
    // Abrir Modal // 
    const editButtons = document.querySelectorAll('.edit-btn');
    const editModal = document.getElementById('editModal');
    const closeModal = document.querySelector('.close-modal');

    editButtons.forEach(button =>{
        button.addEventListener('click', function(){
            const userId = this.getAttribute('data-id')
            const userName = this.getAttribute('data-name')
            const userEmail = this.getAttribute('data-email')

            document.getElementById('editId').value = userId
            document.getElementById('editName').value = userName
            document.getElementById('editEmail').value = userEmail

            editModal.style.display = 'block'
        });
    });

        // Fecha Modal // 

    closeModal.addEventListener('click', function(){
        editModal.style.display = 'none'
    });
    window.addEventListener('click', function(event){
        if(event.target === editModal){
            editModal.style.display = 'none'
        }
    })

        // Validações // 

    const createForm = document.getElementById('createForm');
    createForm.addEventListener('submit', function(event){
        const name = document.getElementById('createName').value.trim();
        const email = document.getElementById('createEmail').value.trim();
        if(!name || !email){
            alert("Por favor, preencha todos os campos")
            event.preventDefault();
        }
    })

    const deletebuttons = document.querySelectorAll('.delete-btn')
    deletebuttons.forEach(button =>{
        button.addEventListener('click', function(event){
            if(!confirm('Tem certeza que deseja excluir o Usuário?')){
                event.preventDefault();
            }
        })
    })
})

