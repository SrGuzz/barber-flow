

document.addEventListener('DOMContentLoaded', () => 
{
    const date = new Date(); 
    
    // Criar uma nova data para o primeiro dia do mês seguinte
    const primeiroDiaMesSeguinte = new Date(date.getFullYear(), date.getMonth() + 2, 1);

    // Voltar um dia para pegar o último dia do mês seguinte
    primeiroDiaMesSeguinte.setDate(0);

    const { Calendar } = window.VanillaCalendarPro;
    const options = {
        firstWeekday: 0,
        selectedWeekends: [0],
        dateMin: date.setDate(date.getDate() + 1),
        dateMax: primeiroDiaMesSeguinte,
        disableDates: pegarDomingosDoMes(date.getFullYear(), date.getMonth()),
        locale: 'pt-BR',

        onClickDate(self) {
            if(self.context.selectedDates.length > 0){
                Livewire.dispatch('appointment::date',  self.context.selectedDates );
            }
        },
    };
    const calendar = new Calendar('#calendar', options);
    calendar.init();
});

function pegarDomingosDoMes(ano, mes) {
    const domingos = [];
    const data = new Date(ano, mes, 1);

    while (data.getMonth() === mes || data.getMonth() === mes + 1) {
        if (data.getDay() === 0) {
        domingos.push(new Date(data)); // Clona a data
        }
        data.setDate(data.getDate() + 1); // Vai para o próximo dia
    }

    return domingos;
}   

function dataFormatada()
{
    const hoje = new Date();

    const ano = hoje.getFullYear();                   // 2025
    const mes = String(hoje.getMonth() + 1).padStart(2, '0');  // 05 (mês começa do 0)
    const dia = String(hoje.getDate()).padStart(2, '0');       // 09

    const dataFormatada = `${ano}-${mes}-${dia}`;

    return dataFormatada;
}