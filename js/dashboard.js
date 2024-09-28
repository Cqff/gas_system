document.addEventListener('DOMContentLoaded', function() {
    loadOrders();
    fetchWorkers();
});

function fetchWorkers() {
    fetch('http://127.0.0.1/gas_system/api/gas-company_getWorkers.php')
        .then(response => response.json())
        .then(data => {
            const workerDropdown = document.getElementById('worker');
            workerDropdown.innerHTML = ''; // Clear existing options
            data.forEach(worker => {
                const option = document.createElement('option');
                option.value = worker.WORKER_Id;
                option.text = worker.WORKER_Name;
                workerDropdown.add(option);
            });
        })
        .catch(error => {
            console.error('Error fetching workers:', error);
        });
}

function loadOrders() {
    fetch('http://127.0.0.1/gas_system/api/gas-company_getOrders.php')
        .then(response => response.json())
        .then(data => {
            console.log(data);
            const tableBody = document.querySelector('#orderTable tbody');
            tableBody.innerHTML = '';
            data.forEach(order => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${order.ORDER_Id}</td>
                    <td>${order.CUSTOMER_Id}</td>
                    <td>${order.CurrentGasAmount !== null ? order.CurrentGasAmount : '無資料'}</td>
                    <td>${order.CUSTOMER_PhoneNo}</td>
                    <td>${order.DELIVERY_Address}</td>
                    <td>${order.EXPECT_Time !== null ? order.EXPECT_Time.split(' ')[0] : '無資料'}</td>
                    <td>${order.EXPECT_Time !== null ? order.EXPECT_Time.split(' ')[1] : '無資料'}</td>
                    <td>${order.CUSTOMER_Name}</td>
                    <td>${order.Order_type}</td>
                    <td>${order.Order_weight}</td>
                    <td>${order.Gas_Quantity}</td>
                    <td>${order.Gas_Volume}</td>
                    <td>${order.WORKER_Name !== '0' ? order.WORKER_Name : ''}</td>
                    <td>${order.sensor_id}</td>
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
}