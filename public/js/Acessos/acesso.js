document.addEventListener("DOMContentLoaded", function () {
    const openModalConfirmButtons = document.querySelectorAll(".open-modal-confirm");
    const closeModalConfirmButtons = document.querySelectorAll(".close-modal-confirm");
    const openModalHistButtons = document.querySelectorAll(".open-modal-hist");
    const closeModalHistButtons = document.querySelectorAll(".close-modal-hist");
    const openModalAcessoButtons = document.querySelectorAll(".open-modal-acesso");
    const closeModalAcessoButtons = document.querySelectorAll(".close-modal-acesso");
    var fadeElement = document.getElementById('fade');




    const openModal = (targetModal) => {
        const modal = document.querySelector(targetModal);
        if (modal.classList.contains('hide')) {
            modal.classList.remove('hide');
            fadeElement.classList.remove('hide');
        }
    };
    const openModalAcesso = (targetModal) => {
        const modal = document.querySelector(targetModal);
        closeModal();
        if (modal.classList.contains('hide')) {
            modal.classList.remove('hide');
        }
    };

    const closeModal = () => {
        let modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            modal.classList.add('hide');
        });
    };
    const closeModalAcesso = () => {
        let modals = document.querySelectorAll('.modal-acesso');
        modals.forEach(modal => {

            modal.classList.add('hide');
        });
    };


    fadeElement.addEventListener('click', () => {
        // Adiciona a classe 'hide' a cada modal
        closeModal();
        closeModalAcesso;
        fadeElement.classList.add('hide');
    });

    openModalConfirmButtons.forEach((button) => {
        button.addEventListener("click", (event) => {
            event.preventDefault();
            const targetModalId = event.currentTarget.getAttribute("data-target");
            openModal(targetModalId);
        });
    });

    openModalAcessoButtons.forEach((button) => {
        button.addEventListener("click", (event) => {
            event.preventDefault();

            const targetModalId = event.currentTarget.getAttribute("data-target");
            openModalAcesso(targetModalId);
        });
    });

    openModalHistButtons.forEach((button) => {
        button.addEventListener("click", (event) => {
            event.preventDefault();
            const targetModalId = event.currentTarget.getAttribute("data-target");
            openModal(targetModalId);
        });
    });

    closeModalConfirmButtons.forEach((button) => {
        button.addEventListener("click", () => {
            closeModal();
            fadeElement.classList.add('hide');
        });
    });

    closeModalConfirmButtons.forEach((button) => {
        button.addEventListener("click", () => {
            closeModal();
            fadeElement.classList.add('hide');
        });
    });

    /*closeModalHistButtons.forEach((button) => {
        button.addEventListener("click", () => {
            closeModal();
            fadeElement.classList.add('hide');

        });
    });*/


    $('#form-filtrar-hist').submit(function(e) {
        e.preventDefault();
    
        var form = $(this);
        var acessoId = form.data('acesso-id');
        var dataInicial = form.find('input[name="data_inicial"]').val();
        var dataFinal = form.find('input[name="data_final"]').val();
    
        $.ajax({
          url: '/acessos/' + acessoId + '/historico',
          method: 'GET',
          data: {
            data_inicial: dataInicial,
            data_final: dataFinal
          },
          success: function(response) {
            // Update the modal content with the filtered data
            $('#modal-hist-{{ acessoId }}').find('.modal-body').html(response);
          }
        });
      });

});
