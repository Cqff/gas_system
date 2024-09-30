document.addEventListener('DOMContentLoaded', function() {
    loadOrders();
    // fetchWorkers();
});

// function fetchWorkers() {
//     fetch('http://127.0.0.1/gas_system/api/gas-company_getWorkers.php')
//         .then(response => response.json())
//         .then(data => {
//             const workerDropdown = document.getElementById('worker');
//             workerDropdown.innerHTML = ''; // Clear existing options
//             data.forEach(worker => {
//                 const option = document.createElement('option');
//                 option.value = worker.WORKER_Id;
//                 option.text = worker.WORKER_Name;
//                 workerDropdown.add(option);
//             });
//         })
//         .catch(error => {
//             console.error('Error fetching workers:', error);
//         });
// }

function loadOrders() {
    const managerId = sessionStorage.getItem('managerId');
    fetch(`http://127.0.0.1/gas_system/api/gas-company_getOrders.php?companyId=${managerId}`)
        .then(response => response.json())
        .then(data => {
            const tableBody = document.querySelector('#orderTable tbody');
            tableBody.innerHTML = '';
            data.forEach(order => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${order.ORDER_Id}</td>
                    <td>${order.CUSTOMER_Id}</td>
                    <td>${order.CurrentGasAmount !== null ? order.CurrentGasAmount : '無資料'}</td>
                    <td>${order.CUSTOMER_PhoneNo !== null ? order.CUSTOMER_PhoneNo : '無資料'}</td>
                    <td>${order.DELIVERY_Address !== null ? order.DELIVERY_Address : '無資料'}</td>
                    <td>${order.EXPECT_Time !== null ? order.EXPECT_Time.split(' ')[0] : '無資料'}</td>
                    <td>${order.EXPECT_Time !== null ? order.EXPECT_Time.split(' ')[1] : '無資料'}</td>
                    <td>${order.CUSTOMER_Name !== null ? order.CUSTOMER_Name : '無資料'}</td>
                    <td>${order.Order_type !== null ? order.Order_type : '無資料'}</td>
                    <td>${order.Order_weight !== null ? order.Order_weight : '無資料'}</td>
                    <td>${order.Gas_Quantity !== null ? order.Gas_Quantity : '無資料'}</td>
                    <td>${order.Gas_Volume !== null ? order.Gas_Volume : '無資料'}</td>
                    <td>${order.WORKER_Name !== null ? order.WORKER_Name : '尚未指派'}</td>
                    <td>${order.sensor_id !== '0' ? order.sensor_id : '無資料'}</td>
                `;
                tableBody.appendChild(row);
            });
        })
        .catch(error => {
            console.error('Error fetching orders:', error);
        });
}

function submitOrder() {
    const orderData = {
        orderDate: document.getElementById('order-date').value,
        customerName: document.getElementById('customer-name').value,
        orderContent: document.getElementById('order-content').value,
        quantity: document.getElementById('quantity').value,
        unit: document.getElementById('unit').value,
        unitPrice: document.getElementById('unit-price').value,
        totalPrice: document.getElementById('total-price').value,
        status: document.getElementById('status').value,
        remarks: document.getElementById('remarks').value
    };

    fetch('../php/submitOrder.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(orderData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Order submitted successfully');
            loadOrders();
        } else {
            alert('Failed to submit order');
        }
    });

function filterOrders() {
    const filter = document.getElementById('filterInput').value.toLowerCase();
    const rows = document.querySelectorAll('#orderTable tbody tr');
    rows.forEach(row => {
        const cells = row.getElementsByTagName('td');
        let match = false;
        for (let i = 0; i < cells.length; i++) {
            if (cells[i].innerText.toLowerCase().includes(filter)) {
                match = true;
                break;
            }
        }
        row.style.display = match ? '' : 'none';
    });
}

document.getElementById('filterInput').addEventListener('keyup', filterOrders);

document.addEventListener('DOMContentLoaded', function() {
    const filterContainer = document.createElement('div');
    filterContainer.className = 'filter-container';
    filterContainer.innerHTML = `
        <input type="text" id="filterInput" class="filter-input" placeholder="篩選...">
    `;
    document.querySelector('.table-container').insertBefore(filterContainer, document.querySelector('#orderTable'));
});
}