const bodyEl = document.getElementById('dealsBody');
const statusFilter = document.getElementById('statusFilter');
const countEl = document.getElementById('count');
let deals = [];

function render() {
    const status = statusFilter.value;
    const filtered = status === 'ALL'
        ? deals
        : deals.filter((deal) => deal.status === status);

    bodyEl.innerHTML = '';
    filtered.forEach((deal) => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${deal.id}</td>
            <td>${deal.title}</td>
            <td class="status">${deal.status}</td>
            <td>${deal.amount}</td>
        `;
        bodyEl.appendChild(row);
    });

    countEl.textContent = `Показано: ${filtered.length}`;
}

fetch('mock_deals.json')
    .then((response) => response.json())
    .then((data) => {
        deals = data;
        render();
    })
    .catch((error) => {
        bodyEl.innerHTML = '<tr><td colspan="4">Ошибка загрузки данных</td></tr>';
        console.error(error);
    });

statusFilter.addEventListener('change', render);
