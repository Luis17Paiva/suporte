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

            const acessoId = targetModalId.split('-')[2];
            registrarAcesso(acessoId);
        });
    });

    // Função para fazer a requisição POST para registrar o acesso
    const registrarAcesso = (acessoId) => {
        axios.post(`/suporte/public/registrar-acesso/${acessoId}`)
            .then(response => {
                console.log(response.data.message);
            })
            .catch(error => {
                console.error(error);
            });
    };

    openModalHistButtons.forEach((button) => {
        button.addEventListener("click", (event) => {
            event.preventDefault();
            const targetModalId = event.currentTarget.getAttribute("data-target");
            openModal(targetModalId);
        });
    });

    closeModalAcessoButtons.forEach((button) => {
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

    closeModalHistButtons.forEach((button) => {
        button.addEventListener("click", () => {
            closeModal();
            fadeElement.classList.add('hide');

        });
    });

    $('.form-filtrar-hist').submit(function (event) {
        event.preventDefault(); // Evitar envio padrão do formulário

        const startDate = $(this).find('#data_inicio').val();
        const endDate = $(this).find('#data_fim').val();
        const acessoId = $(this).data('acesso-id');

        axios.get('/suporte/public/historico-acessos/' + acessoId, {
            params: {
                startDate: startDate,
                endDate: endDate
            }
        })
            .then(response => {
                const data = response.data;

                const dataDisplay = $(this).siblings('#dataDisplay');

                // Limpar exibição de dados existente
                dataDisplay.empty();

                // Verificar se 'data' é uma matriz não nula e não indefinida
                if (Array.isArray(data) && data !== null && data !== undefined) {
                    // Criar uma tabela para armazenar os registros
                    const table = $('<table>').addClass('table'); // Adicionei a classe 'table' do Bootstrap para estilização básica

                    // Cabeçalho da tabela
                    const tableHeader = $('<thead>').append($('<tr>').append($('<th>').text('Usuário'), $('<th>').text('Data de Acesso')));
                    table.append(tableHeader);

                    // Corpo da tabela
                    const tableBody = $('<tbody>');

                    // Iterar sobre cada registro no JSON
                    data.forEach(record => {
                        const tableRow = $('<tr>');

                        // Adicionar informações do registro às células da linha
                        tableRow.append($('<td>').text(record.usuario), $('<td>').text(record.data_acesso));

                        // Adicionar a linha ao corpo da tabela
                        tableBody.append(tableRow);
                    });

                    // Adicionar o corpo da tabela à tabela completa
                    table.append(tableBody);

                    // Adicionar a tabela completa à exibição de dados
                    dataDisplay.append(table);
                } else {
                    dataDisplay.append('<div class="text1">Nenhum registro encontrado</div>');
                }
            })
            .catch(error => {
                console.error(error);
            });
    });

});
