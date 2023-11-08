$(document).ready(function() {
  // Função para abrir Janela 1
  $("#btnAbrirJanela1").click(function() {
      $("#conteudoJanela").load("colaboradores/editar.blade.php");
      $("#janela").show();
  });

  // Função para abrir Janela 2
  $("#btnAbrirJanela2").click(function() {
      $("#conteudoJanela").load("janela2.blade.php");
      $("#janela").show();
  });

  // Função para fechar a janela
  $("#btnFecharJanela").click(function() {
      $("#janela").hide();
  });
});