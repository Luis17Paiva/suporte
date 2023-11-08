
// Função para renderizar o gráfico de pizza
function renderizarGraficoDePizza(dadosGrafico) {
    var options = {
        series: dadosGrafico.valores,
        chart: {
            type: 'donut',
        },
        responsive: [{
            breakpoint: 240,
            options: {
                chart: {
                    width: '50px',
                    height: '40px', 

                },
                legend: {
                    position: 'bottom'
                }
            }
        }],
        labels: dadosGrafico.colaboradores,
        colors: dadosGrafico.cores,
    };

    var chart = new ApexCharts(document.querySelector("#graficoPizza"), options);
    chart.render();
}