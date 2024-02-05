// Função para preencher a tabela em espera com os dados recebidos via JSON
function preencherTabelaEspera(dados) {
  var tabela = document.getElementById('tabela-espera');
  tabela.innerHTML = ''; // Limpa a tabela antes de preenchê-la novamente
  if (dados.fl !== null && dados.fl !== undefined) {
    for (var i = 0; i < dados.fl.length; i++) {
      var hora_chamada = dados.fl[i].hora_chamada;
      var tempo_desde_chamada = dados.fl[i].tempo_de_espera;
      var ura = dados.fl[i].ura;
      var linha = document.createElement('tr');
      var colunaHora = document.createElement('td');
      var colunaTempo = document.createElement('td');
      colunaHora.innerText = hora_chamada;
      colunaTempo.innerText = tempo_desde_chamada;
      linha.appendChild(colunaHora);
      linha.appendChild(colunaTempo);
      tabela.appendChild(linha);
    }
    if (tabela !== null && tabela.rows !== undefined) {
      for (var i = 0; i < tabela.rows.length; i++) {
        var tempoEspera = tabela.rows[i].cells[1].innerText;
        if (ura === 'ADM') {
          tabela.rows[i].cells[1].style.backgroundColor = "rgb(75, 179, 253)";

        } else {
          tabela.rows[i].cells[1].style.backgroundColor = (tempoEspera < "00:05:00") ? "rgb(66, 226, 184)" : (tempoEspera < "00:10:00") ? "rgb(255, 242, 117)" : "rgb(234, 82, 111)";
        }
      }
    }
  }
}

// Função para preencher a tabela atendendo com os dados recebidos via JSON
function preencherTabelaAtendendo(dados) {
  var tabela = document.getElementById('tabela-atendendo');
  tabela.innerHTML = ''; // Limpa a tabela antes de preenchê-la novamente
  if (dados.al !== null && dados.al !== undefined) {
    for (var i = 0; i < dados.al.length; i++) {
      var colaborador = dados.al[i].colaborador;
      var tempo_desde_atendimento = dados.al[i].tempo_de_atendimento;
      var linha = document.createElement('tr');
      var colunaColaborador = document.createElement('td');
      var colunaTempo = document.createElement('td');
      colunaColaborador.innerText = colaborador;
      colunaTempo.innerText = tempo_desde_atendimento;
      linha.appendChild(colunaColaborador);
      linha.appendChild(colunaTempo);
      tabela.appendChild(linha);
    }
    if (tabela !== null && tabela.rows !== undefined) {
      for (var i = 0; i < tabela.rows.length; i++) {
        var tempoEspera = tabela.rows[i].cells[1].innerText;
        tabela.rows[i].cells[1].style.backgroundColor = (tempoEspera < "00:05:00") ? "rgb(66, 226, 184)" : (tempoEspera < "00:10:00") ? "rgb(255, 242, 117)" : "rgb(234, 82, 111)";
      }
    }
  }
}

// Função para preencher a tabela perdidas com os dados recebidos via JSON
function preencherTabelaPerdidas(dados) {
  var tabela = document.getElementById('tabela-perdidas');
  tabela.innerHTML = ''; // Limpa a tabela antes de preenchê-la novamente
  if (dados.pl !== null && dados.pl !== undefined) {
    for (var i = 0; (i < 4 && i < dados.pl.length); i++) {
      var numero = dados.pl[i].numero;
      var tempo_de_espera = dados.pl[i].tempo_de_espera;
      var linha = document.createElement('tr');
      var colunaNumero = document.createElement('td');
      var colunaTempo = document.createElement('td');
      colunaNumero.innerText = numero;
      colunaTempo.innerText = tempo_de_espera;
      linha.appendChild(colunaNumero);
      linha.appendChild(colunaTempo);
      tabela.appendChild(linha);
    }
    if (tabela !== null && tabela.rows !== undefined) {
      for (var i = 0; i < tabela.rows.length; i++) {
        var tempoEspera = tabela.rows[i].cells[1].innerText;
        tabela.rows[i].cells[1].style.backgroundColor = (tempoEspera < "00:05:00") ? "rgb(66, 226, 184)" : (tempoEspera < "00:10:00") ? "rgb(255, 242, 117)" : "rgb(234, 82, 111)";
      }
    }
  }
}

function atualizarStatus(dados) {
  // Obtém o valor retornado da consulta

  var fila_qtd = dados.f;
  var atendendo_qtd = dados.a;
  var perdidas_qtd = dados.p;
  var fila_media = dados.me;
  var atendendo_media = dados.ma;
  var desistencia_media = dados.md;
  var total = 0;
  var maior_tempo_e = dados.mt;


  if (dados.t !== null && dados.t !== undefined) {
    total = dados.t;
  }
  // Atualiza o conteúdo das tags correspondentes com o valor retornado
  if (total !== null && total !== undefined) {
    document.getElementById('total_qtd').textContent = total;
    document.getElementById('maior_espera').textContent = maior_tempo_e;
    document.getElementById('tempo-espera-medio').textContent = fila_media;
    document.getElementById('tempo-atendimento-medio').textContent = atendendo_media;
    document.getElementById('tempo-desistencia-medio').textContent = desistencia_media;
    document.getElementById('perdidas_qtd').textContent = perdidas_qtd;
    document.getElementById('atendendo_qtd').textContent = atendendo_qtd;
    document.getElementById('fila_qtd').textContent = fila_qtd;
  }
}


// Obtem token de autenticação 
var token = document.querySelector('input[name="_token"]').value;

setInterval(function () {
  axios.get('/suporte/public/central/result', {
    headers: {
      'X-CSRF-TOKEN': token
    }
  })
    .then(function (response) {
      
      atualizarStatus(response.data);
      preencherTabelaEspera(response.data);
      preencherTabelaAtendendo(response.data);
      preencherTabelaPerdidas(response.data);
    })
    .catch(function (error) {
      //console.log(error);
    });
}, 900);


